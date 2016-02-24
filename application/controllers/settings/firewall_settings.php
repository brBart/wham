<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Firewall_settings extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        Essentials::checkIfIpIsAllowed();
        if (Mysession::getVar('username') != "admin") {
            header('Location: ' . site_url() . "/welcome/access_forbidden/");
            exit;
        }

    }

    public function index()
    { 
        if (isset($_POST["firewall_mode"])) {
            $firewall_mode = $_POST["firewall_mode"];

            if ($firewall_mode == "none") {
                $this->db->where("w_option", "firewall_mode");
                $this->db->update("settings", array("w_val" => "none"));
                Essentials::insert_log("ACTION", 1, "Firewall mode changed to: none");
                echo "Firewall mode changed to: none";
                exit;
            }

            $ipaddress = '';
            if (isset($_SERVER['HTTP_CLIENT_IP']))
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';

            if ($ipaddress == "UNKNOWN") {
                echo "Cannot determine your ip address, will not continue.";
                exit;
            }

            if ($firewall_mode == "ALLOW_ALL_BLOCK_FEW") {
                if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $range = preg_replace("/(\d+\.\d+\.\d+\.)(\d+)/", '${1}0', $ipaddress);

                    $sql2 = "SELECT * FROM blockedips WHERE b_ip = ? OR b_ip = ?";
                    $query2 = $this->db->query($sql2, array($ipaddress, $range));

                    if ($query2->num_rows() == 0) {
                        $this->db->where("w_option", "firewall_mode");
                        $this->db->update("settings", array("w_val" => "ALLOW_ALL_BLOCK_FEW"));
                        Essentials::insert_log("ACTION", 1, "Firewall mode changed to: ALLOW_ALL_BLOCK_FEW");
                        echo "Firewall mode changed to: ALLOW_ALL_BLOCK_FEW";
                        exit;
                    }
                    else {
                        Essentials::insert_log("ACTION", 0, "Cannot switch firewall to ALLOW_ALL_BLOCK_FEW mode, since this ip or range is listed in IP Blacklist.");
                        echo "Cannot switch to ALLOW_ALL_BLOCK_FEW mode. Remove the corresponding entry from WHAM!'s <b>IP Blacklist</b> first and then try again.";
                        exit;
                    }
                }

                if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                    $sql3 = "SELECT * FROM blockedips WHERE b_ip = ?";
                    $query3 = $this->db->query($sql3, $ipaddress);

                    if ($query3->num_rows() == 0) {
                        $this->db->where("w_option", "firewall_mode");
                        $this->db->update("settings", array("w_val" => "ALLOW_ALL_BLOCK_FEW"));
                        Essentials::insert_log("ACTION", 1, "Firewall mode changed to: ALLOW_ALL_BLOCK_FEW");
                        echo "Firewall mode changed to: ALLOW_ALL_BLOCK_FEW";
                        exit;
                    }
                    else {
                        Essentials::insert_log("ACTION", 0, "Cannot switch firewall to ALLOW_ALL_BLOCK_FEW mode, since this ip is listed in IP Blacklist.");
                        echo "Cannot switch to ALLOW_ALL_BLOCK_FEW mode. Remove the corresponding entry from WHAM!'s <b>IP Blacklist</b> first and then try again.";
                        exit;
                    }
                }
            }
            else if ($firewall_mode == "BLOCK_ALL_ALLOW_FEW") {
                if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $range2 = preg_replace("/(\d+\.\d+\.\d+\.)(\d+)/", '${1}0', $ipaddress);

                    $sql4 = "SELECT * FROM allowedips WHERE a_ip = ? OR a_ip = ?";
                    $query4 = $this->db->query($sql4, array($ipaddress, $range2));

                    if ($query4->num_rows() > 0) {
                        $this->db->where("w_option", "firewall_mode");
                        $this->db->update("settings", array("w_val" => "BLOCK_ALL_ALLOW_FEW"));
                        Essentials::insert_log("ACTION", 1, "Firewall mode changed to: BLOCK_ALL_ALLOW_FEW");
                        echo "Firewall mode changed to: BLOCK_ALL_ALLOW_FEW";
                        exit;
                    }
                    else {
                        Essentials::insert_log("ACTION", 0, "Cannot switch firewall to BLOCK_ALL_ALLOW_FEW mode, since this ip is not listed in IP Whitelist.");
                        echo "Cannot switch to BLOCK_ALL_ALLOW_FEW mode. Please add your ip or range in WHAM!'s <b>IP Whitelist</b> first and then try again.";
                        exit;
                    }
                }

                if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                    $sql5 = "SELECT * FROM allowedips WHERE a_ip = ?";
                    $query5 = $this->db->query($sql5, $ipaddress);

                    if ($query5->num_rows() == 1) {
                        $this->db->where("w_option", "firewall_mode");
                        $this->db->update("settings", array("w_val" => "BLOCK_ALL_ALLOW_FEW"));
                        Essentials::insert_log("ACTION", 1, "Firewall mode changed to: BLOCK_ALL_ALLOW_FEW");
                        echo "Firewall mode changed to: BLOCK_ALL_ALLOW_FEW";
                        exit;
                    }
                    else {
                        Essentials::insert_log("ACTION", 0, "Cannot switch firewall to BLOCK_ALL_ALLOW_FEW mode, since this ip is not listed in IP Whitelist.");
                        echo "Cannot switch to BLOCK_ALL_ALLOW_FEW mode. Please add your ip or range in WHAM!'s <b>IP Whitelist</b> first and then try again.";
                        exit;
                    }
                }
            }

        } else {
            $args = array();
            $sql = "SELECT * FROM settings WHERE w_option =?";
            $query = $this->db->query($sql, "firewall_mode");

            if ($query->num_rows() == 1) {
                $row = $query->row();
                $args["current_mode"] = $row->w_val;
            } else {
                $this->db->insert("settings", array("w_option" => "firewall_mode", "w_val" => "none"));
                $args["current_mode"] = "none";
            }

            $sql2 = "SELECT * FROM allowedips";
            $query2 = $this->db->query($sql2);

            if ($query2->num_rows() > 0)
                $args["w_ips_list"] = $query2->result();

            $sql3 = "SELECT * FROM blockedips";
            $query3 = $this->db->query($sql3);

            if ($query3->num_rows() > 0)
                $args["b_ips_list"] = $query3->result();
            
            $query4 = $this->db->query($sql, "whitelist_url");
            
            if ($query4->num_rows() == 1) {
                $row = $query4->row();
                $args["whitelist_url"] = $row->w_val;
            } else {
                $this->db->insert("settings", array("w_option" => "whitelist_url", "w_val" => ""));
                $args["whitelist_url"] = "";
            }
            
            $query5 = $this->db->query($sql, "whitelist_passwd");
            
            if ($query5->num_rows() == 1) {
                $row = $query5->row();
                $args["whitelist_passwd"] = Essentials::decrypt($row->w_val);
            } else {
                $this->db->insert("settings", array("w_option" => "whitelist_passwd", "w_val" => ""));
                $args["whitelist_passwd"] = "";
            }
            
            $query6 = $this->db->query($sql, "firewall");
            if ($query6->num_rows() == 1) {
                $row = $query6->row();
                $args["firewall"] = $row->w_val;
            }

            $this->load->view("settings/firewall_settings", $args);
        }
    }
    
    public function add_to_whitelist() {
        if (isset($_POST["ip_address"]) && isset($_POST["comment"])) {
            
            $ipaddress = $_POST["ip_address"];
            
            if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || 
                    filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                
                $sql = "SELECT * FROM allowedips WHERE a_ip =?";
                $query = $this->db->query($sql, $ipaddress);
                
                if ($query->num_rows > 0) {
                    $ret = array(
                        "status" => 0,
                        "message" => "Cannot add, mentioned ip/range already present in IP whitelist."
                    );
                    Essentials::insert_log("ACTION", 0, "Failed to add " . $ipaddress . " to IP whitelist, ip/range already present");
                    
                    echo json_encode($ret);
                } else {
                    $dateofa = now();
                    $date_to_return = Essentials::toLocalTime($dateofa);
                    $this->db->insert("allowedips", array("a_ip" => $ipaddress, "a_dateofa" => (int)$dateofa, "a_comment" => $_POST["comment"]));
                    Essentials::insert_log("ACTION", 1, "Added " . $ipaddress . " to IP whitelist successfully");
                    $ret = array(
                        "status" => 1,
                        "message" => "IP " . $ipaddress . " has been added to whitelist successfully.",
                        "ip" => $ipaddress,
                        "comment" => $_POST["comment"],
                        "time" => $date_to_return
                    );
                    
                    echo json_encode($ret);
                }
            } else {
                echo json_encode(array("status" => 0, "message" => "Invalid ip address specified."));
            }
        }
    }
    
    public function remove_from_whitelist() {
        if (isset($_POST["ip_address"])) {
            
            $ipaddress = $_POST["ip_address"];
            
            if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || 
                    filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                
                $sql = "SELECT * FROM allowedips WHERE a_ip =?";
                $query = $this->db->query($sql, $ipaddress);
                
                if ($query->num_rows < 0) {
                    $ret = array(
                        "status" => 0,
                        "message" => "Cannot remove, mentioned ip/range missing in IP whitelist."
                    );
                    Essentials::insert_log("ACTION", 0, "Failed to remove " . $ipaddress . " from IP whitelist, ip/range not present");
                    
                    echo json_encode($ret);
                } else {
                    
                    $ipaddress_remote = '';
                    if (isset($_SERVER['HTTP_CLIENT_IP']))
                        $ipaddress_remote = $_SERVER['HTTP_CLIENT_IP'];
                    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                        $ipaddress_remote = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    else if(isset($_SERVER['HTTP_X_FORWARDED']))
                        $ipaddress_remote = $_SERVER['HTTP_X_FORWARDED'];
                    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                        $ipaddress_remote = $_SERVER['HTTP_FORWARDED_FOR'];
                    else if(isset($_SERVER['HTTP_FORWARDED']))
                        $ipaddress_remote = $_SERVER['HTTP_FORWARDED'];
                    else if(isset($_SERVER['REMOTE_ADDR']))
                        $ipaddress_remote = $_SERVER['REMOTE_ADDR'];
                    else
                        $ipaddress_remote = 'UNKNOWN';

                    if ($ipaddress_remote == "UNKNOWN") {
                        echo json_encode(array("status" => 0, "message" => "Cannot determine your ip address, will not continue."));
                        exit;
                    }
                    
                    $range = preg_replace("/(\d+\.\d+\.\d+\.)(\d+)/", '${1}0', $ipaddress_remote);
                    
                    if ($ipaddress == $ipaddress_remote || $ipaddress == $range)
                    {
                        $this->db->where("w_option", "firewall_mode");
                        $this->db->where("w_val", "BLOCK_ALL_ALLOW_FEW");
                        $res = $this->db->get("settings");
                        
                        if ($res->num_rows() == 1) {
                        
                            $sql2 = "SELECT * FROM blockedips WHERE b_ip = ? OR b_ip = ?";
                            $query2 = $this->db->query($sql2, array($ipaddress, $range));
                            if ($query2->num_rows() < 2) {
                                echo json_encode(array("status" => 0, "message" => "Skipped, removing this entry will prevent you from accessing WHAM!"));
                                exit;
                            }
                        }
                    }
                    
                    
                    $this->db->where("a_ip", $ipaddress);
                    $this->db->delete("allowedips");
                    Essentials::insert_log("ACTION", 1, "Removed " . $ipaddress . " from IP whitelist successfully");
                    $ret = array(
                        "status" => 1,
                        "ip" => $ipaddress,
                        "message" => "IP " . $ipaddress . " has been removed from whitelist successfully."
                    );
                    
                    echo json_encode($ret);
                }
            } else {
                echo json_encode(array("status" => 0, "message" => "Invalid ip address specified."));
            }
        }
    }
    
    public function add_to_blacklist() {
        if (isset($_POST["ip_address"]) && isset($_POST["comment"])) {
            
            $ipaddress = $_POST["ip_address"];
            
            if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || 
                    filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                
                $sql = "SELECT * FROM blockedips WHERE b_ip =?";
                $query = $this->db->query($sql, $ipaddress);
                
                if ($query->num_rows > 0) {
                    $ret = array(
                        "status" => 0,
                        "message" => "Cannot add, mentioned ip/range already present in IP blacklist."
                    );
                    Essentials::insert_log("ACTION", 0, "Failed to add " . $ipaddress . " to IP blacklist, ip/range already present");
                    
                    echo json_encode($ret);
                } else {
                    $dateofa = now();
                    $date_to_return = Essentials::toLocalTime($dateofa);
                    $this->db->insert("blockedips", array("b_ip" => $ipaddress, "b_dateofa" => (int)$dateofa, "b_comment" => $_POST["comment"]));
                    Essentials::insert_log("ACTION", 1, "Added " . $ipaddress . " to IP blacklist successfully");
                    $ret = array(
                        "status" => 1,
                        "message" => "IP " . $ipaddress . " has been added to blacklist successfully.",
                        "ip" => $ipaddress,
                        "comment" => $_POST["comment"],
                        "time" => $date_to_return
                    );
                    
                    echo json_encode($ret);
                }
            } else {
                echo json_encode(array("status" => 0, "message" => "Invalid ip address specified."));
            }
        }
    }
    
    public function remove_from_blacklist() {
        if (isset($_POST["ip_address"])) {
            
            $ipaddress = $_POST["ip_address"];
            
            if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || 
                    filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                
                $sql = "SELECT * FROM blockedips WHERE b_ip =?";
                $query = $this->db->query($sql, $ipaddress);
                
                if ($query->num_rows < 0) {
                    $ret = array(
                        "status" => 0,
                        "message" => "Cannot remove, mentioned ip/range missing in IP blacklist."
                    );
                    Essentials::insert_log("ACTION", 0, "Failed to remove " . $ipaddress . " from IP blacklist, ip/range not present");
                    
                    echo json_encode($ret);
                } else {
                    $this->db->where("b_ip", $ipaddress);
                    $this->db->delete("blockedips");
                    Essentials::insert_log("ACTION", 1, "Removed " . $ipaddress . " from IP blacklist successfully");
                    $ret = array(
                        "status" => 1,
                        "ip" => $ipaddress,
                        "message" => "IP " . $ipaddress . " has been removed from blacklist successfully."
                    );
                    
                    echo json_encode($ret);
                }
            } else {
                echo json_encode(array("status" => 0, "message" => "Invalid ip address specified."));
            }
        }
    }
    
    public function whitelist_url() {
        if (isset($_POST["whitelist_url"]) && isset($_POST["whitelist_passwd"])) {
            $whitelist_url = trim($_POST["whitelist_url"]);
            $whitelist_passwd = trim($_POST["whitelist_passwd"]);
            
            if (strlen($whitelist_url) < 8) {
                echo "Whitelist URL should atleast contain 8 chars.";
                exit;
            }
            
            if (strlen($whitelist_passwd) < 8) {
                echo "Whitelist password should atleast contain 8 chars.";
                exit;
            }
            
            $whitelist_pwd = Essentials::encrypt($whitelist_passwd);
            
            $this->db->where("w_option", "whitelist_url");
            $this->db->update("settings", array("w_val" => $whitelist_url));
            
            $this->db->where("w_option", "whitelist_passwd");
            $this->db->update("settings", array("w_val" => $whitelist_pwd));
            
            Essentials::insert_log("ACTION", 1, "Whitelist url and password modified successfully");
            
            echo "Whitelist url and password modified successfully";
        }
    }
}
