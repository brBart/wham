<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_settings extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        Essentials::checkIfIpIsAllowed();
        if (Mysession::getVar('username') != "admin") {
            header('Location: ' . site_url() . "/welcome/access_forbidden/");
            exit;
        }   
    }
    
    public function index() {
        if (isset($_POST["protocol"]) && isset($_POST["smtp_user"])) {
            $cancontinue = FALSE;
            
            if ($_POST["protocol"] == "smtp") {
                if (! isset($_POST["smtp_host"]) || strlen(trim($_POST["smtp_host"])) < 5)
                    echo "Invalid SMTP hostname. Cannot continue";
                else if (! isset($_POST["smtp_port"]) || !is_numeric($_POST["smtp_port"]) )
                    echo "Invalid SMTP port specified.";
                else if (! isset($_POST["smtp_user"]) || ! filter_var($_POST["smtp_user"], FILTER_VALIDATE_EMAIL))
                    echo "Does not seem to be a valid username. Should be an email address";
                else if (! isset($_POST["smtp_pass"]) || strlen(trim($_POST["smtp_pass"])) < 3)
                    echo "Does not seem to be a valid password.";
                else
                    $cancontinue = TRUE; 
            } else
                $cancontinue = TRUE;
            
            if ($cancontinue == TRUE) {
                $tmp_arr = array( "protocol" => $_POST["protocol"],
                    "smtp_user" => $_POST["smtp_user"] );
                
                if ($_POST["protocol"] == "smtp") {
                    if ($_POST["smtp_crypto"] == "ssl" || $_POST["smtp_crypto"] == "tls") 
                        $tmp_arr["smtp_crypto"] = $_POST["smtp_crypto"];
                    $tmp_arr["smtp_pass"] = $_POST["smtp_pass"];
                    $tmp_arr["smtp_host"] = $_POST["smtp_host"];
                    $tmp_arr["smtp_port"] = (int)$_POST["smtp_port"];
                }
                
                $update_str = json_encode($tmp_arr);
                
                $this->db->where("w_option", "email_settings");
                $this->db->update("settings", array("w_val" => $update_str));
                Essentials::insert_log("ACTION", 1, "Email settings updated successfully");
                
                echo "Settings have been saved successfully.";
            }
            
        } else {
            $args = array();
            $sql = "SELECT * FROM settings WHERE w_option=?";
            $query = $this->db->query($sql, "email_settings");

            if ($query->num_rows() == 1) {
                $row = $query->row();

                if (strlen($row->w_val) > 5) {
                    $tmp = json_decode($row->w_val);
                    $args["protocol"] = $tmp->protocol;
                    if (isset($tmp->smtp_crypto)) $args["smtp_crypto"] = $tmp->smtp_crypto;
                    if (isset($tmp->smtp_user)) $args["smtp_user"] = $tmp->smtp_user;
                    if (isset($tmp->smtp_pass)) $args["smtp_pass"] = $tmp->smtp_pass;
                    if (isset($tmp->smtp_host)) $args["smtp_host"] = $tmp->smtp_host;
                    if (isset($tmp->smtp_port)) $args["smtp_port"] = $tmp->smtp_port;
                }
            }

            $this->load->view("settings/email_settings", $args);
        }
    }
        
}