<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_log extends CI_Controller {
    
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
            $auth_sql = "SELECT logs.log_id, logs.log_type, logs.log_status, logs.log_ip, 
                logs.log_msg, logs.log_user, logs.log_time FROM logs WHERE 
                logs.log_type=? ORDER BY logs.log_id DESC LIMIT 1000";
            
            $auth_query = $this->db->query($auth_sql, "AUTH");
            if ($auth_query->num_rows() > 0)
                $args["auth_result"] = $auth_query->result();
            
            $action_sql = "SELECT logs.log_id, logs.log_type, logs.log_status, logs.log_ip, 
                logs.log_msg, logs.log_server, logs.log_user, logs.log_time, server.s_name 
                FROM logs LEFT JOIN server ON (logs.log_server = server.s_id) WHERE 
                logs.log_type=? ORDER BY logs.log_id DESC LIMIT 1000";
            
            $action_query = $this->db->query($action_sql, "ACTION");
            if ($action_query->num_rows() > 0)
                $args["action_result"] = $action_query->result();
            
            $sync_sql = "SELECT logs.log_id, logs.log_type, logs.log_status, logs.log_ip, 
                logs.log_msg, logs.log_server, logs.log_user, logs.log_time, server.s_name 
                FROM logs LEFT JOIN server ON (logs.log_server = server.s_id) WHERE 
                logs.log_type=? ORDER BY logs.log_id DESC LIMIT 1000";
            
            $sync_query = $this->db->query($sync_sql, "SYNC");
            if ($sync_query->num_rows() > 0)
                $args["sync_result"] = $sync_query->result();
            
            $this->load->view("settings/event_log", $args);
	}        
}