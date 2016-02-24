<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addserver extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        Essentials::checkIfIpIsAllowed();
        if (Mysession::getVar('username') == NULL) {
            header('Location: ' . base_url());
            exit;
        }
        Essentials::checkWhamUserRole("add_server", 1);
    }
    
    public function index($add_to_dc=0) {
        $args = array();
        $sql = "SELECT dc_id, dc_name, dc_location FROM datacenter";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $dcs = array();
            foreach ($query->result() as $row) {
                $dcs[(string)$row->dc_id] = "$row->dc_name, $row->dc_location";
            }
            $args['dcs'] = $dcs;
        }
        
        $sql2 = "SELECT * FROM panels";
        $query2 = $this->db->query($sql2);
        if ($query2->num_rows() > 0) {
            $panels = array();
            foreach($query2->result() as $row) {
                $panels[(string)$row->p_id] = $row->p_name;
            }
            $args['panels'] = $panels;
        }
        $args["can_add_new"] = TRUE;
        
        if (is_numeric($add_to_dc) && $add_to_dc > 0)
            $args['add_to_dc'] = $add_to_dc;
        
        $this->load->library('form_validation');
        
        if (isset($_POST["s_name"])) {
            $this->form_validation->set_rules('s_name', 'Server name', 'required|min_length[3]|max_length[40]|trim');
            $this->form_validation->set_rules('s_hostname', 'Hostname', 'required|min_length[3]|max_length[40]|trim');
            $this->form_validation->set_rules('s_cp', 'Control panel', 'callback_check_valid_cp');
            $this->form_validation->set_rules('s_dc', 'Data center', 'callback_check_valid_dc');
            $this->form_validation->set_rules('s_rack', 'Rack location', 'trim|max_length[20]');
            $this->form_validation->set_rules('s_ip', 'IP Address', 'trim|callback_check_valid_ip');
            
            if (TRUE == Essentials::checkWhamUserRole("view_server_note"))
                $this->form_validation->set_rules('s_notes', 'Notes', 'trim|prep_for_form');
            
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('servers/addserver', $args);
            } else {
                $sql3 = "SELECT * FROM server WHERE s_ip=?";
                $query3 = $this->db->query($sql3, $_POST['s_ip']);
                
                if ($query3->num_rows() == 0) {
                    // no duplicate server with same ip addr, so can insert into db
                    $date = now();
                    $s_data = array(
                        's_name' => $_POST['s_name'],
                        's_hostname' => $_POST['s_hostname'],
                        's_cp' => (int)$_POST['s_cp'],
                        's_dc' => (int)$_POST['s_dc'],
                        's_ip' => $_POST['s_ip'],
                        's_rack' => $_POST['s_rack'],
                        's_dateofc' => $date,
                        's_dateofm' => $date
                    );
                    
                    if (TRUE == Essentials::checkWhamUserRole("view_server_note"))
                        $s_data["s_notes"] = Essentials::encrypt($_POST['s_notes']);
                    
                    $this->db->set($s_data);
                    $this->db->insert('server');
                    
                    $server_id = $this->db->insert_id();
                    
                    $this->db->set(array(
                        "server_id" => $server_id,
                        "last_sync" => 0
                    ));
                    
                    if ($_POST['s_cp'] == 1) 
                        $this->db->insert('cpanel');
                    
                    Essentials::insert_log("ACTION", 1, "New server created - " . $_POST['s_name'] . 
                            " - " . $_POST['s_hostname'] . " - " . $_POST['s_ip'] . ", dc id = ". $_POST['s_dc']);
                    
                    header('Location: ' . site_url() . "/servers/viewserver/info/" . $server_id . "/");
                } else {
                    // duplicate server exists.
                    $redirect_data['breadactive'] = 'Servers';
                    $redirect_data['active_in_top_menu'] = 'servers';
                    $redirect_data['redirect_url'] = site_url() . '/servers/listservers/';
                    $redirect_data['message'] = "<b>ERROR: </b>Cannot add this server because another server with ip address " . 
                            $_POST['s_ip'] . " already exists.";
                    $this->load->view('redirect', $redirect_data);
                }
            }
        } else {
        
            $this->load->view('servers/addserver', $args);
        }
    }
    
    public function check_valid_ip($ip) {
        $allow = TRUE;
        $sql = "SELECT w_val FROM settings WHERE w_option='allow_priv_reserve_ips'";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 1) {
            $row = $query->row();
            if ($row->w_val == "FALSE")
                $allow = FALSE;
        }
        if ($allow == TRUE) {
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                $this->form_validation->set_message('check_valid_ip', "Please enter a valid %s.");
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $this->form_validation->set_message('check_valid_ip', "Please enter a valid %s. Private/reserved IPs not allowed.");
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }
    
    public function check_valid_cp($option) {
        $panels = array();
        $sql = "SELECT p_id FROM panels";
        $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                array_push($panels, $row->p_id);
        }
        
        if (in_array($option, $panels)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_valid_cp', "Please select a valid %s from the list");
            return FALSE;
        }
    }
    
    public function check_valid_dc($option) {
        $dcs = array();
        $sql = "SELECT dc_id FROM datacenter";
        $query = $this->db->query($sql);
        
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row)
                array_push($dcs, $row->dc_id);
        }
        
        if (in_array($option, $dcs)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_valid_dc', "Please select a valid %s from the list");
            return FALSE;
        }
    }
}
