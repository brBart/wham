<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adddc extends CI_Controller {
    
        public function __construct() {
            parent::__construct();
            Essentials::checkIfIpIsAllowed();
            if (Mysession::getVar('username') == NULL) {
                header('Location: ' . base_url());
                exit;
            }
            Essentials::checkWhamUserRole("add_dc", 1);
        }
    
	public function index()
	{
            
            $this->load->library('form_validation');
            $this->load->view('servers/adddc');
	}
        
        public function newdc()
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('dc_name', 'DC Name', 'required|min_length[3]|max_length[30]|trim');
            $this->form_validation->set_rules('dc_websiteurl', 'Website URL', 'required|min_length[3]|max_length[60]|trim|prep_url');
            $this->form_validation->set_rules('dc_supporturl', 'Support URL', 'required|min_length[3]|max_length[60]|trim|prep_url');
            $this->form_validation->set_rules('dc_email', 'Email', 'required|min_length[3]|max_length[30]|trim|valid_email');
            $this->form_validation->set_rules('dc_location', 'DC location', 'required|min_length[3]|trim|max_length[20]');
            
            if (TRUE == Essentials::checkWhamUserRole("view_dc_note"))
                $this->form_validation->set_rules('dc_notes', 'Notes', 'trim|prep_for_form');
            
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('servers/adddc');
            }
            else {
                $date = now();
                $dc_data = array(
                    'dc_name' => $_POST['dc_name'],
                    'dc_websiteurl' => $_POST['dc_websiteurl'],
                    'dc_supporturl' => $_POST['dc_supporturl'],
                    'dc_email' => $_POST['dc_email'],
                    'dc_location' => $_POST['dc_location'],
                    'dc_dateofc' => $date,
                    'dc_dateofm' => $date
                );
                
                if (TRUE == Essentials::checkWhamUserRole("view_dc_note"))
                    $dc_data['dc_notes'] = $_POST['dc_notes'];
                
                $this->db->set($dc_data);
                $this->db->insert('datacenter');
                
                $ins_id = $this->db->insert_id();
                
                Essentials::insert_log("ACTION", 1, "New datacenter created - " . $_POST['dc_name'] . ", dc id: " . $ins_id);
                
                $redirect_data['breadactive'] = 'Servers';
                $redirect_data['active_in_top_menu'] = 'servers';
                $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                $redirect_data['message'] = "<b>New data center added.</b>";
                $this->load->view('redirect', $redirect_data);
            }
        }
        
        
}
