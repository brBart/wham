<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_roles extends CI_Controller {
    
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
            $args = array();
            $sql = "SELECT users.u_id, users.u_name, users.u_pass, users.u_fullname, users.u_roleid, 
                roles.role_name FROM users LEFT JOIN roles ON (users.u_roleid = roles.role_id)";
            
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0)
                $args["user_list"] = $query->result();
            
            // getting all roles
            $sql2 = "SELECT role_id, role_name FROM roles";
            $query2 = $this->db->query($sql2);
            if ($query2->num_rows() > 0)
                $args["role_list"] = $query2->result();
            
            $this->load->view("settings/users", $args);
	}
        
        public function viewroles() 
        {
            $cur_user = Mysession::getVar('username');
            
            // $cur_user should be admin to add new role
            
            if (isset($_POST["new_role_name"])) {
                $any_of = array("add_dc" , "delete_dc", "edit_dc", "view_dc_note",
                    "add_server", "delete_server", "edit_server", "add_account", "view_server_note",
                    "delete_account", "modify_account");
                
                /**
                 * Checking if atleast one option has been selected or not 
                 */
                
                $can_proceed = FALSE;
                
                foreach ($any_of as $any) {
                    if (isset($_POST[$any])) {
                        $can_proceed = TRUE;
                        break;
                    }
                }
                
                /**
                 * Checking if all options were selected. We cannot allow that, 
                 * because role "Full Admin" is already there. I dont want a duplicate
                 * for that.
                 */
                
                $all_options_selected = TRUE;
                
                foreach ($any_of as $any) {
                    if (!isset($_POST[$any])) {
                        $all_options_selected = FALSE;
                        break;
                    }
                }
                
                $sql = "SELECT * FROM roles WHERE role_name =?";
                $query = $this->db->query($sql, trim($_POST["new_role_name"]));
                
                if ($cur_user != "admin")
                    echo "This user account cannot add roles.";
                else if (strlen(trim($_POST["new_role_name"])) < 4)
                    echo "Invalid role name. Should be 4 chars or more.";
                else if ($query->num_rows() == 1) 
                    echo "A role by this name already exists. Skipping.";
                else if ($can_proceed == FALSE)
                    echo "No options selected. Cannot proceed.";
                else if ($all_options_selected == TRUE)
                    echo "Cannot create a role with all options enabled. A role named <i><u>Full Admin</u></i> 
                        already present for your needs.";
                else {
                    $new_role = array();
                    $new_role_name = trim($_POST["new_role_name"]);
                    
                    unset($_POST["new_role_name"]);
                    
                    foreach ($any_of as $any)
                        $new_role[$any] = FALSE;
                    
                    foreach ($_POST as $key=>$val)
                        $new_role[$key] = ($val == "on") ? TRUE : FALSE;
                    
                    $data = array (
                        "role_name" => $new_role_name,
                        "role_priv" => json_encode($new_role),
                        "role_editable" => "Y"
                    );
                    
                    
                    $this->db->insert("roles", $data);
                    Essentials::insert_log("ACTION", 1, "New WHAM role created - " . $new_role_name);
                    
                    echo "New role created successfully.";
                    
                }
                
            } else {
                $args = array();
                
                $sql = "SELECT * FROM roles";
                $query = $this->db->query($sql);
                if ($query->num_rows() > 0)
                    $args["roles_list"] = $query->result();
                
                
                $this->load->view("settings/roles", $args);
            }
        }
        
        public function addnewuser_post() {
            $cur_user = Mysession::getVar('username');
            
            // $cur_user should be admin to execute this script
            
            if (isset($_POST["new_username"]) && isset($_POST["new_fullname"]) &&
                    isset($_POST["new_pwd"]) && isset($_POST["new_pwd_cnf"]) &&
                    isset($_POST["role_selected"])) 
            {
                
                $sql = "SELECT * FROM users WHERE u_name = ?";
                $sql2 = "SELECT * FROM roles WHERE role_id = ?";
                
                $new_username = trim(strtolower($_POST["new_username"]));
                $new_fullname = trim($_POST["new_fullname"]);
                
                $query = $this->db->query($sql, $new_username);
                $query2 = $this->db->query($sql2, (int)$_POST["role_selected"]);
                
                if ($cur_user != "admin")
                    echo "This user account cannot add new users.";
                else if ($new_username == "admin")
                    echo "Username cannot be admin.";
                else if (strlen($new_username) < 4)
                    echo "Username should not be less than 4 characters";
                else if (strlen($new_fullname) < 4)
                    echo "Full name should not be less than 4 characters";
                else if (strlen($_POST["new_pwd"]) < 6)
                    echo "Password should contain atleast 6 characters";
                else if ($_POST["new_pwd"] != $_POST["new_pwd_cnf"])
                    echo "Confirmation password is incorrect";
                else if ($query->num_rows() > 0)
                    echo "Username already exists. Cannot continue";
                else if ($query2->num_rows() != 1)
                    echo "Invalid role selected";
                else {
                    // all set to create a new user
                    
                    $pass = Essentials::encrypt(md5($_POST["new_pwd"]));
                    
                    $data = array (
                        "u_name" => $new_username,
                        "u_pass" => $pass,
                        "u_fullname" => $new_fullname,
                        "u_roleid" => (int)$_POST["role_selected"]
                    );
                    
                    $this->db->insert("users", $data);
                    Essentials::insert_log("ACTION", 1, "New WHAM user created - " . $new_username);
                    
                    echo "New user created successfully.";
                }
                
            }
        }
        
        public function modifyuser_post() {
            $cur_user = Mysession::getVar('username');
            
            // $cur_user should be admin to execute this script
            
            if (isset($_POST["user_fullname"]) && isset($_POST["role_changed"]) &&
                    isset($_POST["new_pwd"]) && isset($_POST["new_pwd_cnf"]) &&
                    isset($_POST["user_id"])) 
            {
                
                $sql = "SELECT * FROM users WHERE u_id = ?";
                
                $new_fullname = trim($_POST["user_fullname"]);
                
                $query = $this->db->query($sql, (int)$_POST["user_id"]);
                
                if ($cur_user != "admin")
                    echo "This user account cannot edit user info.";
                else if (strlen($new_fullname) < 4)
                    echo "Full name should not be less than 4 characters";
                else if (strlen($_POST["new_pwd"]) > 0 && strlen($_POST["new_pwd"]) < 8)
                    echo "Password should contain atleast 8 characters";
                else if ($_POST["new_pwd"] != $_POST["new_pwd_cnf"])
                    echo "Confirmation password is incorrect";
                else if ($query->num_rows() == 0)
                    echo "User id not found. Cannot continue";
                else {
                    
                    $data = array (
                        "u_fullname" => $new_fullname,
                        "u_roleid" => (int)$_POST["role_changed"]
                    );
                    
                    if (strlen($_POST["new_pwd"]) > 0) {
                        $pass = Essentials::encrypt(md5($_POST["new_pwd"]));
                        $data["u_pass"] = $pass;
                    }
                    
                    $this->db->where("u_id", (int)$_POST["user_id"]);
                    $this->db->update("users", $data);
                    Essentials::insert_log("ACTION", 1, "User details updated successfully for WHAM user id " . $_POST["user_id"]);
                    
                    echo "User details updated successfully.";
                }
                
            }
        }
        
        public function deleteuser_post($u_id=0) {
            $cur_user = Mysession::getVar('username');
            
            // $cur_user should be admin to execute this script
            
            if ($cur_user == "admin" && is_numeric($u_id) && $u_id > 0) 
            {
                $this->db->delete("users", array("u_id" => (int)$u_id));
                Essentials::insert_log("ACTION", 1, "WHAM user " . $u_id . " removed successfully");
                echo "User deleted successfully.";
            } else
                echo "Sorry! Cannot make changes.";
        }
}