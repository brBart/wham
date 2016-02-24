<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listduplicates extends CI_Controller {
    
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
            
            
            /* 
             SELECT * , panels.p_name AS control_panel , COUNT(cpanelaccts.domain) AS count FROM cpanelaccts
                    LEFT JOIN server ON ( server.s_id = cpanelaccts.server_id ) 
                    LEFT JOIN panels ON ( panels.p_id = server.s_cp )  
                    WHERE server.s_isactive="Y" GROUP BY cpanelaccts.domain HAVING count >1 
             */
            
            $sql= 'SELECT user , COUNT( cpanelaccts.user ) AS count
                    FROM  cpanelaccts GROUP BY user HAVING count >1';
            
            $query = $this->db->query($sql);
            
            $result_set_users = array();
            
            if ($query->num_rows() > 0) {
                foreach($query->result() as $row) {
                    array_push($result_set_users, $row->user);
                }
            }
            
            $sql2= 'SELECT domain , COUNT( cpanelaccts.domain ) AS count
                    FROM  cpanelaccts GROUP BY domain HAVING count >1';
            
            $query2 = $this->db->query($sql2);
            
            $result_set_domains = array();
            
            if ($query2->num_rows() > 0) {
                foreach($query2->result() as $row) {
                    array_push($result_set_domains, $row->domain);
                }
            }
            
            $final_result_set_users = array();
            $final_result_set_domains = array();
            
            if (count($result_set_users) > 0) {
                foreach($result_set_users as $user) {
                    $sql_qr = 'SELECT * , panels.p_name AS control_panel FROM cpanelaccts
                    LEFT JOIN server ON ( server.s_id = cpanelaccts.server_id ) 
                    LEFT JOIN panels ON ( panels.p_id = server.s_cp ) 
                    WHERE cpanelaccts.user = ? AND server.s_isactive="Y"';
                    $q_1 = $this->db->query($sql_qr, $user);
                    
                    foreach($q_1->result() as $roww)
                        array_push($final_result_set_users, $roww);
                }
            }
            
            if (count($result_set_domains) > 0) {
                foreach($result_set_domains as $domain) {
                    $sql_qr = 'SELECT * , panels.p_name AS control_panel FROM cpanelaccts
                    LEFT JOIN server ON ( server.s_id = cpanelaccts.server_id ) 
                    LEFT JOIN panels ON ( panels.p_id = server.s_cp ) 
                    WHERE cpanelaccts.domain = ? AND server.s_isactive="Y"';
                    $q_1 = $this->db->query($sql_qr, $domain);
                    
                    foreach($q_1->result() as $roww)
                        array_push($final_result_set_domains, $roww);
                }
            }
            
            if (count($final_result_set_users) > 0 || count($final_result_set_domains) > 0) {
                $args["final_result_set_users"] = $final_result_set_users;
                $args["final_result_set_domains"] = $final_result_set_domains;
                
                $this->load->view('accounts/listduplicates', $args);
            } else {
                $redirect_data['breadactive'] = 'Accounts';
                $redirect_data['active_in_top_menu'] = 'accounts';
                $redirect_data['redirect_url'] = site_url();
                $redirect_data['message'] = "<b>ERROR: </b> No duplicate accounts found.";
                $this->load->view('redirect', $redirect_data);
            }
	}        
}
