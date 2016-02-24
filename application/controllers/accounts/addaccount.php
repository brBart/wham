<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addaccount extends CI_Controller {
    
	public function __construct() {
            parent::__construct();
            Essentials::checkIfIpIsAllowed();
            if (Mysession::getVar('username') == NULL) {
                header('Location: ' . base_url());
                exit;
            }
            Essentials::checkWhamUserRole("add_account", 1);
        }
        
        public function index()
	{ 
            $args = array();
            $sql = "SELECT s_id, s_hostname FROM server WHERE s_isactive='Y'";
            $query = $this->db->query($sql);
            
            if ($query->num_rows() > 0) {
                
                $args["server_list"] = $query->result();
                $this->load->view('accounts/addaccount', $args); 
            } else {
                $redirect_data['breadactive'] = 'Accounts';
                $redirect_data['active_in_top_menu'] = 'accounts';
                $redirect_data['redirect_url'] = site_url();
                $redirect_data['message'] = "<b>ERROR: </b> No servers exist in db.";
                $this->load->view('redirect', $redirect_data);
            }
	}        
}
