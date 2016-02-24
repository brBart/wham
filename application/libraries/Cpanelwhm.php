<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Cpanelwhm
 * 
 */

Class Cpanelwhm {
    
    /** 
     * Function checkServerIsCpanel
     * 
     * checks whether a server is a cpanel server and returns an array
     * with status and message:
     * 
     * On success: { 'code': 0, 'message': 'SUCCESS' } 
     * On failure: { 'code': 100, 'message': 'Not a Cpanel/WHM server, or
     *           username or remote key missing' }
     * 
     * @param integer $server_id Server id
     * @return array  
     */
    public static function checkServerIsCpanel($server_id) {
        $success = array(
            "code" => 0,
            "message" => "SUCCESS"
        );
        
        $failure = array(
            "code" => 100,
            "message" => "Not a Cpanel/WHM server, or username or remote key missing"
        );
        
        if (!is_numeric($server_id))
            return $failure;
        
        $CI =& get_instance();
        $sql = "SELECT * FROM cpanel WHERE server_id=?";
        $query = $CI->db->query($sql, (int)$server_id);
        
        if ($query->num_rows() != 1)
            return $failure;
        else {
            $row = $query->row();
            if (empty($row->user_name))
                return $failure;
            
            if (empty($row->remote_key))
                return $failure;
            
            return $success;
        }
    }
    
    /**
     * Function checkIsItTimeToSync
     * 
     * returns FALSE if cpanel data was last synced with WHAM! db within a day.
     * Else it returns TRUE (i.e., need to sync now)
     * 
     * @param integer $server_id
     * @return boolean 
     */
    public static function checkIsItTimeToSync($server_id) {
        $CI =& get_instance();
        $sql = "SELECT last_sync FROM cpanel WHERE server_id = ?";
        
        $query = $CI->db->query($sql, $server_id);
        $row = $query->row();
        
        $last_sync_time = $row->last_sync;
        $timestamp = now();
        
        $diff = $timestamp - $last_sync_time;
        
        if ($diff > 86400)
            return TRUE;
        
        return FALSE;
        
    }
    
    /**
     * Function getIpRemoteKeyUsername
     * 
     * returns a key->value array containing "ip", "username" and "remotekey"
     * from WHAM! db for a server_id
     * 
     * @param integer $server_id
     * @return array 
     */
    public static function getIpRemoteKeyUsername($server_id) {
        $CI =& get_instance();
        $sql = "SELECT cpanel.user_name, server.s_hostname, server.s_ip, cpanel.password, cpanel.remote_key FROM
            cpanel LEFT JOIN server ON (cpanel.server_id=server.s_id) 
            WHERE cpanel.server_id=?";
        
        $query = $CI->db->query($sql, $server_id);
        $row = $query->row();
        
        $return_array = array(
            "ip" => $row->s_ip,
            "hostname" => $row->s_hostname,
            "username" => $row->user_name,
            "password" => $row->password,
            "remotekey" => $row->remote_key
        );
        
        return $return_array;
    }
    
    public static function executeAPI($server_id, $api) {
        
        $srv_info = self::getIpRemoteKeyUsername($server_id);
        
        $ip = $srv_info["ip"];
        $whmusername = $srv_info["username"];
        $remotekey = Essentials::decrypt($srv_info["remotekey"]);
        
        
        // $api = "applist"
        $query = "https://$ip:2087/json-api/$api";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        $header[0] = "Authorization: WHM $whmusername:" . preg_replace("'(\r|\n)'","",$remotekey);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl, CURLOPT_URL, $query);
        $result = curl_exec($curl);
        curl_close($curl);
        if ($result == false) {
            return FALSE; // error
        }
        
        return json_decode($result, true);
    }
    
    /** 
     * Function hasApi
     * 
     * checks if an API is available to execute, return TRUE if available, or
     * FALSE if not. If $apilist_from_db argument is specified, then the 
     * $server_id argument has no importance. $apilist_from_db will be 
     * json_decode() of SQL command "SELECT apis_available FROM cpanel WHERE 
     * server_id = ?"
     * 
     * @param integer $server_id Server id
     * @param string $api Api function name to check for
     * @param mixed $apilist_from_db
     * @return boolean 
     */
    public static function hasApi($server_id, $api, $apilist_from_db = NULL) {
        $apis = array();
        
        if ($apilist_from_db != NULL) 
            $apis = $apilist_from_db->app;
        else {
            $CI =& get_instance();
            $sql = "SELECT apis_available FROM cpanel WHERE server_id = ?";
            $query = $CI->db->query($sql, (int)$server_id);
            $row = $query->row();
            $apis_parent = json_decode($row->apis_available);
            $apis = $apis_parent->app;
        }
        
        $match = preg_grep("/^$api$/", $apis);
        
        if ($match == NULL)
            return FALSE;
        
        return TRUE;
    }
    
    /** 
     * Function hasPriv
     * 
     * checks if a privilege is available, return TRUE if available, or
     * FALSE if not. If $privlist_from_db argument is specified, then the 
     * $server_id argument has no importance. $privlist_from_db will be 
     * json_decode() of SQL command "SELECT priv_list FROM cpanel WHERE 
     * server_id = ?"
     * 
     * @param integer $server_id Server id
     * @param string $priv Privilege name to check for
     * @param mixed $privlist_from_db
     * @return boolean 
     */
    public static function hasPriv($server_id, $priv, $privlist_from_db = NULL) {
        $privs = array();
        
        if ($privlist_from_db != NULL) 
            $privs = $privlist_from_db->privs;
        else {
            $CI =& get_instance();
            $sql = "SELECT priv_list FROM cpanel WHERE server_id = ?";
            $query = $CI->db->query($sql, (int)$server_id);
            $row = $query->row();
            $privs_parent = json_decode($row->priv_list);
            $privs = $privs_parent->privs;
        }
        if (isset($privs->all) && $privs->all == 1)
            return TRUE;  // all = 1 means root priv
        
        if (isset($privs->$priv) && $privs->$priv == 1)
            return TRUE;
        
        return FALSE;
    }
    
    public static function fetchAccountsFromServer($server_id) {
        $CI =& get_instance();
        $sql = "SELECT * FROM cpanelaccts WHERE server_id = ?";
        $query = $CI->db->query($sql, (int)$server_id);

        if ($query->num_rows() == 0)
            return FALSE;
        
        return $query->result();
    }
    
    public static function quickUpdateAccounts($server_id) {
        $result = self::executeAPI($server_id, "listaccts");
        if ($result != FALSE && isset($result["acct"])) {
            $CI =& get_instance();
            
            $CI->db->delete("cpanelaccts", array("server_id" => $server_id));
                        
            $ins_cols = array ("user", "domain", "email", "ip", "owner", "plan",
                "disklimit", "diskused", "suspended", "suspendreason");

            for($i = 0; $i < count($result["acct"]); $i++) {

                $cur = $result["acct"][$i];

                $insert_array = array();  // resetting array on each run of for..
                $insert_array["server_id"] = $server_id;

                foreach ($ins_cols as $cur_col)
                    $insert_array[$cur_col] = $cur[$cur_col];

                $CI->db->insert("cpanelaccts", $insert_array);
            }
            
        }
    }
    
    public static function quickUpdatePackages($server_id) {
        $result = self::executeAPI($server_id, "listpkgs");
        if (!is_numeric($result) && isset($result["package"])) {
            $CI =& get_instance();
            $CI->db->where("server_id", (int)$server_id);
            $CI->db->update("cpanel", array("package_list" => json_encode($result)));
        }
    }
    
    public static function checkPrivApiNRedirect($s_id, $arr) {
        $is_cpanel_or_not = self::checkServerIsCpanel($s_id);
        $has_priv = FALSE;
        $has_api = FALSE;
        $can_continue = FALSE;
        $CI =& get_instance();
        
        if ($is_cpanel_or_not["code"] ==0) {
            $has_api = self::hasApi($s_id, $arr["api"]);
            $has_priv = self::hasPriv($s_id, $arr["priv"]);
        }
        
        if ($has_priv === TRUE || $has_api === TRUE)
            $can_continue = TRUE;
        
        if ($is_cpanel_or_not["code"] !=0 || $can_continue === FALSE) {
            $redirect_data['breadactive'] = 'Servers';
            $redirect_data['active_in_top_menu'] = 'servers';
            $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
            $redirect_data['message'] = "<b>ERROR </b>code: 100_Cant_Continue</p>";
            $CI->load->view('redirect', $redirect_data);
            return FALSE;
        }
        return TRUE;
    }
}