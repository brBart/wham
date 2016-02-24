<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listsuspended extends CI_Controller {
    
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
            $args = array();
            
            $sql = 'SELECT * , panels.p_name AS control_panel FROM cpanelaccts
                    LEFT JOIN server ON ( server.s_id = cpanelaccts.server_id ) 
                    LEFT JOIN panels ON ( panels.p_id = server.s_cp ) 
                    WHERE cpanelaccts.suspended = 1 AND server.s_isactive="Y"';
            
            $query = $this->db->query($sql);
            
            if ($query->num_rows() > 0) {
                
                $args["accounts_list"] = $query->result();
                $this->load->view('accounts/listsuspended', $args); 
            } else {
                $redirect_data['breadactive'] = 'Accounts';
                $redirect_data['active_in_top_menu'] = 'accounts';
                $redirect_data['redirect_url'] = site_url();
                $redirect_data['message'] = "<b>ERROR: </b> No suspended accounts found.";
                $this->load->view('redirect', $redirect_data);
            }
	}        
}
