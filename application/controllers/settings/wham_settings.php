<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wham_settings extends CI_Controller {
    
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

        $sql = "SELECT * FROM settings WHERE w_option = ?";

        // check if servers with priv ips is allowed
        $query1 = $this->db->query($sql, "allow_priv_reserve_ips");
        if ($query1->num_rows() == 1) {
            $row = $query1->row();
            $args["allow_priv_reserve_ips"] = $row->w_val;
        } else {
            $this->db->insert("settings", array("w_option" => "allow_priv_reserve_ips", "w_val" => "FALSE"));
            $args["allow_priv_reserve_ips"] = "FALSE";
        }

        // check if load avg can be displayed
        $query2 = $this->db->query($sql, "show_load_avg");
        if ($query2->num_rows() == 1) {
            $row = $query2->row();
            $args["show_load_avg"] = $row->w_val;
        } else {
            $this->db->insert("settings", array("w_option" => "show_load_avg", "w_val" => "FALSE"));
            $args["show_load_avg"] = "FALSE";
        }

        // check options about logging and related info
        $query3 = $this->db->query($sql, "logging");
        if ($query3->num_rows() == 1) {
            $row = $query3->row();
            $tmp_obj = json_decode($row->w_val);
            $args["logging"] = ($tmp_obj->logging == TRUE) ? "TRUE":"FALSE";
            $args["log_all_login_attempts"] = ($tmp_obj->log_all_login_attempts == TRUE) ? "TRUE":"FALSE";
            $args["log_actions"] = ($tmp_obj->log_actions == TRUE) ? "TRUE":"FALSE";
        } else {
            $this->db->insert("settings", array("w_option" => "logging", "w_val" => json_encode(array("logging" => FALSE, "log_all_login_attempts" => FALSE, "log_actions" => FALSE))));
            $args["logging"] = "FALSE";
            $args["log_all_login_attempts"] = "FALSE";
            $args["log_actions"] = "FALSE";
        }

        // check status of WHAM! firewall
        $query4 = $this->db->query($sql, "firewall");
        if ($query4->num_rows() == 1) {
            $row = $query4->row();
            $args["firewall"] = $row->w_val;
        } else {
            $this->db->insert("settings", array("w_option" => "firewall", "w_val" => "FALSE"));
            $args["firewall"] = "FALSE";
        }

        // check if email alerts are enabled
        $query5 = $this->db->query($sql, "email_alerts");
        if ($query5->num_rows() == 1) {
            $row = $query5->row();
            $args["email_alerts"] = $row->w_val;
        } else {
            $this->db->insert("settings", array("w_option" => "email_alerts", "w_val" => "FALSE"));
            $args["email_alerts"] = "FALSE";
        }

        // get email to: and cc:
        $query6 = $this->db->query($sql, "send_email_to");
        if ($query6->num_rows() == 1) {
            $row = $query6->row();
            $args["send_email_to"] = $row->w_val;
        } else {
            $this->db->insert("settings", array("w_option" => "send_email_to", "w_val" => ""));
            $args["send_email_to"] = "";
        }

        $query7 = $this->db->query($sql, "send_email_cc");
        if ($query7->num_rows() == 1) {
            $row = $query7->row();
            $args["send_email_cc"] = $row->w_val;
        } else {
            $this->db->insert("settings", array("w_option" => "send_email_cc", "w_val" => ""));
            $args["send_email_cc"] = "";
        }

        // get email notification settings
        $query8 = $this->db->query($sql, "notify_settings");
        if ($query8->num_rows() == 1) {
            $row = $query8->row();
            $tmp_obj = json_decode($row->w_val);
            $args["notify"] = $tmp_obj->notify;
            $args["notify_whitelist_from_url"] = ($tmp_obj->notify_whitelist_from_url == TRUE) ? "TRUE":"FALSE";
        } else {
            $this->db->insert("settings", array("w_option" => "notify_settings", "w_val" => json_encode(array("notify" => "failonly", "notify_whitelist_from_url" => FALSE))));
            $args["notify"] = "failonly";
            $args["notify_whitelist_from_url"] = "FALSE";
        }

        // get timezone and daylight
        $query9 = $this->db->query($sql, "timezone");
        if ($query9->num_rows() == 1) {
            $row = $query9->row();
            $args["timezone"] = $row->w_val;
        } else {
            $this->db->insert("settings", array("w_option" => "timezone", "w_val" => "UP55"));
            $args["timezone"] = "UP55";
        }

        $query10 = $this->db->query($sql, "daylight");
        if ($query10->num_rows() == 1) {
            $row = $query10->row();
            $args["daylight"] = $row->w_val;
        } else {
            $this->db->insert("settings", array("w_option" => "daylight", "w_val" => "FALSE"));
            $args["daylight"] = "FALSE";
        }
        
        // get sidebar options
        $query11 = $this->db->query($sql, "sidebar");
        if ($query11->num_rows() == 1) {
            $row = $query11->row();
            $args["sidebar"] = $row->w_val;
        } else {
            $this->db->insert("settings", array("w_option" => "sidebar", "w_val" => "LEFT"));
            $args["sidebar"] = "LEFT";
        }

        $query12 = $this->db->query($sql, "sidebar_view");
        if ($query12->num_rows() == 1) {
            $row = $query12->row();
            $args["sidebar_view"] = $row->w_val;
        } else {
            $this->db->insert("settings", array("w_option" => "sidebar_view", "w_val" => "compact"));
            $args["sidebar_view"] = "compact";
        }

        $this->load->view("settings/wham_settings", $args);
    }

    public function save() {
        
        if (isset($_POST["email_notify"]) && $_POST["email_notify"] == "TRUE" && isset($_POST["email_address"]) && isset($_POST["cc_email_address"])) {
            if (! filter_var($_POST["email_address"], FILTER_VALIDATE_EMAIL)) {
                echo "Invalid email address specified. Cannot continue";
                exit;
            }
            
            if (strlen(trim($_POST["cc_email_address"])) > 0) {
                if (! filter_var($_POST["cc_email_address"], FILTER_VALIDATE_EMAIL)) {
                    echo "Invalid cc: email address specified. Cannot continue";
                    exit;
                }
            }   
        }
        
        $check = array("TRUE", "FALSE");
        
        $req = array("allow_priv_reserve_ips", "show_load_avg", "logging", "restrict_access",
            "email_notify", "email_address", "cc_email_address", "email_login_attempts", 
            "timezones", "daylight", "sidebar", "sidebar_view");
        
        foreach ($req as $c) {
            if (!(isset($_POST[$c]))) {
                echo "$c arguments missing.. Cannot continue.";
                exit;
            }
        }
        
        if (! in_array($_POST["allow_priv_reserve_ips"], $check)) {
            echo "Invalid value for allow_priv_reserve_ips";
            exit;
        }
        if (! in_array($_POST["show_load_avg"], $check)) {
            echo "Invalid value for show_load_avg";
            exit;
        }
        if (! in_array($_POST["logging"], $check)) {
            echo "Invalid value for logging";
            exit;
        }
        if (! in_array($_POST["restrict_access"], $check)) {
            echo "Invalid value for restrict_access";
            exit;
        }
        if (! in_array($_POST["email_notify"], $check)) {
            echo "Invalid value for email_notify";
            exit;
        }
        if (! in_array($_POST["daylight"], $check)) {
            echo "Invalid value for daylight";
            exit;
        }
        if (! in_array($_POST["email_login_attempts"], array("failonly", "all"))) {
            echo "Invalid value for email_login_attempts";
            exit;
        }
        if (! in_array($_POST["sidebar"], array("LEFT", "RIGHT"))) {
            echo "Invalid value for sidebar";
            exit;
        }
        if (! in_array($_POST["sidebar_view"], array("compact", "expand"))) {
            echo "Invalid value for sidebar_view";
            exit;
        }
        
        $current = array();
        $ret_str = "Changes have been saved.";
        
        $sql = "SELECT * FROM settings WHERE w_option = ?";

        // check if servers with priv ips is allowed
        $query1 = $this->db->query($sql, "allow_priv_reserve_ips");
        if ($query1->num_rows() == 1) {
            $row = $query1->row();
            $current["allow_priv_reserve_ips"] = $row->w_val;
        }
        
        if ($_POST["allow_priv_reserve_ips"] != $current["allow_priv_reserve_ips"]) {
            $this->db->where("w_option", "allow_priv_reserve_ips");
            $this->db->update("settings", array("w_val" => $_POST["allow_priv_reserve_ips"]));
            $ret_str .= "<br/>allow_priv_reserve_ips = " . $_POST["allow_priv_reserve_ips"];
        }
        
        // check if load avg can be displayed
        $query2 = $this->db->query($sql, "show_load_avg");
        if ($query2->num_rows() == 1) {
            $row = $query2->row();
            $current["show_load_avg"] = $row->w_val;
        }
        
        if ($_POST["show_load_avg"] != $current["show_load_avg"]) {
            $this->db->where("w_option", "show_load_avg");
            $this->db->update("settings", array("w_val" => $_POST["show_load_avg"]));
            Mysession::setVar("show_load_avg", $_POST["show_load_avg"]);
            $ret_str .= "<br/>show_load_avg = " . $_POST["show_load_avg"];
        }
        
        // update options about logging and related info
        $tmp_arr = array(
            "logging" => ($_POST["logging"] == "TRUE") ? TRUE : FALSE,
            "log_all_login_attempts" => (isset($_POST["log_login_attempts"]) && $_POST["log_login_attempts"] == TRUE) ? TRUE : FALSE,
            "log_actions" => (isset($_POST["log_server_actions"]) && $_POST["log_server_actions"] == TRUE) ? TRUE : FALSE
        );
        $this->db->where("w_option", "logging");
        $this->db->update("settings", array("w_val" => json_encode($tmp_arr)));
        $ret_str .= "<br/>logging = " . $_POST["logging"];
        $ret_str .= "<br/>log_all_login_attempts = ";
        $ret_str .= ($tmp_arr["log_all_login_attempts"] == TRUE)? "on":"off";
        $ret_str .= "<br/>log_actions = ";
        $ret_str .= ($tmp_arr["log_actions"] == TRUE)? "on":"off";

        // check status of WHAM! firewall
        $query4 = $this->db->query($sql, "firewall");
        if ($query4->num_rows() == 1) {
            $row = $query4->row();
            $current["firewall"] = $row->w_val;
        }
        
        if ($_POST["restrict_access"] != $current["firewall"]) {
            $this->db->where("w_option", "firewall");
            $this->db->update("settings", array("w_val" => $_POST["restrict_access"]));
            $this->db->where("w_option", "firewall_mode");
            $this->db->update("settings", array("w_val" => "none"));
            $ret_str .= "<br/>firewall = " . $_POST["restrict_access"];
        }

        // check if email alerts are enabled
        $query5 = $this->db->query($sql, "email_alerts");
        if ($query5->num_rows() == 1) {
            $row = $query5->row();
            $current["email_alerts"] = $row->w_val;
        }
        
        if ($_POST["email_notify"] != $current["email_alerts"]) {
            $this->db->where("w_option", "email_alerts");
            $this->db->update("settings", array("w_val" => $_POST["email_notify"]));
            $ret_str .= "<br/>email_alerts = " . $_POST["email_notify"];
        }

        // get email to: and cc:
        $query6 = $this->db->query($sql, "send_email_to");
        if ($query6->num_rows() == 1) {
            $row = $query6->row();
            $current["send_email_to"] = $row->w_val;
        }
        
        if ($_POST["email_address"] != $current["send_email_to"]) {
            $this->db->where("w_option", "send_email_to");
            $this->db->update("settings", array("w_val" => $_POST["email_address"]));
            $ret_str .= "<br/>send_email_to = " . $_POST["email_address"];
        }

        $query7 = $this->db->query($sql, "send_email_cc");
        if ($query7->num_rows() == 1) {
            $row = $query7->row();
            $current["send_email_cc"] = $row->w_val;
        }
        
        if ($_POST["cc_email_address"] != $current["send_email_cc"]) {
            $this->db->where("w_option", "send_email_cc");
            $this->db->update("settings", array("w_val" => $_POST["cc_email_address"]));
            $ret_str .= "<br/>send_email_cc = " . $_POST["cc_email_address"];
        }

        // set email notification settings
        $tmp_arr = array(
            "notify" => $_POST["email_login_attempts"] ,
            "notify_whitelist_from_url" => (isset($_POST["email_ip_whitelist_frm_url"]) && $_POST["email_ip_whitelist_frm_url"] == TRUE) ? TRUE : FALSE
        );
        $this->db->where("w_option", "notify_settings");
        $this->db->update("settings", array("w_val" => json_encode($tmp_arr)));
        $ret_str .= "<br/>notify = " . $tmp_arr["notify"];
        $ret_str .= "<br/>notify_whitelist_from_url = ";
        $ret_str .= ($tmp_arr["notify_whitelist_from_url"] == TRUE)? "on":"off";
        
        // get timezone and daylight
        $query9 = $this->db->query($sql, "timezone");
        if ($query9->num_rows() == 1) {
            $row = $query9->row();
            $current["timezone"] = $row->w_val;
        }
        
        if ($_POST["timezones"] != $current["timezone"]) {
            $this->db->where("w_option", "timezone");
            $this->db->update("settings", array("w_val" => $_POST["timezones"]));
            $ret_str .= "<br/>timezone = " . $_POST["timezones"];
        }

        $query10 = $this->db->query($sql, "daylight");
        if ($query10->num_rows() == 1) {
            $row = $query10->row();
            $current["daylight"] = $row->w_val;
        }
        
        if ($_POST["daylight"] != $current["daylight"]) {
            $this->db->where("w_option", "daylight");
            $this->db->update("settings", array("w_val" => $_POST["daylight"]));
            $ret_str .= "<br/>daylight = " . $_POST["daylight"];
        }
        
        Mysession::setVar("timezone", $_POST["timezones"]);
        Mysession::setVar("daylight", $_POST["daylight"]);
        
        // get sidebar options
        $query11 = $this->db->query($sql, "sidebar");
        if ($query11->num_rows() == 1) {
            $row = $query11->row();
            $current["sidebar"] = $row->w_val;
        }
        
        if ($_POST["sidebar"] != $current["sidebar"]) {
            $this->db->where("w_option", "sidebar");
            $this->db->update("settings", array("w_val" => $_POST["sidebar"]));
            $ret_str .= "<br/>sidebar = " . $_POST["sidebar"];
            
            Mysession::setVar("sidebar", $_POST["sidebar"]);
        }

        $query12 = $this->db->query($sql, "sidebar_view");
        if ($query12->num_rows() == 1) {
            $row = $query12->row();
            $current["sidebar_view"] = $row->w_val;
        }
        
        if ($_POST["sidebar_view"] != $current["sidebar_view"]) {
            $this->db->where("w_option", "sidebar_view");
            $this->db->update("settings", array("w_val" => $_POST["sidebar_view"]));
            $ret_str .= "<br/>sidebar_view = " . $_POST["sidebar_view"];
            
            Mysession::setVar("sidebar_view", $_POST["sidebar_view"]);
        }
        
        Essentials::insert_log("ACTION", 1, $ret_str);
        echo $ret_str;
        
    }
}