<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Whitelist extends CI_Controller {
    
    public function __construct() {
        parent::__construct();   
    }
    
    public function allow($url = "") {
        if ($url == "")
            exit;
        
        $sql = "SELECT * FROM settings WHERE w_option =?";
        
        $query1 = $this->db->query($sql, "firewall");
        if ($query1->num_rows() != 1)
            exit;
        
        $row1 = $query1->row();
        if ($row1->w_val != "TRUE")
            exit;
        
        $query2 = $this->db->query($sql, "firewall_mode");
        if ($query2->num_rows() != 1)
            exit;
        
        $row2 = $query2->row();
        if ($row2->w_val != "BLOCK_ALL_ALLOW_FEW")
            exit;
        
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
        
        if ($ipaddress == "UNKNOWN")
            exit;
        
        if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        
            $range = preg_replace("/(\d+\.\d+\.\d+\.)(\d+)/", '${1}0', $ipaddress);

            $sql3 = "SELECT * FROM allowedips WHERE a_ip = ? OR a_ip = ?";
            $query3 = $this->db->query($sql3, array($ipaddress, $range));

            if ($query3->num_rows() > 0)
                exit;
        }
        
        if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $sql4 = "SELECT * FROM allowedips WHERE a_ip = ?";
            $query4 = $CI->db->query($sql4, $ipaddress);

            if ($query4->num_rows() > 0)
                exit;
        }
        
        $query5 = $this->db->query($sql, "whitelist_url");
        if ($query5->num_rows() != 1)
            exit;
        
        $row5 = $query5->row();
        if ($url != $row5->w_val)
            exit;
        
        $args = array();
        
        if (isset($_POST["whitelist_passwd"])) {
            $query6 = $this->db->query($sql, "whitelist_passwd");
            if ($query6->num_rows() != 1)
                exit;
            
            $row6 = $query6->row();
            $cur_pass = Essentials::decrypt($row6->w_val);
            
            if ($_POST["whitelist_passwd"] == $cur_pass) {
                $now = now();
                $this->db->insert("allowedips", array( "a_ip" => $ipaddress, "a_dateofa" => $now, "a_comment" => "Added via whitelist url"  ));
                Essentials::insert_log("AUTH", 1, "Authenticated successfully via whitelist url, ip added to allowed list", "NOT_LOGGED_IN");
                Essentials::notifyWhiteListAuth("success");
                header("Location: " . base_url());
            } else {
                Essentials::insert_log("AUTH", 0, "Authentication failed via whitelist url", "NOT_LOGGED_IN");
                Essentials::notifyWhiteListAuth("fail");
                $args["incorrect_pwd"] = TRUE;
                $this->load->view("settings/whitelist_url", $args);
            }
            
            
            
        } else {
            $this->load->view("settings/whitelist_url", $args);
        }
    }
        
}