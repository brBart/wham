<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listdcs extends CI_Controller {
    
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
            $sql = "SELECT datacenter.dc_id AS dc_id, datacenter.dc_name AS dc_name, 
                datacenter.dc_location AS dc_location, count(server.s_dc) AS count 
                FROM datacenter LEFT JOIN server ON (server.s_dc = datacenter.dc_id) 
                GROUP BY datacenter.dc_id ORDER BY datacenter.dc_name";
            $query = $this->db->query($sql);
            
            $args = array();
            if ($query->num_rows() > 0) {
                $args['dc_data'] = $query->result();
            }
            
            $this->load->view('servers/listdcs', $args);
	}
        
        /**
         *
         * view function - to view datacenter info.
         * e.g.: http://localhost:8888/wham/index.php/servers/listdcs/view/1/ 
         */
        
        public function view($dc_id=0)
        {
            if (isset($dc_id) && is_numeric($dc_id) && $dc_id >0) {
                $args = array();
                
                $sql = "SELECT * FROM datacenter WHERE dc_id=?";
                $query = $this->db->query($sql, (int)$dc_id);
                
                if ($query->num_rows() == 1) {
                    $args['dc_data'] = $query->row();

                    $sql2 = "SELECT count(s_id) AS count FROM server WHERE s_dc=?";
                    $query2 = $this->db->query($sql2, (int)$dc_id);

                    $args['no_of_servers'] = 0;
                    if ($query2->num_rows() == 1) {
                        $row = $query2->row();
                        $args['no_of_servers'] = $row->count;
                    }
                    $this->load->view('servers/listdcs_view', $args);
                    
                } else {
                    $redirect_data['active_in_top_menu'] = 'servers';
                    $redirect_data['breadactive'] = 'Servers';
                    $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                    $redirect_data['message'] = "<b>ERROR: </b>No matching data center found.";
                    $this->load->view('redirect', $redirect_data);
                }
            
            } else {
                $redirect_data['active_in_top_menu'] = 'servers';
                $redirect_data['breadactive'] = 'Servers';
                $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                $redirect_data['message'] = "<b>ERROR: </b>Invalid data center.";
                $this->load->view('redirect', $redirect_data);                
            }
        }
        
        /**
         * servers function - to list servers associated with a DC 
         * e.g. : http://localhost:8888/wham/index.php/servers/listdcs/showservers/3/
         */
        
        public function showservers($dc_id=0) {
            if (isset($dc_id) && is_numeric($dc_id) && $dc_id > 0) {
                $args = array();
                
                $sql = "SELECT * FROM datacenter WHERE dc_id=?";
                $query = $this->db->query($sql, (int)$dc_id);
                
                if ($query->num_rows() == 1) {
                    $args['dc_data'] = $query->row();
                    
                    $sql2 = "SELECT count(s_id) AS count FROM server WHERE s_dc=?";
                    $query2 = $this->db->query($sql2, (int)$dc_id);

                    $args['no_of_servers'] = 0;
                    if ($query2->num_rows() == 1) {
                        $row = $query2->row();
                        $args['no_of_servers'] = $row->count;
                    }

                    if ($args['no_of_servers'] > 0) {
                        $sql3 = "SELECT server.s_id, server.s_name , server.s_hostname, 
                            server.s_ip, server.s_rack, server.s_dc, panels.p_name FROM 
                            server LEFT JOIN panels ON (server.s_cp=panels.p_id) 
                            WHERE server.s_dc=?";
                        $query3 = $this->db->query($sql3, (int)$dc_id);
                        $args['server_list'] = $query3->result();
                    }

                    $this->load->view('servers/listdcs_servers', $args);
                } else {
                    $redirect_data['active_in_top_menu'] = 'servers';
                    $redirect_data['breadactive'] = 'Servers';
                    $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                    $redirect_data['message'] = "<b>ERROR: </b>No matching data center found.";
                    $this->load->view('redirect', $redirect_data);
                }
                
            } else {
                $redirect_data['active_in_top_menu'] = 'servers';
                $redirect_data['breadactive'] = 'Servers';
                $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                $redirect_data['message'] = "<b>ERROR: </b>Invalid data center.";
                $this->load->view('redirect', $redirect_data);                
            }
        }
        
        /**
         * edit function - to edit datacenter info.
         * e.g. : http://localhost:8888/wham/index.php/servers/listdcs/edit/1/ 
         */
        
        public function edit($dc_id=0) {
            Essentials::checkWhamUserRole("edit_dc", 1);
            
            if (isset($dc_id) && is_numeric($dc_id) && $dc_id >0) {
                $args = array();
                
                $sql = "SELECT * FROM datacenter WHERE dc_id=?";
                $query = $this->db->query($sql, (int)$dc_id);
                
                if ($query->num_rows() == 1) {
                    $args['dc_data'] = $query->row();

                    $this->load->view('servers/listdcs_savechanges', $args);
                    
                } else {
                    $redirect_data['active_in_top_menu'] = 'servers';
                    $redirect_data['breadactive'] = 'Servers';
                    $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                    $redirect_data['message'] = "<b>ERROR: </b>No matching data center found.";
                    $this->load->view('redirect', $redirect_data);
                }
            
            } else {
                $redirect_data['active_in_top_menu'] = 'servers';
                $redirect_data['breadactive'] = 'Servers';
                $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                $redirect_data['message'] = "<b>ERROR: </b>Invalid data center.";
                $this->load->view('redirect', $redirect_data);                
            }
        }
        
        
        public function savechanges() {
            Essentials::checkWhamUserRole("edit_dc", 1);
            
            if (isset($_POST['dc_id'])) {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('dc_id', 'DC id', 'required|is_natural_no_zero');
                $this->form_validation->set_rules('dc_name_old', 'DC Name current', 'required|min_length[3]|max_length[30]|trim');
                $this->form_validation->set_rules('dc_name', 'DC Name', 'required|min_length[3]|max_length[30]|trim');
                $this->form_validation->set_rules('dc_websiteurl', 'Website URL', 'required|min_length[3]|max_length[60]|trim|prep_url');
                $this->form_validation->set_rules('dc_supporturl', 'Support URL', 'required|min_length[3]|max_length[60]|trim|prep_url');
                $this->form_validation->set_rules('dc_email', 'Email', 'required|min_length[3]|max_length[30]|trim|valid_email');
                $this->form_validation->set_rules('dc_location', 'DC location', 'required|min_length[3]|trim|max_length[20]');
                
                if (TRUE == Essentials::checkWhamUserRole("view_dc_note"))
                    $this->form_validation->set_rules('dc_notes', 'Notes', 'trim|prep_for_form');


                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('servers/listdcs_savechangesreview');
                }
                else {
                    $date = now();
                    $dc_data = array(
                        'dc_name' => $_POST['dc_name'],
                        'dc_websiteurl' => $_POST['dc_websiteurl'],
                        'dc_supporturl' => $_POST['dc_supporturl'],
                        'dc_email' => $_POST['dc_email'],
                        'dc_location' => $_POST['dc_location'],
                        'dc_dateofm' => $date
                    );
                    
                    if (TRUE == Essentials::checkWhamUserRole("view_dc_note"))
                        $dc_data["dc_notes"] = $_POST['dc_notes'];

                    $this->db->where('dc_id', (int)$_POST['dc_id']);
                    $this->db->update('datacenter', $dc_data);
                    
                    $str = "Dc id " . $_POST["dc_id"] . " details updated";
                    Essentials::insert_log("ACTION", 1, $str);

                    $redirect_data['breadactive'] = 'Servers';
                    $redirect_data['active_in_top_menu'] = 'servers';
                    $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/view/' . $_POST['dc_id'] . '/';
                    $redirect_data['message'] = "<b>Data center details updated.</b>";
                    $this->load->view('redirect', $redirect_data);
                }
            } else {
                $redirect_data['breadactive'] = 'Servers';
                $redirect_data['active_in_top_menu'] = 'servers';
                $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                $redirect_data['message'] = "<b>ERROR: </b>Some arguments missing.";
                $this->load->view('redirect', $redirect_data);
            }
        }
        
        public function delete($dc_id=0) {
            Essentials::checkWhamUserRole("delete_dc", 1);
            
            // checking if dc_id provided is valid or not
            $valid_dc = FALSE;
            if (isset($dc_id) && is_numeric($dc_id) && $dc_id > 0) {
                $sql = "SELECT dc_id FROM datacenter WHERE dc_id=?";
                $query = $this->db->query($sql, (int)$dc_id);
                
                if ($query->num_rows() == 1)
                    $valid_dc = TRUE;
            }
            
            if ($valid_dc == TRUE) {
                $sql = "SELECT s_id FROM server WHERE s_dc=?";
                $query = $this->db->query($sql, (int)$dc_id);
                
                if ($query->num_rows() > 0) {
                    $redirect_data['breadactive'] = 'Servers';
                    $redirect_data['active_in_top_menu'] = 'servers';
                    $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                    $redirect_data['message'] = "<b>ERROR: Cannot delete this data center.</b>";
                    $this->load->view('redirect', $redirect_data);
                } else {
                    $this->db->where('dc_id', (int)$dc_id);
                    $this->db->delete('datacenter');
                    Essentials::insert_log("ACTION", 1, "Data center dc id: " . $dc_id . " deleted");
                    
                    $redirect_data['breadactive'] = 'Servers';
                    $redirect_data['active_in_top_menu'] = 'servers';
                    $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                    $redirect_data['message'] = "<b>Data center deleted successfully.</b>";
                    $this->load->view('redirect', $redirect_data);
                }
            } else {
                $redirect_data['breadactive'] = 'Servers';
                $redirect_data['active_in_top_menu'] = 'servers';
                $redirect_data['redirect_url'] = site_url() . '/servers/listdcs/';
                $redirect_data['message'] = "<b>ERROR: Some arguments missing.</b>";
                $this->load->view('redirect', $redirect_data);
            }
        }
        
        
}
