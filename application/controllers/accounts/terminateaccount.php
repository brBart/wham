<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terminateaccount extends CI_Controller {
    
	public function __construct() {
            parent::__construct();
            Essentials::checkIfIpIsAllowed();
            if (Mysession::getVar('username') == NULL) {
                header('Location: ' . base_url());
                exit;
            }
            Essentials::checkWhamUserRole("delete_account", 1);
        }
        
        public function index()
	{ 
            $args = array();
            $sql = 'SELECT * , panels.p_name AS control_panel FROM cpanelaccts
                    LEFT JOIN server ON ( server.s_id = cpanelaccts.server_id ) 
                    LEFT JOIN panels ON ( panels.p_id = server.s_cp ) 
                    WHERE server.s_isactive="Y"';
            
            $query = $this->db->query($sql);
            
            if ($query->num_rows() > 0 && $query->num_rows() < 2000) {
                
                $args["accounts_list"] = $query->result();
                $this->load->view('accounts/terminateaccount', $args); 
            } 
            else if ($query->num_rows() > 2000) {
                header("Location: " . site_url() . "/accounts/terminateaccount/page/");
            }
            
            else {
                $redirect_data['breadactive'] = 'Accounts';
                $redirect_data['active_in_top_menu'] = 'accounts';
                $redirect_data['redirect_url'] = site_url();
                $redirect_data['message'] = "<b>ERROR: </b> No results to display.";
                $this->load->view('redirect', $redirect_data);
            }
	}
        
        
        public function page() {
            
            $args = array();
            
            $this->load->library("pagination");
            
            
            
            $sql = 'SELECT * , panels.p_name AS control_panel FROM cpanelaccts
                    LEFT JOIN server ON ( server.s_id = cpanelaccts.server_id ) 
                    LEFT JOIN panels ON ( panels.p_id = server.s_cp ) 
                    WHERE server.s_isactive="Y"';
            
            
            $config["base_url"] = site_url() . "/accounts/terminateaccount/page/";
            $config["total_rows"] = $this->db->query($sql)->num_rows();
            $config["per_page"] = 100;
            $config['uri_segment'] = 4;
            $config["num_links"] = 15;
            
            
            $config['full_tag_open'] = '<div class="pagination"><ul>';
            $config['full_tag_close'] = '</ul></div><!--pagination-->';

            $config['first_link'] = '&laquo; First';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';

            $config['last_link'] = 'Last &raquo;';
            $config['last_tag_open'] = '<li class="next page">';
            $config['last_tag_close'] = '</li>';

            $config['next_link'] = 'Next &rarr;';
            $config['next_tag_open'] = '<li class="next page">';
            $config['next_tag_close'] = '</li>';

            $config['prev_link'] = '&larr; Previous';
            $config['prev_tag_open'] = '<li class="prev page">';
            $config['prev_tag_close'] = '</li>';

            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';

            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';
            
            $this->pagination->initialize($config);
            
            
            $sql2 = 'SELECT * , panels.p_name AS control_panel FROM cpanelaccts
                    LEFT JOIN server ON ( server.s_id = cpanelaccts.server_id ) 
                    LEFT JOIN panels ON ( panels.p_id = server.s_cp ) 
                    WHERE server.s_isactive="Y" ORDER BY cpanelaccts.user LIMIT ?, ?';
            
            $query2 = $this->db->query($sql2, array((int)$this->uri->segment(4), $config["per_page"]));
            $args["accounts_list"] = $query2->result();
            
            $this->load->view("accounts/terminateaccount_page", $args);
            
        }
}
