<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accountsearch extends CI_Controller {
    
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

        $this->load->view('accounts/accountsearch');
    }
    
    public function search() {
        if (!isset($_POST["what"]) || !isset($_POST["for"]) || !isset($_POST["condition"]) || 
                !isset($_POST["query"])) {
            echo "<i>Some arguments missing..</i>";
            exit;
        }
        
        if (!in_array($_POST["what"], array("account"))) {
            echo "<i>Invalid argument 1</i>";
            exit;
        }
        
        if (!in_array($_POST["for"], array("user", "domain", "email", "ip", "owner", "plan", "any"))) {
            echo "<i>Invalid argument 2</i>";
            exit;
        }
        
        if (!in_array($_POST["condition"], array("equals", "contains", "begins_with", "ends_with"))) {
            echo "<i>Invalid argument 3</i>";
            exit;
        }
        
        if (strlen($_POST["query"]) < 3) {
            echo "<i>Query string should be atleast 3 chars</i>";
            exit;
        }
        
        $this->db->select('*');
        $this->db->join("server", 'server.s_id = cpanelaccts.server_id', "left");
        $this->db->join("panels", 'panels.p_id = server.s_cp', "left");
        $this->db->from("cpanelaccts");
        $this->db->where("server.s_isactive", "Y");
        
        $arr = array();
        
        if ($_POST["for"] != "any") {
            if ($_POST["condition"] == "equals") $this->db->where($_POST["for"], $_POST["query"]);
            if ($_POST["condition"] == "contains") $this->db->like($_POST["for"], $_POST["query"]);
            if ($_POST["condition"] == "begins_with") $this->db->like($_POST["for"], $_POST["query"], "after");  // LIKE 'match%' 
            if ($_POST["condition"] == "ends_with") $this->db->like($_POST["for"], $_POST["query"], "before");  // LIKE '%match'
        } else {
            
            $arr = array("user", "domain", "email", "ip", "owner", "plan");
            
            foreach ($arr as $key) {
                if ($_POST["condition"] == "equals") $this->db->or_where($key, $_POST["query"]);
                if ($_POST["condition"] == "contains") $this->db->or_like($key, $_POST["query"]);
                if ($_POST["condition"] == "begins_with") $this->db->or_like($key, $_POST["query"], "after");
                if ($_POST["condition"] == "ends_with") $this->db->or_like($key, $_POST["query"], "before");
            }
        }
            
        $query = $this->db->get();
        
        $args["result"] = $query->result();
        
        
        $this->load->view("accounts/search/accounts", $args);
        
    }
    
}
