<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listservers extends CI_Controller {
    
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
            $sql = "SELECT server.s_id, server.s_name, server.s_ip, server.s_rack, 
                server.s_hostname, server.s_isactive, datacenter.dc_name, datacenter.dc_id, 
                datacenter.dc_location, panels.p_name FROM server 
                LEFT JOIN datacenter ON (server.s_dc=datacenter.dc_id) 
                LEFT JOIN panels ON (server.s_cp=panels.p_id) ORDER BY server.s_name";
            $query = $this->db->query($sql);
            
            if ($query->num_rows() > 0)
                $args['server_list'] = $query->result();
            
            $this->load->view('servers/listservers', $args);
	}
        
        
}

