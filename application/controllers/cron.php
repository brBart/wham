<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends CI_Controller {

	public function __construct() {
            parent::__construct();
        }
        
        
        
        public function sync() {
            $this->db->cache_delete();
            if (TRUE == $this->input->is_cli_request()) {
                
                $this->db->join("server", "server.s_id = cpanel.server_id", "left");
                $this->db->where("server.s_isactive", "Y");
                $this->db->where("server.s_cp", 1);
                $result = $this->db->get("cpanel");

                if ($result->num_rows() > 0) {
                    foreach($result->result() as $row) {
                        $s_id = $row->server_id;
                        $this->update_access($s_id);
                        $this->update_privs($s_id);
                        $this->update_packages($s_id);
                        $this->update_accounts($s_id);

                        $now = now();
                        $this->db->where("server_id", (int)$s_id);
                        $this->db->update("cpanel", array("last_sync" => $now));
                    }
                }
            } else { echo "This script is meant to be executed via CLI."; }
            $this->db->cache_delete();
            
        }
        
        private function update_access($s_id) {
            $result = Cpanelwhm::executeAPI($s_id, "applist");
            if (!is_numeric($result) && isset($result["app"])) {
                $this->db->where("server_id", (int)$s_id);
                $this->db->update("cpanel", array("apis_available" => json_encode($result)));
            }
        }
        
        private function update_privs($s_id) {
            $result = Cpanelwhm::executeAPI($s_id, "myprivs");
            if (!is_numeric($result) && isset($result["privs"])) {
                $this->db->where("server_id", (int)$s_id);
                $this->db->update("cpanel", array("priv_list" => json_encode($result)));
            }
        }
        
        private function update_packages($s_id) {
            $result = Cpanelwhm::executeAPI($s_id, "listpkgs");
            if (!is_numeric($result) && isset($result["package"])) {
                $this->db->where("server_id", (int)$s_id);
                $this->db->update("cpanel", array("package_list" => json_encode($result)));
            }
        }
        
        private function update_accounts($s_id) {
            $result = Cpanelwhm::executeAPI($s_id, "listaccts");
            if (!is_numeric($result) && isset($result["acct"])) {

                $this->db->delete("cpanelaccts", array("server_id" => $s_id));

                $ins_cols = array ("user", "domain", "email", "ip", "owner", "plan",
                    "disklimit", "diskused", "suspended", "suspendreason");

                for($i = 0; $i < count($result["acct"]); $i++) {

                    $cur = $result["acct"][$i];

                    $insert_array = array();  // resetting array on each run of for..
                    $insert_array["server_id"] = $s_id;

                    foreach ($ins_cols as $cur_col)
                        $insert_array[$cur_col] = $cur[$cur_col];

                    $this->db->insert("cpanelaccts", $insert_array);
                }
            }
        }
        
}
