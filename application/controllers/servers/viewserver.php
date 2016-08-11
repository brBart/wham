<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Viewserver extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        Essentials::checkIfIpIsAllowed();
        if (Mysession::getVar('username') == NULL) {
            header('Location: ' . base_url());
            exit;
        }    
    }
    
    public function index() {
        $redirect_data['breadactive'] = 'Servers';
        $redirect_data['active_in_top_menu'] = 'servers';
        $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
        $redirect_data['message'] = "<b>ERROR: </b>Invalid server argument specified.";
        $this->load->view('redirect', $redirect_data);
    }
    
    public function info($s_id=0) {
        $sql = "SELECT server.s_id, server.s_name, server.s_isactive, server.s_ip, server.s_rack, 
                server.s_hostname, server.s_dateofc, server.s_dateofm, 
                server.s_notes, datacenter.dc_name, datacenter.dc_id, 
                datacenter.dc_location, panels.p_name FROM server 
                LEFT JOIN datacenter ON (server.s_dc=datacenter.dc_id) 
                LEFT JOIN panels ON (server.s_cp=panels.p_id) WHERE server.s_id=?";
        
        $is_invalid = FALSE;
        
        if (is_numeric($s_id) && $s_id > 0) {
            $args = array();
            $query = $this->db->query($sql, (int)$s_id);
            if ($query->num_rows() == 1) {
                $args['server'] = $query->row();
                
                if (Mysession::getVar("username") == "admin") {
                    $action_sql = "SELECT * FROM logs WHERE log_type=? AND 
                        log_server = ? ORDER BY log_id DESC LIMIT 1000";

                    $action_query = $this->db->query($action_sql, array("ACTION", (int)$s_id));
                    if ($action_query->num_rows() > 0)
                        $args["action_result"] = $action_query->result();
                }
                
                
                $this->load->view('servers/viewserver/info', $args);
            } else {
                $is_invalid = TRUE;
            }
        } else
            $is_invalid = TRUE;
        
        if ($is_invalid === TRUE) {
            $redirect_data['breadactive'] = 'Servers';
            $redirect_data['active_in_top_menu'] = 'servers';
            $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
            $redirect_data['message'] = "<b>ERROR: </b>Invalid server argument specified.";
            $this->load->view('redirect', $redirect_data);
        }
    }
    
    public function edit($s_id=0) {
        Essentials::checkWhamUserRole("edit_server", 1);
        
        $sql = "SELECT server.s_id, server.s_name, server.s_ip, server.s_rack, 
                server.s_hostname, server.s_dateofc, server.s_dateofm, 
                server.s_notes, datacenter.dc_name, datacenter.dc_id, 
                datacenter.dc_location, panels.p_name FROM server 
                LEFT JOIN datacenter ON (server.s_dc=datacenter.dc_id) 
                LEFT JOIN panels ON (server.s_cp=panels.p_id) WHERE server.s_id=?";
        
        $is_invalid = FALSE;
        
        if (is_numeric($s_id) && $s_id > 0) {
            $args = array();
            $query = $this->db->query($sql, $s_id);
            if ($query->num_rows() == 1) {
                
                if (isset($_POST["s_name"]) && isset($_POST["s_hostname"]) && isset($_POST["s_rack"]) && 
                        isset($_POST["s_dc"])) {
                    $date = now();
                    
                    $data = array(
                        "s_name" => $_POST["s_name"],
                        "s_hostname" => $_POST["s_hostname"],
                        "s_rack" => $_POST["s_rack"],
                        "s_dc" => $_POST["s_dc"],
                        "s_dateofm" => $date
                    );
                    
                    if (TRUE == Essentials::checkWhamUserRole("view_server_note"))
                        $data["s_notes"] = Essentials::encrypt($_POST["s_notes"]);
                    
                    $this->db->where('s_id', $s_id);
                    $this->db->update('server', $data);
                    
                    Essentials::insert_log("ACTION", 1, "Server details updated - server id " . $s_id, "", $s_id);
                    
                    header("Location: " . site_url() . "/servers/viewserver/info/" . $s_id . "/");
                }
                
                $args['server'] = $query->row();
                
                $sql2 = "SELECT dc_id, dc_name, dc_location FROM datacenter";
                $query2 = $this->db->query($sql2);
                if ($query2->num_rows() > 0) {
                    $dcs = array();
                    foreach ($query2->result() as $row2) {
                        $dcs[(string)$row2->dc_id] = "$row2->dc_name, $row2->dc_location";
                    }
                    $args['dcs'] = $dcs;
                }
                
                $this->load->view('servers/viewserver/edit', $args);
            } else {
                $is_invalid = TRUE;
            }
        } else
            $is_invalid = TRUE;
        
        if ($is_invalid === TRUE) {
            $redirect_data['breadactive'] = 'Servers';
            $redirect_data['active_in_top_menu'] = 'servers';
            $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
            $redirect_data['message'] = "<b>ERROR: </b>Invalid server argument specified.";
            $this->load->view('redirect', $redirect_data);
        }
    }
    
    public function delete($s_id=0) {
            Essentials::checkWhamUserRole("delete_server", 1);
            
            // checking if s_id provided is valid or not
            $valid_server = FALSE;
            if (isset($s_id) && is_numeric($s_id) && $s_id > 0) {
                $sql = "SELECT s_id FROM server WHERE s_id=?";
                $query = $this->db->query($sql, (int)$s_id);
                
                if ($query->num_rows() == 1)
                    $valid_server = TRUE;
            }
            
            if ($valid_server == TRUE) {
                
                if (isset($_POST["server_id"]) && $_POST["server_id"] == $s_id && 
                        isset($_POST["confirm_msg"]) && $_POST["confirm_msg"] == "I wish to delete this server from WHAM!") {
                    $this->db->where('s_id', (int)$s_id);
                    $this->db->delete('server');

                    $this->db->where('server_id', (int)$s_id);
                    $this->db->delete('cpanel');
                    
                    $this->db->where('server_id', (int)$s_id);
                    $this->db->delete('cpanelaccts');
                                       
                    Essentials::insert_log("ACTION", 1, "Server id " . $s_id . " successfully deleted");

                    $redirect_data['breadactive'] = 'Servers';
                    $redirect_data['active_in_top_menu'] = 'servers';
                    $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
                    $redirect_data['message'] = "<b>Server deleted successfully.</b>";
                    $this->load->view('redirect', $redirect_data);
                } else {
                    $args = array();
                    $args["s_id"] = $s_id;
                    
                    $sql = "SELECT s_hostname FROM server WHERE s_id = ?";
            
                    $query = $this->db->query($sql, (int)$s_id);

                    $row = $query->row();
                    $args["hostname"] = $row->s_hostname;

                    $this->load->view('servers/viewserver/deleteserver', $args);
                }
                
            } else {
                $redirect_data['breadactive'] = 'Servers';
                $redirect_data['active_in_top_menu'] = 'servers';
                $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
                $redirect_data['message'] = "<b>ERROR: Some arguments missing.</b>";
                $this->load->view('redirect', $redirect_data);
            }
        }
    
    public function whm($s_id=0) {
        $args = array();
        $args["s_id"] = $s_id;
        
        $is_cpanel_or_not = Cpanelwhm::checkServerIsCpanel($s_id);
        
        if ($is_cpanel_or_not["code"] !=0) {
            $redirect_data['breadactive'] = 'Servers';
            $redirect_data['active_in_top_menu'] = 'servers';
            $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
            $redirect_data['message'] = "<b>ERROR </b>code: " .
                    $is_cpanel_or_not['code'] . "<br/>" . $is_cpanel_or_not['message']. "</p>";
            $this->load->view('redirect', $redirect_data);
        } else {
            $should_sync = Cpanelwhm::checkIsItTimeToSync($s_id);
            
            if ($should_sync === TRUE) {
                Mysession::setVar("sync_server", $s_id);
                $this->load->view('servers/viewserver/whm_initialize', $args);  
            } else {
                
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";
                $query = $this->db->query($sql, (int)$s_id);
                
                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                
                $sql1 = "SELECT apis_available, priv_list FROM cpanel WHERE server_id = ?";
                $query1 = $this->db->query($sql1, (int)$s_id);
                
                $row1 = $query1->row();
                $args["apis_available"] = json_decode($row1->apis_available);
                $args["priv_list"] = json_decode($row1->priv_list);
                
                if (Mysession::getVar("show_load_avg") == "TRUE") { 
                    if (TRUE === Cpanelwhm::hasApi($s_id, "loadavg", $args["apis_available"])) {
                        $load = Cpanelwhm::executeAPI($s_id, "loadavg");
                        if ($load != FALSE)
                            $args["load_txt"] = $load['one'] . ", " . $load['five'] . ", " . $load['fifteen'];
                    }
                }
                $this->load->view('servers/viewserver/whm', $args);
            }
            
        }
    }
    
    public function whm_listaccts($s_id=0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        $is_cpanel_or_not = Cpanelwhm::checkServerIsCpanel($s_id);
        
        if ($is_cpanel_or_not["code"] !=0) {
            $redirect_data['breadactive'] = 'Servers';
            $redirect_data['active_in_top_menu'] = 'servers';
            $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
            $redirect_data['message'] = "<b>ERROR </b>code: " .
                    $is_cpanel_or_not['code'] . "<br/>" . $is_cpanel_or_not['message']. "</p>";
            $this->load->view('redirect', $redirect_data);
        } else {
            $account_list = array();
            $sql = "SELECT s_hostname FROM server WHERE s_id = ?";
            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;

            $args["account_list"] = Cpanelwhm::fetchAccountsFromServer($s_id);
            $this->load->view('servers/viewserver/whm/listaccts', $args);
        }
    }
    
    public function whm_createacct($s_id=0) {
        Essentials::checkWhamUserRole("add_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "createacct", "priv" => "create-acct"))) {
            if (isset($_POST["user_name"]) && isset($_POST["domain_name"]) && isset($_POST["contactemail"]) && isset($_POST["plan_name"])) {
                $api_post = array(
                    "username" => $_POST["user_name"],
                    "domain" => $_POST["domain_name"],
                    "plan" => $_POST["plan_name"],
                    "contactemail" => $_POST["contactemail"]
                );
                $api_string = "createacct?" . http_build_query($api_post);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "New account created - " . $_POST["user_name"] . " - " . $_POST["domain_name"];
                    
                    
                    $api_post1 = array("searchtype" => "user",
                        "search" => '^' . $_POST["user_name"] . '$');
                
                    $api_string1 = "listaccts?" . http_build_query($api_post1);

                    $result_json1 = Cpanelwhm::executeAPI($s_id, $api_string1);
                    
                    if ($result_json1 != FALSE && isset($result_json1["acct"])) {
                        $status_final1 = $result_json1["acct"][0];
                        
                        $ins_cols = array ("user", "domain", "email", "ip", "owner", "plan",
                            "disklimit", "diskused", "suspended", "suspendreason");
                        
                        $insert_array = array(); 
                        $insert_array["server_id"] = $s_id;

                        foreach ($ins_cols as $cur_col)
                            $insert_array[$cur_col] = $status_final1[$cur_col];

                        $this->db->insert("cpanelaccts", $insert_array);
                    }
                    
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    echo $status_final[0]["rawout"];
                    
                }
                else{
                    $ins_str = "New account creation failed - " . $_POST["user_name"] . " - " . $_POST["domain_name"] .
                            ", reason: " . $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                    echo $status_final[0]["statusmsg"];
                }
            } else {
                $package_list = array();
                $sql = "SELECT server.s_hostname, cpanel.package_list FROM server 
                    LEFT JOIN cpanel ON (server.s_id = cpanel.server_id) WHERE server.s_id = ?";
                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;

                $tmp_ = json_decode($row->package_list);
                $all_packages_on_server = $tmp_->package;

                $count = count($all_packages_on_server);

                for ($i=0; $i<$count; $i++) {
                    $each = $all_packages_on_server[$i];
                    array_push($package_list, $each->name);
                }

                $args['package_list'] = $package_list;
                $this->load->view('servers/viewserver/whm/createacct', $args);
            }
        }
    }
    
    public function whm_removeacct($s_id=0, $sel_acc="") {
        Essentials::checkWhamUserRole("delete_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if ($sel_acc != "")
            $args["sel_acc"] = $sel_acc;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "removeacct", "priv" => "kill-acct"))) {
            if (isset($_POST["account_name"]) && isset($_POST["confirm_string"]) && 
                    $_POST["confirm_string"] === "I wish to delete this account from my server. It is no longer required") 
            {
                $api_post = array("user" => $_POST["account_name"]);
                
                if (isset($_POST["keepdns"]) && $_POST["keepdns"] == 1)
                    $api_post["keepdns"] = $_POST["keepdns"];
                
                $api_string = "removeacct?" . http_build_query($api_post);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Account removed successfully - " . $_POST["account_name"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    
                    $this->db->where("server_id", $s_id);
                    $this->db->where("user", $_POST["account_name"]);
                    $this->db->delete("cpanelaccts");
                    
                    echo nl2br($status_final[0]["rawout"]);
                }
                else{
                    $ins_str = "Account removal failed - " . $_POST["account_name"] . ", reason: " .
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                    echo $status_final[0]["statusmsg"];
                }
            } else {
                $account_list = array();
                $sql = "SELECT s_hostname FROM server WHERE server.s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;

                $all_accounts = Cpanelwhm::fetchAccountsFromServer($s_id);
                
                if ($all_accounts != FALSE) {
                    $count = count($all_accounts);

                    for ($i = 0; $i < $count; $i++) {
                        $tmp_ac = $all_accounts[$i]->user;
                        $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                                $all_accounts[$i]->domain . " - " . 
                                $all_accounts[$i]->ip;
                        $account_list[$tmp_ac] = $tmp_ac_str;
                    }
                    ksort($account_list);

                    $args["account_list"] = $account_list;
                }
                $this->load->view('servers/viewserver/whm/removeacct', $args);
            }
        }
    }
    
    public function whm_modifyacct($s_id=0, $sel_acc="") {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if ($sel_acc != "")
            $args["sel_acc"] = $sel_acc;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "modifyacct", "priv" => "edit-account"))) {
            if (isset($_POST["user"]) && isset($_POST["newuser"]) && isset($_POST["domain"])) 
            {
                $api_post = array(
                    "user" => $_POST["user"],
                    "newuser" => $_POST["newuser"],
                    "domain" => $_POST["domain"],
                    "MAXPOP" => $_POST["MAXPOP"],
                    "MAXFTP" => $_POST["MAXFTP"],
                    "MAXSQL" => $_POST["MAXSQL"],
                    "MAXLST" => $_POST["MAXLST"],
                    "MAXSUB" => $_POST["MAXSUB"],
                    "MAXPARK" => $_POST["MAXPARK"],
                    "MAXADDON" => $_POST["MAXADDON"]
                    );
                
                $api_string = "modifyacct?" . http_build_query($api_post);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Account modified - " . $_POST["newuser"] . " - " . $_POST["domain"];
                    
                    
                    $api_post1 = array("searchtype" => "user",
                        "search" => '^' . $_POST["newuser"] . '$');
                
                    $api_string1 = "listaccts?" . http_build_query($api_post1);

                    $result_json1 = Cpanelwhm::executeAPI($s_id, $api_string1);
                    
                    if ($result_json1 != FALSE && isset($result_json1["acct"])) {
                        $status_final1 = $result_json1["acct"][0];
                        
                        $ins_cols = array ("user", "domain", "email", "ip", "owner", "plan",
                            "disklimit", "diskused", "suspended", "suspendreason");
                        
                        $update_array = array();
                        
                        foreach ($ins_cols as $cur_col)
                            $update_array[$cur_col] = $status_final1[$cur_col];

                        $this->db->where("server_id", $s_id);
                        $this->db->where("user", $_POST["user"]);
                        
                        $this->db->update("cpanelaccts", $update_array);
                    }
                    
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    echo nl2br(implode("\n", $status_final[0]["messages"]));
                }
                else{
                    $ins_str = "Account modification failed - " . $_POST["user"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                    echo $status_final[0]["statusmsg"];
                }
            } else {
                $account_list = array();
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;

                $all_accounts = Cpanelwhm::fetchAccountsFromServer($s_id);
                
                if ($all_accounts != FALSE) {
                    $count = count($all_accounts);

                    for ($i = 0; $i < $count; $i++) {
                        $tmp_ac = $all_accounts[$i]->user;
                        $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                                $all_accounts[$i]->domain . " - " . 
                                $all_accounts[$i]->ip;
                        $account_list[$tmp_ac] = $tmp_ac_str;
                    }
                    ksort($account_list);

                    $args["account_list"] = $account_list;
                
                    // will executeAPI as some details that can be modified is not present 
                    // in db (like MAXPOP etc)
                    
                    if (isset($_POST["account_to_change"])) {
                        for ($i = 0; $i < $count; $i++) {
                            if ($all_accounts[$i]->user == $_POST["account_to_change"]) {
                                $sel_user = Cpanelwhm::executeAPI($s_id, 'listaccts?searchtype=user&search=^' . $_POST["account_to_change"] . '$');
                                if ($sel_user != false && isset($sel_user["status"]) && $sel_user["status"] == 1)
                                    $args["account_selected"] = $sel_user["acct"][0];
                                break;
                            }
                        }
                    }
                }
                $this->load->view('servers/viewserver/whm/modifyacct', $args);
            }
        }
    }
    
    
    public function whm_updownacct($s_id=0, $sel_acc="")
    {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if ($sel_acc != "")
            $args["sel_acc"] = $sel_acc;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "changepackage", "priv" => "edit-account"))) 
        {
            if (isset($_POST["account_to_change"]) && isset($_POST["new_pkg"]))
            {
                
                $api_post = array(
                    "user" => $_POST["account_to_change"],
                    "pkg" => $_POST["new_pkg"]
                    );
                
                $api_string = "changepackage?" . http_build_query($api_post);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Package for account - " . $_POST["account_to_change"] . " has been changed to - " . $_POST["new_pkg"];
                    
                    
                    $api_post1 = array("searchtype" => "user",
                        "search" => '^' . $_POST["account_to_change"] . '$');
                
                    $api_string1 = "listaccts?" . http_build_query($api_post1);

                    $result_json1 = Cpanelwhm::executeAPI($s_id, $api_string1);
                    
                    if ($result_json1 != FALSE && isset($result_json1["acct"])) {
                        $status_final1 = $result_json1["acct"][0];
                        
                        $ins_cols = array ("user", "domain", "email", "ip", "owner", "plan",
                            "disklimit", "diskused", "suspended", "suspendreason");
                        
                        $update_array = array();
                        
                        foreach ($ins_cols as $cur_col)
                            $update_array[$cur_col] = $status_final1[$cur_col];

                        $this->db->where("server_id", $s_id);
                        $this->db->where("user", $_POST["account_to_change"]);
                        
                        $this->db->update("cpanelaccts", $update_array);
                    }
                    
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    echo $status_final[0]["rawout"];
                }
                else{
                    $ins_str = "Failed to modify package for user - " . $_POST["account_to_change"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                    echo $status_final[0]["statusmsg"];
                }
                
            } else
            {
                $result = Cpanelwhm::executeAPI($s_id, "listpkgs");
                if (!is_numeric($result) && isset($result["package"]) && count($result["package"]) > 0) {

                    $pkglist = array();
                    foreach($result["package"] as $tmp) {
                        array_push($pkglist, $tmp["name"]);
                    }
                    $args["pkglist"] = $pkglist;

                }

                $account_list = array();
                    $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                    $query = $this->db->query($sql, (int)$s_id);

                    $row = $query->row();
                    $args["hostname"] = $row->s_hostname;

                    $all_accounts = Cpanelwhm::fetchAccountsFromServer($s_id);

                    if ($all_accounts != FALSE) {
                        $count = count($all_accounts);

                        for ($i = 0; $i < $count; $i++) {
                            $tmp_ac = $all_accounts[$i]->user;
                            $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                                    $all_accounts[$i]->domain . " - " . 
                                    $all_accounts[$i]->ip;
                            $account_list[$tmp_ac] = $tmp_ac_str;
                        }
                        ksort($account_list);

                        $args["account_list"] = $account_list;
                    }


                $this->load->view('servers/viewserver/whm/updownacct', $args);
            }
        }
    }
    
    
    public function whm_modifyquota($s_id=0, $sel_acc="") {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if ($sel_acc != "")
            $args["sel_acc"] = $sel_acc;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "editquota", "priv" => "quota"))) {
            if (isset($_POST["user"]) && isset($_POST["quota"])) 
            {
                $api_post = array(
                    "user" => $_POST["user"],
                    "quota" => $_POST["quota"]
                    );
                
                $api_string = "editquota?" . http_build_query($api_post);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1){ 
                    $ins_str = "New quota set for user: " . $_POST["user"] . " to: " . $_POST["quota"];
                    
                    $api_post1 = array("searchtype" => "user",
                        "search" => '^' . $_POST["user"] . '$');
                
                    $api_string1 = "listaccts?" . http_build_query($api_post1);

                    $result_json1 = Cpanelwhm::executeAPI($s_id, $api_string1);
                    
                    if ($result_json1 != FALSE && isset($result_json1["acct"])) {
                        $status_final1 = $result_json1["acct"][0];
                        
                        $ins_cols = array ("user", "domain", "email", "ip", "owner", "plan",
                            "disklimit", "diskused", "suspended", "suspendreason");
                        
                        $update_array = array();
                        
                        foreach ($ins_cols as $cur_col)
                            $update_array[$cur_col] = $status_final1[$cur_col];

                        $this->db->where("server_id", $s_id);
                        $this->db->where("user", $_POST["user"]);
                        
                        $this->db->update("cpanelaccts", $update_array);
                    }
                    
                    
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    
                } else {
                    $ins_str = "Failed to modify quota for user: " . $_POST["user"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                
                echo $status_final[0]["statusmsg"];
            } else {
                $account_list = array();
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;

                $all_accounts = Cpanelwhm::fetchAccountsFromServer($s_id);
                
                if ($all_accounts != FALSE) {
                    $count = count($all_accounts);

                    for ($i = 0; $i < $count; $i++) {
                        $tmp_ac = $all_accounts[$i]->user;
                        $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                                $all_accounts[$i]->domain . " - " . 
                                $all_accounts[$i]->diskused . " / " . 
                                $all_accounts[$i]->disklimit;
                        $account_list[$tmp_ac] = $tmp_ac_str;
                    }
                    ksort($account_list);

                    $args["account_list"] = $account_list;
                    if (isset($_POST["account_to_change"])) {
                        for ($i = 0; $i < $count; $i++) {
                            if ($all_accounts[$i]->user == $_POST["account_to_change"]) {
                                $args["account_selected"] = $all_accounts[$i];
                                break;
                            }
                        }
                    }
                }
                $this->load->view('servers/viewserver/whm/modifyquota', $args);
            }
        }
    }
    
    public function whm_modifypasswd($s_id=0, $sel_acc="") {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if ($sel_acc != "")
            $args["sel_acc"] = $sel_acc;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "passwd", "priv" => "passwd"))) {
            if (isset($_POST["user"]) && isset($_POST["pass"])) 
            {
                $api_post = array(
                    "user" => $_POST["user"],
                    "pass" => $_POST["pass"]
                    );
                
                $api_string = "passwd?" . http_build_query($api_post);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["passwd"]))
                    $status_final = $result_json["passwd"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1){
                    $ins_str = "Password modified for account - " . $_POST["user"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    echo nl2br($status_final[0]["rawout"]);
                }
                else{
                    $ins_str = "Password modification failed - " . $_POST["user"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                    echo $status_final[0]["statusmsg"];
                }
            } else {
                $account_list = array();
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;

                $all_accounts = Cpanelwhm::fetchAccountsFromServer($s_id);
                
                if ($all_accounts != FALSE) {
                    $count = count($all_accounts);

                    for ($i = 0; $i < $count; $i++) {
                        $tmp_ac = $all_accounts[$i]->user;
                        $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                                $all_accounts[$i]->domain . " - " . 
                                $all_accounts[$i]->ip;
                        $account_list[$tmp_ac] = $tmp_ac_str;
                    }
                    ksort($account_list);

                    $args["account_list"] = $account_list;
                    if (isset($_POST["account_to_change"])) {
                        for ($i = 0; $i < $count; $i++) {
                            if ($all_accounts[$i]->user == $_POST["account_to_change"]) {
                                $args["account_selected"] = $all_accounts[$i];
                                break;
                            }
                        }
                    }
                }
                $this->load->view('servers/viewserver/whm/modifypasswd', $args);
            }
        }
    }
    
    public function whm_listsuspended($s_id=0, $selected="") {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "listsuspended", "priv" => "suspend-acct"))) {
            if (isset($_POST["account_to_unsuspend"])) 
            {
                $api_post = array(
                    "user" => $_POST["account_to_unsuspend"]
                    );
                
                $api_string = "unsuspendacct?" . http_build_query($api_post);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1){
                    $ins_str = "Account unsuspended - " . $_POST["account_to_unsuspend"];
                    
                    $api_post1 = array("searchtype" => "user",
                        "search" => '^' . $_POST["account_to_unsuspend"] . '$');
                
                    $api_string1 = "listaccts?" . http_build_query($api_post1);

                    $result_json1 = Cpanelwhm::executeAPI($s_id, $api_string1);
                    
                    if ($result_json1 != FALSE && isset($result_json1["acct"])) {
                        $status_final1 = $result_json1["acct"][0];
                        
                        $ins_cols = array ("user", "domain", "email", "ip", "owner", "plan",
                            "disklimit", "diskused", "suspended", "suspendreason");
                        
                        $update_array = array();
                        
                        foreach ($ins_cols as $cur_col)
                            $update_array[$cur_col] = $status_final1[$cur_col];

                        $this->db->where("server_id", $s_id);
                        $this->db->where("user", $_POST["account_to_unsuspend"]);
                        
                        $this->db->update("cpanelaccts", $update_array);
                    }
                    
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    
                } else {
                    $ins_str = "Account unsuspension failed - " . $_POST["account_to_unsuspend"] . ", reason: " .
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo nl2br($status_final[0]["statusmsg"]);
            } else {
                $account_list = array();
                $account_list_table = array();
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;

                $sql2 = "SELECT * FROM cpanelaccts WHERE server_id = ? AND suspended = ?";
                $query2 = $this->db->query($sql2, array((int)$s_id, 1));
                
                if ($selected != "")
                    $args["selected"] = $selected;
                
                if ($query2->num_rows() > 0)
                    $all_accounts = $query2->result();
                
                if (isset($all_accounts)) {
                    $count = count($all_accounts);

                    for ($i = 0; $i < $count; $i++) {
                        array_push($account_list_table, $all_accounts[$i]);
                        $tmp_ac = $all_accounts[$i]->user;
                        $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                                $all_accounts[$i]->domain;
                        $account_list[$tmp_ac] = $tmp_ac_str;
                    }
                    ksort($account_list);

                    $args["account_list"] = $account_list;
                    $args["account_list_table"] = $account_list_table;
                }
                $this->load->view('servers/viewserver/whm/listsuspended', $args);
            }
        }
    }
    
    public function whm_suspendacct($s_id=0, $sel_acc="") {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if ($sel_acc != "")
            $args["sel_acc"] = $sel_acc;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "listsuspended", "priv" => "suspend-acct"))) {
            if (isset($_POST["account_to_suspend"]) && isset($_POST["reason"])) 
            {
                $api_post = array(
                    "user" => $_POST["account_to_suspend"],
                    "reason" => $_POST["reason"]
                    );
                
                $api_string = "suspendacct?" . http_build_query($api_post);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) { 
                    $ins_str = "Account suspended - " . $_POST["account_to_suspend"] . ", reason: " . $_POST["reason"];
                    
                    $api_post1 = array("searchtype" => "user",
                        "search" => '^' . $_POST["account_to_suspend"] . '$');
                
                    $api_string1 = "listaccts?" . http_build_query($api_post1);

                    $result_json1 = Cpanelwhm::executeAPI($s_id, $api_string1);
                    
                    if ($result_json1 != FALSE && isset($result_json1["acct"])) {
                        $status_final1 = $result_json1["acct"][0];
                        
                        $ins_cols = array ("user", "domain", "email", "ip", "owner", "plan",
                            "disklimit", "diskused", "suspended", "suspendreason");
                        
                        $update_array = array();
                        
                        foreach ($ins_cols as $cur_col)
                            $update_array[$cur_col] = $status_final1[$cur_col];

                        $this->db->where("server_id", $s_id);
                        $this->db->where("user", $_POST["account_to_suspend"]);
                        
                        $this->db->update("cpanelaccts", $update_array);
                    }
                    
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    
                } else {
                   $ins_str = "Account suspension failed - " . $_POST["account_to_suspend"] . ", reason: " . 
                           $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id); 
                }
                
                echo nl2br($status_final[0]["statusmsg"]);
            } else {
                $account_list = array();
                $sql = "SELECT s_hostname FROM server WHERE server.s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;

                $sql2 = "SELECT * FROM cpanelaccts WHERE server_id = ? AND suspended = ?";
                $query2 = $this->db->query($sql2, array((int)$s_id, 0));
                
                if ($query2->num_rows() > 0)
                    $all_accounts = $query2->result();
                
                if (isset($all_accounts)) {
                    $count = count($all_accounts);

                    for ($i = 0; $i < $count; $i++) {
                        if ($all_accounts[$i]->suspended == FALSE) {
                            $tmp_ac = $all_accounts[$i]->user;
                            $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                                    $all_accounts[$i]->domain;
                            $account_list[$tmp_ac] = $tmp_ac_str;
                        }
                    }
                    ksort($account_list);

                    $args["account_list"] = $account_list;
                }
                $this->load->view('servers/viewserver/whm/suspendacct', $args);
            }
        }
    }
    
    public function whm_listpkgs($s_id=0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "listpkgs", "priv" => "edit-pkg"))) {
            $pkg_list = array();
            $sql = "SELECT server.s_hostname, cpanel.package_list FROM server 
                    LEFT JOIN cpanel ON (server.s_id = cpanel.server_id) WHERE server.s_id = ?";
            
            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;

            $all_pkgs = json_decode($row->package_list);
            
            if (count($all_pkgs->package) > 0)
                $args["package_list"] = $all_pkgs->package;
            
            $this->load->view('servers/viewserver/whm/listpkgs', $args);
        }
    }
    
    public function whm_addpkg($s_id=0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "listpkgs", "priv" => "edit-pkg"))) {
            if (isset($_POST["name"]) && isset($_POST["quota"])) {
                
                $api_string = "addpkg?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "New package added - " . $_POST["name"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    Cpanelwhm::quickUpdatePackages($s_id);
                } else {
                    $ins_str = "Failed to add new package - " . $_POST["name"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo $status_final[0]["statusmsg"];
            } else {
                $sql = "SELECT server.s_hostname, cpanel.priv_list FROM server 
                        LEFT JOIN cpanel ON (server.s_id = cpanel.server_id) WHERE server.s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["priv_list"] = json_decode($row->priv_list);

                $this->load->view('servers/viewserver/whm/addpkg', $args);
            }
        }
    }
    
    public function whm_killpkg($s_id=0, $sel_pkg="") {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if ($sel_pkg != "")
            $args["sel_pkg"] = $sel_pkg;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "killpkg", "priv" => "edit-pkg"))) {
            if (isset($_POST["pkg"]) && isset($_POST["confirm_string"]) 
                    && $_POST["confirm_string"] == "I wish to delete this package from my server") 
            {
                $api_string = "killpkg?" . http_build_query(array("pkg" => $_POST["pkg"]));
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Package removed - " . $_POST["pkg"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    Cpanelwhm::quickUpdatePackages($s_id);
                } else {
                    $ins_str = "Failed to remove package - " . $_POST["pkg"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo $status_final[0]["statusmsg"];
            } else {
                $account_list = array();
                $sql = "SELECT server.s_hostname, cpanel.package_list FROM server 
                        LEFT JOIN cpanel ON (server.s_id = cpanel.server_id) WHERE server.s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                
                $all_pkgs = json_decode($row->package_list);
                
                if (count($all_pkgs->package) > 0)
                    $args["package_list"] = $all_pkgs->package;
            
                $this->load->view('servers/viewserver/whm/killpkg', $args);
            }
        }
    }
    
    public function whm_editpkg($s_id=0, $sel_pkg="") {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if ($sel_pkg != "")
            $args["sel_pkg"] = $sel_pkg;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "editpkg", "priv" => "edit-pkg"))) {
            if (isset($_POST["name"]) && isset($_POST["quota"])) {
                $api_string = "editpkg?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Package modified - " . $_POST["name"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    Cpanelwhm::quickUpdatePackages($s_id);
                } else {
                    $ins_str = "Package modification failed - " . $_POST["name"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo $status_final[0]["statusmsg"];
            } else {
                $account_list = array();
                $sql = "SELECT server.s_hostname, cpanel.package_list, cpanel.priv_list FROM server 
                        LEFT JOIN cpanel ON (server.s_id = cpanel.server_id) WHERE server.s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["priv_list"] = json_decode($row->priv_list);

                $all_pkgs = json_decode($row->package_list);
                
                if (count($all_pkgs->package) > 0)
                    $args["package_list"] = $all_pkgs->package;
                
                if (isset($_POST["package_to_modify"])) {
                    for ($i = 0 ; $i < count($args["package_list"]); $i++) {
                        if ($args["package_list"][$i]->name == $_POST["package_to_modify"]) {
                            $args["selected_package_to_modify"] = $args["package_list"][$i];
                            break;
                        }
                    }
                }
            
                $this->load->view('servers/viewserver/whm/editpkg', $args);
            }
        }
    }
    
    public function whm_listips($s_id=0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "listips", "priv" => "all"))) {
            $sql = "SELECT s_hostname FROM server WHERE s_id = ?";
            
            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;

            $api_string = "listips";
                
            $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
            if ($result_json != FALSE && isset($result_json["result"]))
                $args["ip_list"] = $result_json["result"];
            
            $this->load->view('servers/viewserver/whm/listips', $args);
        }
    }
    
    public function whm_addip($s_id=0) {
        Essentials::checkWhamUserRole("edit_server", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "addip", "priv" => "all"))) {
            if (isset($_POST["ip"]) && isset($_POST["netmask"])) 
            {
                $api_string = "addip?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["addip"]))
                    $status_final = $result_json["addip"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "New ip " . $_POST["ip"] . " added successfully";
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                } else {
                    $ins_str = "Failed to add new ip " . $_POST["ip"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo $status_final[0]["statusmsg"];
            } else {
                $account_list = array();
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                
                $this->load->view('servers/viewserver/whm/addip', $args);
            }
        }
    }
    
    public function whm_delip($s_id=0,$selected_ip="") {
        Essentials::checkWhamUserRole("edit_server", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "delip", "priv" => "all"))) {
            if (isset($_POST["ip"]) && isset($_POST["ethernetdev"]) && isset($_POST["skipifshutdown"])) 
            {
                $api_string = "delip?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["delip"]))
                    $status_final = $result_json["delip"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Ip " . $_POST["ip"] . " successfully removed";
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                } else {
                    $ins_str = "Removal of ip " . $_POST["ip"] . " failed, reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo $status_final[0]["statusmsg"];
            } else {
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";
            
                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;

                $api_string = "listips";

                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $args["ip_list"] = $result_json["result"];
                
                if ($selected_ip != "")
                    $args["selected_ip"] = $selected_ip;

                $this->load->view('servers/viewserver/whm/delip', $args);
                }
        }
    }
    
    public function whm_serverinfo($s_id=0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        $is_cpanel_or_not = Cpanelwhm::checkServerIsCpanel($s_id);
        
        if ($is_cpanel_or_not["code"] !=0) {
            $redirect_data['breadactive'] = 'Servers';
            $redirect_data['active_in_top_menu'] = 'servers';
            $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
            $redirect_data['message'] = "<b>ERROR </b>code: " .
                    $is_cpanel_or_not['code'] . "<br/>" . $is_cpanel_or_not['message']. "</p>";
            $this->load->view('redirect', $redirect_data);
        } else {
            $sql = "SELECT s_hostname FROM server WHERE s_id = ?";
            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;

            $load = Cpanelwhm::executeAPI($s_id, "loadavg");
            if ($load != FALSE)
                $args["load_txt"] = $load['one'] . " &nbsp; " . $load['five'] . " &nbsp; " . $load['fifteen'];
            
            $version = Cpanelwhm::executeAPI($s_id, "version");
            if ($version != FALSE)
                $args["version"] = $version['version'];
            
            if ($version != FALSE && $load != FALSE)
                $this->load->view('servers/viewserver/whm/serverinfo', $args);
            else
                $this->load->view('api_network_err');
        }
    }
    
    public function whm_servicestatus($s_id=0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        $is_cpanel_or_not = Cpanelwhm::checkServerIsCpanel($s_id);
        
        if ($is_cpanel_or_not["code"] !=0) {
            $redirect_data['breadactive'] = 'Servers';
            $redirect_data['active_in_top_menu'] = 'servers';
            $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
            $redirect_data['message'] = "<b>ERROR </b>code: " .
                    $is_cpanel_or_not['code'] . "<br/>" . $is_cpanel_or_not['message']. "</p>";
            $this->load->view('redirect', $redirect_data);
        } else {
            $sql = "SELECT s_hostname FROM server WHERE s_id = ?";
            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;

            $result_json = Cpanelwhm::executeAPI($s_id, "servicestatus");
            if ($result_json != FALSE && isset($result_json["result"]["status"]) && $result_json["result"]["status"] == 1){
                $args["service_list"] = $result_json["service"];
                $this->load->view('servers/viewserver/whm/servicestatus', $args);
            } else
                $this->load->view('api_network_err');
        }
    }
    
    public function whm_configureservice($s_id=0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "configureservice", "priv" => "all"))) {
            if (isset($_POST["service"]) && isset($_POST["enabled"]) && isset($_POST["monitored"])) 
            {
                $api_string = "configureservice?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Service " . $_POST["service"] . " modified successfully";
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                } else {
                    $ins_str = "Failed to modify service " . $_POST["service"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo $status_final[0]["statusmsg"];
            } else {
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";
            
                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;

                $api_string = "servicestatus";

                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]["status"]) && $result_json["result"]["status"] == 1)
                    $args["service_list"] = $result_json["service"];
                
                $this->load->view('servers/viewserver/whm/configureservice', $args);
            }
        }
    }
    
    public function whm_restartservice($s_id=0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "restartservice", "priv" => "all"))) {
            if (isset($_POST["service"])) 
            {
                $api_string = "restartservice?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["restart"]))
                    $status_final = $result_json["restart"];
                else {
                    $status_final[0]["rawout"] = "Network/API error";
                    $status_final[0]["result"] = 255;
                }
                
                if ($status_final[0]["result"] == 1) {
                    $ins_str = "Service " . $_POST["service"] . " successfully restarted";
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                } else {
                    $ins_str = "Restart of service " . $_POST["service"] . " failed, reason: " . 
                            $status_final[0]["rawout"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo nl2br($status_final[0]["rawout"]);
                
            } else {
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";
            
                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                
                $this->load->view('servers/viewserver/whm/restartservice', $args);
            }
        }
    }
    
    public function whm_reboot($s_id=0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "reboot", "priv" => "all"))) {
            if (isset($_POST["force"]) && isset($_POST["confirm_string"]) && 
                    $_POST["confirm_string"] == "I am about to reboot this server") 
            {
                $api_string = "reboot?" . http_build_query(array("force" => $_POST["force"]));
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["reboot"]))
                    $status_final = $result_json["reboot"];
                else {
                    $status_final = "Network/API error";
                }
                
                if ($status_final != "Network/API error") {
                    $ins_str = "Server restarted successfully";
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                } else {
                    $ins_str = "Failed to restart server, reason: " . $status_final;
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo $status_final;
                
            } else {
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";
            
                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                
                $this->load->view('servers/viewserver/whm/reboot', $args);
            }
        }
    }
    
    public function whm_setremotekey($s_id=0) {
        Essentials::checkWhamUserRole("edit_server", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (isset($_POST["user_name"]) && isset($_POST["password"]) && isset($_POST["remote_key"]) && $s_id > 0) 
        {
            $data = array(
                "user_name" => $_POST["user_name"],
                "password" => Essentials::encrypt($_POST["password"]),
                "remote_key" => Essentials::encrypt($_POST["remote_key"])
            );
            
            $this->db->where('server_id', $s_id);
            $this->db->update('cpanel', $data);
            
            Essentials::insert_log("ACTION", 1, "WHM Remote key / username / password modified successfully", "", $s_id);
            
            echo "Username, Password and Remote key updated..!";

        } else {
            $sql = "SELECT server.s_hostname, cpanel.remote_key, cpanel.password, cpanel.user_name FROM server 
                LEFT JOIN cpanel ON (server.s_id=cpanel.server_id) WHERE server.s_id = ?";

            $query = $this->db->query($sql, (int)$s_id);

            $is_cpanel_or_not = Cpanelwhm::checkServerIsCpanel($s_id);
            
            if ($query->num_rows() != 1) {
                $redirect_data['breadactive'] = 'Servers';
                $redirect_data['active_in_top_menu'] = 'servers';
                $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
                $redirect_data['message'] = "<b>ERROR </b>code: " .
                        $is_cpanel_or_not['code'] . "<br/>" . $is_cpanel_or_not['message']. "</p>";
                $this->load->view('redirect', $redirect_data);
            } else {
                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["remote_key"] = Essentials::decrypt($row->remote_key);
                $args["password"] = Essentials::decrypt($row->password);
                $args["user_name"] = $row->user_name;

                $this->load->view('servers/viewserver/whm/setremotekey', $args);
            }
        }
    }
    
    public function whm_syncserverinfo($s_id=0) {
        Essentials::checkIfServerIsActive($s_id);
        
        if ($s_id > 0) {
            $args["title"] = "Forceful Sync Requested";
            $args["s_id"] = $s_id;
            Mysession::setVar("sync_server", $s_id);
            $this->load->view('servers/viewserver/whm_initialize', $args);
        }
    }
    
    public function whm_adddns($s_id=0) {
        Essentials::checkWhamUserRole("add_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        if ($s_id == 0)
            exit;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "adddns", "priv" => "create-dns"))) {
        
            if (isset($_POST["domain"]) && isset($_POST["ip"])) {
                $api_string = "adddns?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "DNS zone for " . $_POST["domain"] . " with ip " . $_POST["ip"] . " successfully added";
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                } else {
                    $ins_str = "Failed to add DNS zone for " . $_POST["domain"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo $status_final[0]["statusmsg"];
            } else {
            
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["s_id"] = $s_id;

                $this->load->view("servers/viewserver/whm/adddns", $args);
            }
        }
    }
    
    public function whm_killdns($s_id=0) {
        Essentials::checkWhamUserRole("add_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        if ($s_id == 0)
            exit;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "killdns", "priv" => "kill-dns"))) {
        
            if (isset($_POST["domain"])) {
                unset($_POST["confirm_string"]);
                $api_string = "killdns?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["rawout"] = "";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "DNS zone for " . $_POST["domain"] . " successfully removed";
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                } else {
                    $ins_str = "Failed to delete DNS zone for " . $_POST["domain"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                }
                
                echo $status_final[0]["statusmsg"] . "<br/>" . nl2br($status_final[0]["rawout"]);
            } else {
            
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["s_id"] = $s_id;
                
                $result_json = Cpanelwhm::executeAPI($s_id, "listzones");
                if ($result_json != FALSE) {
                    $args["zonelist"] = $result_json["zone"];
                    $args["status"] = "success";
                }    
                else {
                    $args["status"] = "Network/API error";
                }

                $this->load->view("servers/viewserver/whm/killdns", $args);
            }
        }
    }
    
    public function whm_editdns($s_id=0) {
        Essentials::checkWhamUserRole("add_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "adddns", "priv" => "edit-dns"))) {
        
            $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;
            $args["s_id"] = $s_id;
                
            if (isset($_POST["domain"])) {
                $args["domain"] = $_POST["domain"]; // required to insert in javascript things..
                $api_string = "dumpzone?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                if ($result_json != FALSE && isset($result_json["result"]))
                    $args["dump_zone"] = $result_json["result"][0];
                else {
                    $args["dump_zone"] = FALSE;
                }
                
                $this->load->view("servers/viewserver/whm/editdns", $args); 
            } else {
            
                $result_json = Cpanelwhm::executeAPI($s_id, "listzones");
                if ($result_json != FALSE) {
                    $args["zonelist"] = $result_json["zone"];
                    $args["status"] = "success";
                }    
                else {
                    $args["status"] = "Network/API error";
                }

                $this->load->view("servers/viewserver/whm/editdns", $args);
            }
        }
    }
    
    public function whm_editdns_edit($s_id = 0) {
        Essentials::checkWhamUserRole("add_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "adddns", "priv" => "edit-dns"))) {
            $api_string = "editzonerecord?" . http_build_query($_POST);

            $result_json = Cpanelwhm::executeAPI($s_id, $api_string);

            if ($result_json != FALSE && isset($result_json["result"])) {
                if ($result_json["result"][0]["status"] == 1) {
                    $ins_str = "DNS Record edited, zone: " . $_POST["domain"] . ", line: " . $_POST["Line"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    echo "success";
                }
                else
                    echo $result_json["result"][0]["statusmsg"];
            } else
                echo "error";
        }
    }
    
    public function whm_editdns_delete($s_id = 0) {
        Essentials::checkWhamUserRole("add_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "adddns", "priv" => "edit-dns"))) {
            $api_string = "removezonerecord?" . http_build_query($_POST);

            $result_json = Cpanelwhm::executeAPI($s_id, $api_string);

            if ($result_json != FALSE && isset($result_json["result"])) {
                if ($result_json["result"][0]["status"] == 1) {
                    $ins_str = "DNS Record removed, zone: " . $_POST["domain"] . ", line: " . $_POST["Line"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    echo "success";
                }
                else
                    echo $result_json["result"][0]["statusmsg"];
            } else
                echo "error";
        }
    }
    
    public function whm_editdns_add_rr($s_id = 0) {
        Essentials::checkWhamUserRole("add_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "adddns", "priv" => "edit-dns"))) {
            $api_string = "addzonerecord?" . http_build_query($_POST);

            $result_json = Cpanelwhm::executeAPI($s_id, $api_string);

            if ($result_json != FALSE && isset($result_json["result"])) {
                if ($result_json["result"][0]["status"] == 1)
                    echo "success";
                else
                    echo $result_json["result"][0]["statusmsg"];
            } else
                echo "error";
        }
    }
    
    public function whm_listresellers($s_id = 0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "listresellers", "priv" => "all"))) {
            
            if (isset($_POST["user"])) 
            {
                if (TRUE == Essentials::checkWhamUserRole("edit_server") || TRUE == Essentials::checkWhamUserRole("modify_account")) {
                    $api_string = "unsetupreseller?" . http_build_query($_POST);

                    $result_json = Cpanelwhm::executeAPI($s_id, $api_string);

                    if ($result_json != FALSE && isset($result_json["result"]))
                        $status_final = $result_json["result"];
                    else {
                        $status_final[0]["statusmsg"] = "Network/API error";
                        $status_final[0]["status"] = 255;
                    }

                    if ($status_final[0]["status"] == 1) {
                        $ins_str = "Reseller privileges removed for user " . $_POST["user"];
                        Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                        echo $ins_str;
                    } else {
                        $ins_str = "Could not remove reseller privileges for user " . $_POST["user"] . ", reason: " . 
                                $status_final[0]["statusmsg"];
                        Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                        echo $ins_str;
                    }
                } else
                    echo "You do not have enough privileges to perform this action";
                
            } 
            else {
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["s_id"] = $s_id;


                $result_json = Cpanelwhm::executeAPI($s_id, "listresellers");

                if ($result_json == FALSE) {
                    $args["error"] = "Network/API error";
                } else if ($result_json != FALSE && is_array($result_json["reseller"]) && count($result_json["reseller"]) == 0) {
                    $args["no_resellers"] = "No resellers found";
                } else if ($result_json != FALSE && is_array($result_json["reseller"]) && count($result_json["reseller"]) > 0) {
                    $args["reseller_list"] = $result_json["reseller"];
                    
                    $reseller_details = array();
                    
                    foreach($args["reseller_list"] as $reseller) {
                        $acc_det_json = Cpanelwhm::executeAPI($s_id, "acctcounts?user=" . $reseller);
                        if ($acc_det_json != FALSE) {
                            $reseller_details[$reseller] = $acc_det_json["reseller"];
                        }
                    }
                    $args["reseller_details"] = $reseller_details;
                }

                $this->load->view("servers/viewserver/whm/listresellers", $args);
            }
            
            
            
        }
    }
    
    public function whm_setupreseller($s_id = 0) {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "setupreseller", "priv" => "all"))) {
            
            if (isset($_POST["user"]) && isset($_POST["makeowner"])) {
                $api_string = "setupreseller?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Reseller privileges added for user " . $_POST["user"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    echo $ins_str;
                } else {
                    $ins_str = "Could not add reseller privileges for user " . $_POST["user"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                    echo $ins_str;
                }
            }
            else {
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["s_id"] = $s_id;

                $sql2 = "SELECT user FROM cpanelaccts WHERE server_id = ?";
                $query2 = $this->db->query($sql2, (int)$s_id);

                $fullaccounts = array();
                if ($query2->num_rows() > 0) {
                    foreach ($query2->result() as $row)
                        array_push($fullaccounts, $row->user);
                }

                if (count($fullaccounts) == 0) {
                    $args["error"] = "No accounts present in this server";

                } else {

                    $result_json = Cpanelwhm::executeAPI($s_id, "listresellers");

                    if ($result_json == FALSE) {
                        $args["error"] = "Network/API error";

                    } else if ($result_json != FALSE && is_array($result_json["reseller"]) && count($result_json["reseller"]) == 0) {
                        $args["acc_list"] = $fullaccounts;
                    } else if ($result_json != FALSE && is_array($result_json["reseller"]) && count($result_json["reseller"]) > 0) {

                        $tmp_arr = array();
                        foreach($fullaccounts as $acc) {
                            if (!in_array($acc, $result_json["reseller"]))
                                array_push($tmp_arr, $acc);
                        }

                        $args["acc_list"] = $tmp_arr;
                    }

                }

                $this->load->view("servers/viewserver/whm/setupreseller", $args);
            }
            
        }
    }
    
    
    public function whm_suspendreseller($s_id = 0) {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "setupreseller", "priv" => "all"))) {
            
            if (isset($_POST["user"]) && isset($_POST["reason"])) {
                $this->db->where("user", $_POST["user"]);
                $this->db->where("server_id", $s_id);
                $this->db->where("suspended", 1);
                
                $quer = $this->db->get("cpanelaccts");
                if ($quer->num_rows() > 0) {
                    echo "This account is already suspended.";
                    exit;
                }
                
                if (isset($_POST["suspend_all_sub_accounts"]) && $_POST["suspend_all_sub_accounts"] == "yes")
                    $suspend_all_sub_accounts = TRUE;
                
                unset($_POST["suspend_all_sub_accounts"]);
                
                $api_string = "suspendreseller?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Reseller " . $_POST["user"] . " suspended, reason: " . $_POST["reason"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    
                    $this->db->where("server_id", (int)$s_id);
                    $this->db->where("user", $_POST["user"]);
                    $this->db->update("cpanelaccts", array("suspended" => 1, "suspendreason" => $_POST["reason"]));
                    
                    echo $ins_str . "<br/>";
                } else {
                    $ins_str = "Failed to suspend reseller " . $_POST["user"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                    echo $ins_str . "<br/>";
                }
                
                if (isset($suspend_all_sub_accounts)) {
                    $sql = "SELECT user FROM cpanelaccts WHERE server_id = ? AND 
                        owner = ? AND suspended = ?";
                    $query = $this->db->query($sql, array($s_id, $_POST["user"], 0));
                    
                    if ($query->num_rows() > 0) {
                        $msg = "Sub Accounts of user " . $_POST["user"] . " suspended, results:<br/>";
                        foreach ($query->result() as $row) {
                            $api_post = array(
                                "user" => $row->user,
                                "reason" => $_POST["reason"]
                            );
                            
                            $api_string = "suspendacct?" . http_build_query($api_post);
                
                            $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                            if ($result_json != FALSE && isset($result_json["result"]))
                                $status_final = $result_json["result"];
                            else {
                                $status_final[0]["statusmsg"] = "Network/API error";
                                $status_final[0]["status"] = 255;
                            }
                            
                            if ($status_final[0]["status"] == 1) { 
                                $msg .= "Success - " . $row->user . "<br/>";
                                
                                $this->db->where("server_id", (int)$s_id);
                                $this->db->where("user", $row->user);
                                $this->db->update("cpanelaccts", array("suspended" => 1, "suspendreason" => $_POST["reason"]));
                            } else {
                            $msg .= "Failed - " . $row->user . "<br/>";
                            }
                        }
                        Essentials::insert_log("ACTION", 1, $msg, "", $s_id);
                        echo $msg;
                    }
                }
            }
            else {
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["s_id"] = $s_id;

                

                $result_json = Cpanelwhm::executeAPI($s_id, "listresellers");

                if ($result_json == FALSE) {
                    $args["error"] = "Network/API error";
                } else if ($result_json != FALSE && is_array($result_json["reseller"]) && count($result_json["reseller"]) == 0) {
                    $args["no_resellers"] = "No resellers found";
                } else if ($result_json != FALSE && is_array($result_json["reseller"]) && count($result_json["reseller"]) > 0) {
                    $args["reseller_list"] = $result_json["reseller"];
                }

                

                $this->load->view("servers/viewserver/whm/suspendreseller", $args);
            }
            
        }
    }
    
    
    public function whm_unsuspendreseller($s_id = 0) {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "unsetupreseller", "priv" => "all"))) {
            
            if (isset($_POST["user"])) {
                $this->db->where("user", $_POST["user"]);
                $this->db->where("server_id", $s_id);
                $this->db->where("suspended", 0);
                
                $quer = $this->db->get("cpanelaccts");
                if ($quer->num_rows() > 0) {
                    echo "This account is not suspended.";
                    exit;
                }
                
                if (isset($_POST["unsuspend_all_sub_accounts"]) && $_POST["unsuspend_all_sub_accounts"] == "yes")
                    $unsuspend_all_sub_accounts = TRUE;
                
                unset($_POST["unsuspend_all_sub_accounts"]);
                
                $api_string = "unsuspendreseller?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Reseller " . $_POST["user"] . " unsuspended successfully";
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    
                    $this->db->where("server_id", (int)$s_id);
                    $this->db->where("user", $_POST["user"]);
                    $this->db->update("cpanelaccts", array("suspended" => 0, "suspendreason" => "not suspended"));
                    
                    echo $ins_str . "<br/>";
                } else {
                    $ins_str = "Failed to unsuspend reseller " . $_POST["user"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                    echo $ins_str . "<br/>";
                }
                
                if (isset($unsuspend_all_sub_accounts)) {
                    $sql = "SELECT user FROM cpanelaccts WHERE server_id = ? AND 
                        owner = ? AND suspended = ?";
                    $query = $this->db->query($sql, array($s_id, $_POST["user"], 1));
                    
                    if ($query->num_rows() > 0) {
                        $msg = "Sub Accounts of user " . $_POST["user"] . " unsuspended, results:<br/>";
                        foreach ($query->result() as $row) {
                            $api_post = array(
                                "user" => $row->user
                            );
                            
                            $api_string = "unsuspendacct?" . http_build_query($api_post);
                
                            $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                            if ($result_json != FALSE && isset($result_json["result"]))
                                $status_final = $result_json["result"];
                            else {
                                $status_final[0]["statusmsg"] = "Network/API error";
                                $status_final[0]["status"] = 255;
                            }
                            
                            if ($status_final[0]["status"] == 1) { 
                                $msg .= "Success - " . $row->user . "<br/>";
                                
                                $this->db->where("server_id", (int)$s_id);
                                $this->db->where("user", $row->user);
                                $this->db->update("cpanelaccts", array("suspended" => 0, "suspendreason" => "not suspended"));
                            } else {
                            $msg .= "Failed - " . $row->user . "<br/>";
                            }
                        }
                        Essentials::insert_log("ACTION", 1, $msg, "", $s_id);
                        echo $msg;
                    }
                }
            }
            else {
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["s_id"] = $s_id;

                

                $result_json = Cpanelwhm::executeAPI($s_id, "listresellers");

                if ($result_json == FALSE) {
                    $args["error"] = "Network/API error";
                } else if ($result_json != FALSE && is_array($result_json["reseller"]) && count($result_json["reseller"]) == 0) {
                    $args["no_resellers"] = "No resellers found";
                } else if ($result_json != FALSE && is_array($result_json["reseller"]) && count($result_json["reseller"]) > 0) {
                    $args["reseller_list"] = $result_json["reseller"];
                }

                

                $this->load->view("servers/viewserver/whm/unsuspendreseller", $args);
            }
            
        }
    }
    
    
    
    public function redirect($resource, $s_id = 0, $user = "") {
        Essentials::checkIfServerIsActive($s_id);
        
        $this->db->cache_delete_all();
        if (strtoupper($resource) != "WHM" && strtoupper($resource) != "CPANEL")
            exit;
        
        if ($s_id == 0)
            exit;
        
        Essentials::checkIfServerIsActive($s_id);
        
        $this->load->library("cpanelautologin");
        
        $detail_arr = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        $url;
        
        $hasPriv = FALSE;
        if (Mysession::getVar("username") == "admin" || TRUE == Essentials::checkWhamUserRole("edit_server") || 
                (TRUE == Essentials::checkWhamUserRole("add_account") && TRUE == Essentials::checkWhamUserRole("modify_account") && 
                TRUE == Essentials::checkWhamUserRole("delete_account")) )
            $hasPriv = TRUE;
        
        if (strtoupper($resource) == "WHM") {
            
            $url = $this->cpanelautologin->getLoggedInUrl($detail_arr["username"], Essentials::decrypt($detail_arr["password"]), 
                $detail_arr["hostname"], "whm", '/');
            //echo $url;
            if ($url != FALSE && $hasPriv == TRUE) {
                header("Location: " . $url); 
            }
            else 
                header("Location: https://" . $detail_arr["hostname"] . ":2087/");
        }
        else if (strtoupper($resource) == "CPANEL" & $user != ""){
            $url = $this->cpanelautologin->getLoggedInUrl($detail_arr["username"], Essentials::decrypt($detail_arr["password"]), 
                $detail_arr["hostname"], "whm", '/xfercpanel/' . $user);
            if ($url != FALSE && $hasPriv == TRUE)
                header("Location: " . $url);
            else 
                header("Location: https://" . $detail_arr["hostname"] . ":2083/");
        }
             
    }
    
    public function whm_modifyacctowner($s_id =0) {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "setupreseller", "priv" => "all"))) {
            
            if (isset($_POST["user"]) && isset($_POST["owner"])) {
                $api_string = "modifyacct?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "Ownership of account " . $_POST["user"] . " changed, new owner: " . $_POST["owner"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    
                    $this->db->where("server_id", (int)$s_id);
                    $this->db->where("user", $_POST["user"]);
                    $this->db->update("cpanelaccts", array("owner" => $_POST["owner"]));
                    
                    echo $ins_str;
                } else {
                    $ins_str = "Failed to change ownership of user " . $_POST["user"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                    echo $ins_str;
                }
            }
            else 
            {
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["s_id"] = $s_id;


                $result_json = Cpanelwhm::executeAPI($s_id, "listresellers");

                if ($result_json == FALSE) {
                    $args["error"] = "Network/API error";
                } else if ($result_json != FALSE && is_array($result_json["reseller"]) && count($result_json["reseller"]) > 0) {
                    $args["reseller_list"] = $result_json["reseller"];
                }

                if (isset($args["reseller_list"])) {
                    $this->db->where("server_id", $s_id);
                    $query2 = $this->db->get("cpanelaccts");

                    if ($query2->num_rows() > 0) {
                        $tmp_list = array();

                        foreach($query2->result() as $row) {
                            array_push($tmp_list, $row->user);
                        }

                        $args["user_list"] = array();

                        foreach($tmp_list as $usr) {
                            if (!in_array($usr, $args["reseller_list"]))
                                    array_push($args["user_list"], $usr);
                        } 
                    }
                }

                $this->load->view("servers/viewserver/whm/modifyacctowner", $args);
            }
        }
    }
    
    public function whm_setsiteip($s_id = 0) {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "setsiteip", "priv" => "all"))) {
            
            if (isset($_POST["user"]) && isset($_POST["ip"])) {
                $api_string = "setsiteip?" . http_build_query($_POST);
                
                $result_json = Cpanelwhm::executeAPI($s_id, $api_string);
                
                if ($result_json != FALSE && isset($result_json["result"]))
                    $status_final = $result_json["result"];
                else {
                    $status_final[0]["statusmsg"] = "Network/API error";
                    $status_final[0]["status"] = 255;
                }
                
                if ($status_final[0]["status"] == 1) {
                    $ins_str = "IP Address of account " . $_POST["user"] . " changed, new ip: " . $_POST["ip"];
                    Essentials::insert_log("ACTION", 1, $ins_str, "", $s_id);
                    
                    $this->db->where("server_id", (int)$s_id);
                    $this->db->where("user", $_POST["user"]);
                    $this->db->update("cpanelaccts", array("ip" => $_POST["ip"]));
                    
                    echo $ins_str;
                } else {
                    $ins_str = "Failed to change ip address of account " . $_POST["user"] . " to " . $_POST["ip"] . ", reason: " . 
                            $status_final[0]["statusmsg"];
                    Essentials::insert_log("ACTION", 0, $ins_str, "", $s_id);
                    echo $ins_str;
                }
            }
            
            else {
            
                $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

                $query = $this->db->query($sql, (int)$s_id);

                $row = $query->row();
                $args["hostname"] = $row->s_hostname;
                $args["s_id"] = $s_id;

                $sql2 = "SELECT * FROM cpanelaccts WHERE server_id = ?";
                $query2 = $this->db->query($sql2, (int)$s_id);

                if ($query2->num_rows() > 0)
                    $args["acc_list"] = $query2->result();
                else
                    $args["error"] = "No accounts present in server";

                if (!isset($args["error"])) {
                    $result_json = Cpanelwhm::executeAPI($s_id, "listips");

                    if ($result_json == FALSE) {
                        $args["error"] = "Network/API error";
                    } else if ($result_json != FALSE && is_array($result_json["result"]) && count($result_json["result"]) > 0) {
                        $args["ip_list"] = $result_json["result"];
                    }
                }

                $this->load->view("servers/viewserver/whm/setsiteip", $args);
            }
            
        }
    }
    
    public function whm_removeacct_multi($s_id = 0) {
        
        Essentials::checkWhamUserRole("delete_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "removeacct", "priv" => "kill-acct"))) {
            $account_list = array();
            $sql = "SELECT s_hostname FROM server WHERE server.s_id = ?";

            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;

            $all_accounts = Cpanelwhm::fetchAccountsFromServer($s_id);

            if ($all_accounts != FALSE) {
                $count = count($all_accounts);

                for ($i = 0; $i < $count; $i++) {
                    $tmp_ac = $all_accounts[$i]->user;
                    $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                            $all_accounts[$i]->domain . " - " . 
                            $all_accounts[$i]->ip;
                    $account_list[$tmp_ac] = $tmp_ac_str;
                }
                ksort($account_list);

                $args["account_list"] = $account_list;
            }
            $this->load->view('servers/viewserver/whm/removeacct_multi', $args);
        }
    }
    
    public function whm_modifyquota_multi($s_id = 0) {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "editquota", "priv" => "quota"))) {
            $account_list = array();
            $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;

            $all_accounts = Cpanelwhm::fetchAccountsFromServer($s_id);

            if ($all_accounts != FALSE) {
                $count = count($all_accounts);

                for ($i = 0; $i < $count; $i++) {
                    $tmp_ac = $all_accounts[$i]->user;
                    $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                            $all_accounts[$i]->domain . " - " . 
                            $all_accounts[$i]->diskused . " / " . 
                            $all_accounts[$i]->disklimit;
                    $account_list[$tmp_ac] = $tmp_ac_str;
                }
                ksort($account_list);

                $args["account_list"] = $account_list;
                if (isset($_POST["account_to_change"])) {
                    for ($i = 0; $i < $count; $i++) {
                        if ($all_accounts[$i]->user == $_POST["account_to_change"]) {
                            $args["account_selected"] = $all_accounts[$i];
                            break;
                        }
                    }
                }
            }
            $this->load->view('servers/viewserver/whm/modifyquota_multi', $args);
            
        }
    }
    
    public function whm_setsiteip_multi($s_id = 0) {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "setsiteip", "priv" => "all"))) {
            
            $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;
            $args["s_id"] = $s_id;

            $sql2 = "SELECT * FROM cpanelaccts WHERE server_id = ?";
            $query2 = $this->db->query($sql2, (int)$s_id);

            if ($query2->num_rows() > 0)
                $args["acc_list"] = $query2->result();
            else
                $args["error"] = "No accounts present in server";

            if (!isset($args["error"])) {
                $result_json = Cpanelwhm::executeAPI($s_id, "listips");

                if ($result_json == FALSE) {
                    $args["error"] = "Network/API error";
                } else if ($result_json != FALSE && is_array($result_json["result"]) && count($result_json["result"]) > 0) {
                    $args["ip_list"] = $result_json["result"];
                }
            }

            $this->load->view("servers/viewserver/whm/setsiteip_multi", $args);
        }
        
    }
    
    public function whm_modifyacctowner_multi($s_id =0) {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "setupreseller", "priv" => "all"))) {
         
            $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;
            $args["s_id"] = $s_id;


            $result_json = Cpanelwhm::executeAPI($s_id, "listresellers");

            if ($result_json == FALSE) {
                $args["error"] = "Network/API error";
            } else if ($result_json != FALSE && is_array($result_json["reseller"]) && count($result_json["reseller"]) > 0) {
                $args["reseller_list"] = $result_json["reseller"];
            }

            if (isset($args["reseller_list"])) {
                $this->db->where("server_id", $s_id);
                $query2 = $this->db->get("cpanelaccts");

                if ($query2->num_rows() > 0) {
                    $tmp_list = array();

                    foreach($query2->result() as $row) {
                        array_push($tmp_list, $row->user);
                    }

                    $args["user_list"] = array();

                    foreach($tmp_list as $usr) {
                        if (!in_array($usr, $args["reseller_list"]))
                                array_push($args["user_list"], $usr);
                    } 
                }
            }

            $this->load->view("servers/viewserver/whm/modifyacctowner_multi", $args);
        }
    }
    
    public function whm_suspendacct_multi($s_id = 0) {
        Essentials::checkWhamUserRole("modify_account", 1);
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "listsuspended", "priv" => "suspend-acct"))) {
            
            $account_list = array();
            $sql = "SELECT s_hostname FROM server WHERE server.s_id = ?";

            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;

            $sql2 = "SELECT * FROM cpanelaccts WHERE server_id = ? AND suspended = ?";
            $query2 = $this->db->query($sql2, array((int)$s_id, 0));

            if ($query2->num_rows() > 0)
                $all_accounts = $query2->result();

            if (isset($all_accounts)) {
                $count = count($all_accounts);

                for ($i = 0; $i < $count; $i++) {
                    if ($all_accounts[$i]->suspended == FALSE) {
                        $tmp_ac = $all_accounts[$i]->user;
                        $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                                $all_accounts[$i]->domain;
                        $account_list[$tmp_ac] = $tmp_ac_str;
                    }
                }
                ksort($account_list);

                $args["account_list"] = $account_list;
            }
            $this->load->view('servers/viewserver/whm/suspendacct_multi', $args);
            
        }
    }
    
    public function whm_unsuspendacct_multi($s_id = 0) {
        Essentials::checkIfServerIsActive($s_id);
        
        $args = array();
        $args["s_id"] = $s_id;
        
        if (TRUE === Cpanelwhm::checkPrivApiNRedirect($s_id, array("api" => "listsuspended", "priv" => "suspend-acct"))) {
            
            $account_list = array();
            $account_list_table = array();
            $sql = "SELECT s_hostname FROM server WHERE s_id = ?";

            $query = $this->db->query($sql, (int)$s_id);

            $row = $query->row();
            $args["hostname"] = $row->s_hostname;

            $sql2 = "SELECT * FROM cpanelaccts WHERE server_id = ? AND suspended = ?";
            $query2 = $this->db->query($sql2, array((int)$s_id, 1));


            if ($query2->num_rows() > 0)
                $all_accounts = $query2->result();

            if (isset($all_accounts)) {
                $count = count($all_accounts);

                for ($i = 0; $i < $count; $i++) {
                    array_push($account_list_table, $all_accounts[$i]);
                    $tmp_ac = $all_accounts[$i]->user;
                    $tmp_ac_str = $all_accounts[$i]->user . " - " . 
                            $all_accounts[$i]->domain;
                    $account_list[$tmp_ac] = $tmp_ac_str;
                }
                ksort($account_list);

                $args["account_list"] = $account_list;
                $args["account_list_table"] = $account_list_table;
            }
            
            $this->load->view('servers/viewserver/whm/unsuspendacct_multi', $args);
            
        }
    }
    
    
}
