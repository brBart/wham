<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_pref extends CI_Controller {
    
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
        if (Mysession::getVar('username') == "admin")
            exit;
        
        $sql = "SELECT * FROM users WHERE u_name =?";
        $query = $this->db->query($sql, Mysession::getVar('username'));
        
        if ($query->num_rows() != 1)
            exit;
        
        $row = $query->row();
        
        $args = array();
        
        $args["timezone"] = $row->s_timezone;
        $args["daylight"] = $row->s_daylight;
        $args["sidebar"] = $row->s_sidebar;
        $args["sidebar_view"] = $row->s_sidebarview;
        
        $this->load->view("settings/user_pref", $args);
        
    } 
    
    public function save() {
        if (Mysession::getVar('username') == "admin")
            exit;
        
        if (!(isset($_POST["timezones"]))) {
            echo "Timezone data missing. Cannot continue";
            exit;
        }
        
        if (!(isset($_POST["daylight"])) || ($_POST["daylight"] != "TRUE" && 
                $_POST["daylight"] != "FALSE")) {
            echo "Daylight settings either missing, or invalid. Cannot continue";
            exit;
        }
        
        if (!(isset($_POST["sidebar"])) || ($_POST["sidebar"] != "LEFT" && 
                $_POST["sidebar"] != "RIGHT")) {
            echo "Invalid details for sidebar. Cannot continue";
            exit;
        }
        
        if (!(isset($_POST["sidebar_view"])) || ($_POST["sidebar_view"] != "compact" && 
                $_POST["sidebar_view"] != "expand")) {
            echo "Invalid details for sidebar view. Cannot continue";
            exit;
        }
        
        $update_arr = array(
            "s_timezone" => $_POST["timezones"],
            "s_daylight" => $_POST["daylight"],
            "s_sidebar" => $_POST["sidebar"],
            "s_sidebarview" => $_POST["sidebar_view"]
        );
        
        $this->db->where("u_name", Mysession::getVar('username'));
        $this->db->update("users", $update_arr);
        
        Mysession::setVar("timezone", $_POST["timezones"]);
        Mysession::setVar("daylight", $_POST["daylight"]);
        Mysession::setVar("sidebar", $_POST["sidebar"]);
        Mysession::setVar("sidebar_view", $_POST["sidebar_view"]);
        
        Essentials::insert_log("ACTION", 1, "Locale settings for user modified successfully");
        
        echo "Settings modified successfully";
    }
}