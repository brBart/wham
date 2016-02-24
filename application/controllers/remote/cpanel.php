<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cpanel extends CI_Controller {
    
        public function __construct() {
            parent::__construct();
            Essentials::checkIfIpIsAllowed();
            if (Mysession::getVar('username') == NULL) {
                header('Location: ' . base_url());
                exit;
            }
        }
        
        public function update_access($s_id=0) {
            header('Content-type: application/json');
            if (Mysession::getVar("sync_server") == $s_id) {
                $is_cpanel_or_not = Cpanelwhm::checkServerIsCpanel($s_id);
                
                if ($is_cpanel_or_not["code"] == 0) {
                    $result = Cpanelwhm::executeAPI($s_id, "applist");
                    if (!is_numeric($result) && isset($result["app"])) {
                        
                        $this->db->where("server_id", (int)$s_id);
                        $this->db->update("cpanel", array("apis_available" => json_encode($result)));
                        
                        Essentials::insert_log("SYNC", 1, "WHM available access list synced successfully", "", $s_id);
                        
                        echo json_encode(array("status" => "success", "message" => "success"));
                    } else
                        echo json_encode(array("status" => "error", "message" => "network/api error!"));
                    
                } else
                    echo json_encode(array("status" => "error", "message" => "not cpanel or info missing"));
            } else 
                echo json_encode(array("status" => "error", "message" => "not authorized"));            
        }
        
        
        public function update_accounts($s_id=0) {
            header('Content-type: application/json');
            if (Mysession::getVar("sync_server") == $s_id) {
                $is_cpanel_or_not = Cpanelwhm::checkServerIsCpanel($s_id);
                
                if ($is_cpanel_or_not["code"] == 0) {
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
                        
                        Essentials::insert_log("SYNC", 1, "Cpanel accounts list synced successfully", "", $s_id);
                        
                        echo json_encode(array("status" => "success", "message" => "success"));
                    } else
                        echo json_encode(array("status" => "error", "message" => "network/api error!"));
                    
                } else
                    echo json_encode(array("status" => "error", "message" => "not cpanel or info missing"));
            } else 
                echo json_encode(array("status" => "error", "message" => "not authorized"));            
        }
        
        
        public function update_packages($s_id=0) {
            header('Content-type: application/json');
            if (Mysession::getVar("sync_server") == $s_id) {
                $is_cpanel_or_not = Cpanelwhm::checkServerIsCpanel($s_id);
                
                if ($is_cpanel_or_not["code"] == 0) {
                    $result = Cpanelwhm::executeAPI($s_id, "listpkgs");
                    if (!is_numeric($result) && isset($result["package"])) {
                        
                        $this->db->where("server_id", (int)$s_id);
                        $this->db->update("cpanel", array("package_list" => json_encode($result)));
                        
                        Essentials::insert_log("SYNC", 1, "WHM available packages list synced successfully", "", $s_id);
                        
                        echo json_encode(array("status" => "success", "message" => "success"));
                    } else
                        echo json_encode(array("status" => "error", "message" => "network/api error!"));
                    
                } else
                    echo json_encode(array("status" => "error", "message" => "not cpanel or info missing"));
            } else 
                echo json_encode(array("status" => "error", "message" => "not authorized"));            
        }
        
        public function update_privs($s_id=0) {
            header('Content-type: application/json');
            if (Mysession::getVar("sync_server") == $s_id) {
                $is_cpanel_or_not = Cpanelwhm::checkServerIsCpanel($s_id);
                
                if ($is_cpanel_or_not["code"] == 0) {
                    $result = Cpanelwhm::executeAPI($s_id, "myprivs");
                    if (!is_numeric($result) && isset($result["privs"])) {
                        $this->db->where("server_id", (int)$s_id);
                        $this->db->update("cpanel", array("priv_list" => json_encode($result)));
                        
                        Essentials::insert_log("SYNC", 1, "WHM available privileges list synced successfully", "", $s_id);
                        
                        echo json_encode(array("status" => "success", "message" => "success"));
                    } else
                        echo json_encode(array("status" => "error", "message" => "network/api error!"));
                    
                } else
                    echo json_encode(array("status" => "error", "message" => "not cpanel or info missing"));
            } else
                echo json_encode(array("status" => "error", "message" => "not authorized"));
        }
        
        public function unset_sync($s_id=0) {
            header('Content-type: application/json');
            if (Mysession::getVar("sync_server") == $s_id) {
                $time = now();
                
                $this->db->where("server_id", (int)$s_id);
                $this->db->update("cpanel", array("last_sync" => (int)$time));
                 
                Mysession::unsetVar("sync_server");
                echo json_encode(array("status" => "success", "message" => "success!"));
            } else 
                echo json_encode(array("status" => "error", "message" => "not authorized"));            
        }
        
}
