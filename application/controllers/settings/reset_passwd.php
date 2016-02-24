<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reset_passwd extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        Essentials::checkIfIpIsAllowed();
        if (Mysession::getVar('username') == NULL) {
            header('Location: ' . base_url());
            exit;
        }    
    }

    public function index()
    {
        if (isset($_POST["old_pwd"]) && isset($_POST["new_pwd"]) && isset($_POST["new_pwd_cnf"])) {
            if (strlen($_POST["new_pwd"]) < 8 || $_POST["new_pwd"] != $_POST["new_pwd_cnf"])
                echo "New password should atleast be 8 chars, and should match with confirm password";
            else if ($_POST["old_pwd"] == $_POST["new_pwd"])
                echo "New password cannot be the old one!";
            else if (Mysession::getVar('username') == "admin") {
                $user = "admin";
                $pass = Essentials::encrypt(md5($_POST['old_pwd']));

                $sql = "SELECT * FROM settings WHERE w_option=? AND w_val=?";
                $query = $this->db->query($sql, array('adminpassword', $pass));

                if ($query->num_rows() == 1) {
                    $new_pass = Essentials::encrypt(md5($_POST['new_pwd']));
                    $this->db->where("w_option", "adminpassword");
                    $this->db->update("settings", array ("w_val" => $new_pass));

                    Essentials::insert_log("ACTION", 1, "Admin password modified successfully");

                    echo "Admin password has been changed successfully.";
                } else {
                    Essentials::insert_log("ACTION", 0, "Failed to reset admin user password, reason: 
                        current password is incorrect");
                    echo "Current password is wrong!";
                }
            } else {
                $user = Mysession::getVar('username');
                $pass = Essentials::encrypt(md5($_POST['old_pwd']));

                $sql = "SELECT * FROM users WHERE u_name=? AND u_pass=?";
                $query = $this->db->query($sql, array($user, $pass));

                if ($query->num_rows() == 1) {
                    $new_pass = Essentials::encrypt(md5($_POST['new_pwd']));
                    $this->db->where("u_name", $user);
                    $this->db->update("users", array ("u_pass" => $new_pass));

                    Essentials::insert_log("ACTION", 1, "Password for WHAM user " . $user . 
                            " modified successfully");

                    echo "Password has been changed successfully.";
                } else {
                    Essentials::insert_log("ACTION", 0, "Failed to reset password for WHAM user " . $user . 
                            ", reason: current password is incorrect");
                    echo "Current password is wrong!";
                }
            }
        } else {
            if (Mysession::getVar('username') == "admin")
                $this->load->view("settings/reset_passwd");
        }
    }        
}