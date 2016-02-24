<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
            parent::__construct();
            Essentials::checkIfIpIsAllowed();
        }
        
        public function index()
	{
            if (Mysession::getVar('username') !== NULL) {
                header('Location: ' . site_url() . "/welcome/home/");
                exit;
            }
            
            $args = array();
            
            if (isset($_POST['username']) && isset($_POST['pass'])) {
                $username = $_POST['username'];
                $pass = Essentials::encrypt(md5($_POST['pass']));
                
                if ($username == 'admin') {
                    $sql = "SELECT * FROM settings WHERE w_option=? AND w_val=?";
                    $query = $this->db->query($sql, array('adminpassword', $pass));
                } else {
                    $sql = "SELECT * FROM users WHERE u_name=? AND u_pass=?";
                    $query = $this->db->query($sql, array($username, $pass));
                }
                
                $args = array();
                
                if ($query->num_rows() == 1) {
                    
                    // add to log
                    Essentials::insert_log("AUTH", 1, "Successful login", $username);
                    Essentials::notifyLogin("success");
                    
                    $sql2 = "SELECT * FROM settings";
                    $query2 = $this->db->query($sql2);
                    
                    $allowed_vars = array ("timezone", "daylight", "sidebar", "sidebar_view", 
                            "allow_priv_reserve_ips", "show_load_avg");
                    
                    // first we load the defaults ..
                    foreach ($query2->result() as $row) {
                        if (in_array($row->w_option, $allowed_vars, true))
                            Mysession::setVar($row->w_option, $row->w_val);
                    }
                    
                    // ... and then over-ride default values with user's locale
                    if ($username != "admin") {
                        $row = $query->row();
                        
                        $timezone_ = $row->s_timezone;
                        $daylight_ = $row->s_daylight;
                        $sidebar_ = $row->s_sidebar;
                        $sidebar_view_ = $row->s_sidebarview;
                        
                        if ($timezone_ != NULL)
                            Mysession::setVar ("timezone", $timezone_);
                        
                        if ($daylight_ != NULL)
                            Mysession::setVar ("daylight", $daylight_);
                        
                        if ($sidebar_ != NULL)
                            Mysession::setVar ("sidebar", $sidebar_);
                        
                        if ($sidebar_view_ != NULL)
                            Mysession::setVar ("sidebar_view", $sidebar_view_);
                    }
                    
                    Mysession::setVar('username', $username);
                    header('Location: ' . site_url() . '/welcome/home/');
                    exit;
                } else {
                    // add to log
                    Essentials::insert_log("AUTH", 0, "Authentication failed", $username);
                    Essentials::notifyLogin("fail");
                    $args["incorrect_pwd"] = TRUE;
                }
            }
            $this->load->view('login', $args);
	}
        
        public function home()
	{
            if (Mysession::getVar('username') == NULL) {
                header('Location: ' . base_url());
                exit;
            }
            $args = array();
            
            if (Mysession::getVar('username') == "admin") {
            $sql = "SELECT * FROM settings WHERE w_option = ? AND w_val = ?";
            $query = $this->db->query($sql, array("email_alerts", "TRUE"));
            
            if ($query->num_rows() == 1)
                $args["email_alerts"] = TRUE;
            }
            $this->load->view('home', $args);
	}
        
        public function servers() {
            if (Mysession::getVar('username') == NULL) {
                header('Location: ' . base_url());
                exit;
            }
            $this->load->view('servers');
        }
        
        public function accounts() {
            if (Mysession::getVar('username') == NULL) {
                header('Location: ' . base_url());
                exit;
            }
            $this->load->view('accounts');
        }
        
        public function utilities() {
            if (Mysession::getVar('username') == NULL) {
                header('Location: ' . base_url());
                exit;
            }
            $this->load->view('utilities');
        }
        
        public function settings() {
            if (Mysession::getVar('username') == NULL) {
                header('Location: ' . base_url());
                exit;
            }
            
            $args = array();
            
            if (Mysession::getVar('username') == "admin") {
            $sql = "SELECT * FROM settings WHERE w_option = ? AND w_val = ?";
            $query = $this->db->query($sql, array("email_alerts", "TRUE"));
            
            if ($query->num_rows() == 1)
                $args["email_alerts"] = TRUE;
            }
            
            $this->load->view('settings', $args);
        }
        
        public function logout() {
            Essentials::insert_log("AUTH", 1, "Logout successful");
            
            Mysession::destory_session();
            header('Location: ' . base_url());
        }
        
        public function access_forbidden() {
            $this->load->view('access_forbidden');
        }
        
        public function searchall() {
            
            $args = array();
            
            if (!isset($_POST["query"])) {
                $args["error"] = TRUE;
                $args["msg"] = "Some arguments missing..!";
                $this->load->view("searchall", $args);
                return;
            }
            
            if ( strlen($_POST["query"]) < 3) {
                $args["error"] = TRUE;
                $args["msg"] = "Search query should atleast be 3 chars long";
                $this->load->view("searchall", $args);
                return;
            }
            
            $args["query"] = trim($_POST["query"]);
            $this->load->view("searchall", $args);
            
        }
}
