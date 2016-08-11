<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cpanel extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        Essentials::checkIfIpIsAllowed();
        
        if (Mysession::getVar('username') == NULL) {
            header('Location: ' . base_url());
            exit;
        }
        
        $ed_srv = FALSE;
        $acc_ed = FALSE;
        
        if (TRUE === Essentials::checkWhamUserRole("edit_server"))
            $ed_srv = TRUE;
        
        if (TRUE === Essentials::checkWhamUserRole("add_account") && TRUE === Essentials::checkWhamUserRole("modify_account") && TRUE === Essentials::checkWhamUserRole("delete_account"))
            $acc_ed = TRUE;
        
        if ($ed_srv == FALSE && $acc_ed == FALSE)
            exit;
        
    }

    private function initPage($s_id, $user) {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $args = array();
        
        $sql = "SELECT * FROM server WHERE s_id = ?";
        $query = $this->db->query($sql, (int)$s_id);
        
        if ($query->num_rows() != 1) {
            echo "Server not found. Cannot continue";
            exit;
        }
        
        $row = $query->row();
        
        $query2 = $this->db->get_where("cpanelaccts", array("server_id" => (int)$s_id, "user" => $user));
        if ($query2->num_rows() != 1) {
            echo "Account not found. Cannot continue";
            exit;
        }
        
        $args["hostname"] = $row->s_hostname;
        $args["s_id"] = $s_id;
        $args["account"] = $user;
        
        $row2 = $query2->row();
        $args["main_domain"] = $row2->domain;
        $args["suspended"] = $row2->suspended;
        
        return $args;
    }
    
    public function home($s_id = 0, $user = "")
    {
        $args = $this->initPage($s_id, $user);
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");

        $args["stats"] = $this->xmlapi->api2_query($user, "StatsBar", "stat", array("display" => "emailaccounts|subdomains|addondomains|parkeddomains|ftpaccounts|emailforwarders|mailinglists|mysqldiskusage|diskusage|bandwidthusage|hostingpackage|theme") );
        $args["serverinfo"] = $this->xmlapi->api2_query($user, "StatsBar", "stat", array("display" => "operatingsystem|machinetype|perlversion|shorthostname|sendmailpath|perlpath|phpversion|apacheversion|kernelversion|mysqlversion|cpanelversion|hostname"));
        $args["addonlist"] = $this->xmlapi->api2_query($user, "AddonDomain", "listaddondomains");
        
        foreach($args as $arg) {
            if ($arg === NULL) {
                echo "One or more remote calls failed to return result. Cannot continue";
                exit;
            }
        }
        
        $this->load->view('servers/cpanel/home', $args);
    }
    
    public function create_addon($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["newdomain"]) || !isset($_POST["subdomain"]) || !isset($_POST["dir"])) {
            echo "Some options missing";
            exit;
        }
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "AddonDomain", "addaddondomain", $_POST);
        
        if ($result == FALSE) {
            echo "Connection error";
            exit;
        }
        
        $str = "API Output: <hr/>Status : " . $result["data"]["result"] . "<br/>" .
                "Message : " . $result["data"]["reason"];
        
        if ($result["data"]["result"] == 1 && strpos($result["data"]["reason"], "has been removed.") == FALSE)
            Essentials::insert_log ("ACTION", 1, "Addon domain created for user " . $user . 
                    " - " . $_POST["newdomain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        else
            Essentials::insert_log ("ACTION", 0, "Failed to create addon domain for user " . $user . 
                    " - " . $_POST["newdomain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        
        echo nl2br($str);
    }

    public function remove_addon($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["domain"]) || !isset($_POST["subdomain"])) {
            echo "Some options missing";
            exit;
        }
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "AddonDomain", "deladdondomain", $_POST);
        
        if ($result == FALSE) {
            echo json_encode( array("msg"=> "Connection error", "status" => 0) );
            exit;
        }
        
        $str = "API Output: <hr/>Status : " . $result["data"]["result"] . "<br/>" .
                "Message : " . $result["data"]["reason"];
        
        if ($result["data"]["result"] == 1)
            Essentials::insert_log ("ACTION", 1, "Addon domain removed from account " . $user . 
                    " - " . $_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        else
            Essentials::insert_log ("ACTION", 0, "Failed to remove addon domain from account " . $user . 
                    " - " . $_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        
        echo json_encode( array("msg"=> nl2br($str), "status" => $result["data"]["result"]) );
    }
    
    public function parked($s_id = 0, $user = "") {
        $args = $this->initPage($s_id, $user);
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");

        $args["stats"] = $this->xmlapi->api2_query($user, "StatsBar", "stat", array("display" => "emailaccounts|subdomains|addondomains|parkeddomains|ftpaccounts|emailforwarders|mailinglists|mysqldiskusage|diskusage|bandwidthusage|hostingpackage|theme") );
        $args["serverinfo"] = $this->xmlapi->api2_query($user, "StatsBar", "stat", array("display" => "operatingsystem|machinetype|perlversion|shorthostname|sendmailpath|perlpath|phpversion|apacheversion|kernelversion|mysqlversion|cpanelversion|hostname"));
        $args["parkedlist"] = $this->xmlapi->api2_query($user, "Park", "listparkeddomains");
        
        foreach($args as $arg) {
            if ($arg === NULL) {
                echo "One or more remote calls failed to return result. Cannot continue";
                exit;
            }
        }
        
        $this->load->view('servers/cpanel/parked', $args);
    }
    
    public function create_parked($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["domain"])) {
            echo "Some options missing";
            exit;
        }
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "Park", "park", $_POST);
        
        if ($result == FALSE) {
            echo json_encode( array("msg"=> "Connection error", "status" => 0) );
            exit;
        }
        
        $str = "API Output: <hr/>Status : " . $result["data"]["result"] . "<br/>" .
                "Message : " . $result["data"]["reason"];
        
        if ($result["data"]["result"] == 1)
            Essentials::insert_log ("ACTION", 1, "New parked domain created for account ". $user . 
                    " - " . $_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        else
            Essentials::insert_log ("ACTION", 0, "Failed to create new parked domain created for account ". $user . 
                    " - " . $_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        
        
        echo json_encode( array("msg"=> nl2br($str), "status" => $result["data"]["result"]) );
    }
    
    public function remove_parked($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["domain"])) {
            echo "Some options missing";
            exit;
        }
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "Park", "unpark", $_POST);
        
        if ($result == FALSE) {
            echo json_encode( array("msg"=> "Connection error", "status" => 0) );
            exit;
        }
        
        $str = "API Output: <hr/>Status : " . $result["data"]["result"] . "<br/>" .
                "Message : " . $result["data"]["reason"];
        
        if ($result["data"]["result"] == 1)
            Essentials::insert_log ("ACTION", 1, "Parked domain removed from account ". $user . 
                    " - " . $_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        else
            Essentials::insert_log ("ACTION", 0, "Failed to remove parked domain from account ". $user . 
                    " - " . $_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        
        echo json_encode( array("msg"=> nl2br($str), "status" => $result["data"]["result"]) );
    }
    
    public function sub($s_id = 0, $user = "") {
        $args = $this->initPage($s_id, $user);
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");

        $args["stats"] = $this->xmlapi->api2_query($user, "StatsBar", "stat", array("display" => "emailaccounts|subdomains|addondomains|parkeddomains|ftpaccounts|emailforwarders|mailinglists|mysqldiskusage|diskusage|bandwidthusage|hostingpackage|theme") );
        $args["serverinfo"] = $this->xmlapi->api2_query($user, "StatsBar", "stat", array("display" => "operatingsystem|machinetype|perlversion|shorthostname|sendmailpath|perlpath|phpversion|apacheversion|kernelversion|mysqlversion|cpanelversion|hostname"));
        $args["subdomainlist"] = $this->xmlapi->api2_query($user, "SubDomain", "listsubdomains");
        
        
        // for subdomain create select
        $list_all_domains = $this->xmlapi->api2_query($user, "DomainLookup", "getbasedomains");
        if ($list_all_domains != FALSE) {
            $args["list_all_domains"] = array();
            if (isset($list_all_domains["data"]) && is_array($list_all_domains["data"]) && isset($list_all_domains["data"]["domain"])) {
                $args["list_all_domains"] = array($list_all_domains["data"]["domain"]);
            } else if (isset($list_all_domains["data"]) && is_array($list_all_domains["data"]) && !isset($list_all_domains["data"]["domain"])) {
                foreach ($list_all_domains["data"] as $domain)
                    array_push($args["list_all_domains"], $domain["domain"]);
            }
        }
        
        foreach($args as $arg) {
            if ($arg === NULL) {
                echo "One or more remote calls failed to return result. Cannot continue";
                exit;
            }
        }
        
        $this->load->view('servers/cpanel/sub', $args);
    }
    
    
    public function create_sub($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["domain"]) || !isset($_POST["rootdomain"])) {
            echo "Some options missing";
            exit;
        }
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "SubDomain", "addsubdomain", $_POST);
        
        if ($result == FALSE) {
            echo json_encode( array("msg"=> "Connection error", "status" => 0) );
            exit;
        }
        
        $str = "API Output: <hr/>Status : " . $result["data"]["result"] . "<br/>" .
                "Message : " . $result["data"]["reason"];
        
        if ($result["data"]["result"] == 1)
            Essentials::insert_log ("ACTION", 1, "New sub domain created for account ". $user . 
                    " - " . $_POST["domain"] . '.' . $_POST["rootdomain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        else
            Essentials::insert_log ("ACTION", 0, "Failed to create new sub domain for account ". $user . 
                    " - " . $_POST["domain"] . '.' . $_POST["rootdomain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        
        
        echo json_encode( array("msg"=> nl2br($str), "status" => $result["data"]["result"]) );
    }
    
    
    public function remove_sub($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["domain"])) {
            echo "Some options missing";
            exit;
        }
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "SubDomain", "delsubdomain", $_POST);
        
        if ($result == FALSE) {
            echo json_encode( array("msg"=> "Connection error", "status" => 0) );
            exit;
        }
        
        $str = "API Output: <hr/>Status : " . $result["data"]["result"] . "<br/>" .
                "Message : " . $result["data"]["reason"];
        
        if ($result["data"]["result"] == 1)
            Essentials::insert_log ("ACTION", 1, "Sub domain removed from account ". $user . 
                    " - " . $_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        else
            Essentials::insert_log ("ACTION", 0, "Failed to remove sub domain from account ". $user . 
                    " - " . $_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        
        echo json_encode( array("msg"=> nl2br($str), "status" => $result["data"]["result"]) );
    }
    
    
    public function email($s_id = 0, $user = "") {
        $args = $this->initPage($s_id, $user);
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");

        $args["stats"] = $this->xmlapi->api2_query($user, "StatsBar", "stat", array("display" => "emailaccounts|subdomains|addondomains|parkeddomains|ftpaccounts|emailforwarders|mailinglists|mysqldiskusage|diskusage|bandwidthusage|hostingpackage|theme") );
        $args["serverinfo"] = $this->xmlapi->api2_query($user, "StatsBar", "stat", array("display" => "operatingsystem|machinetype|perlversion|shorthostname|sendmailpath|perlpath|phpversion|apacheversion|kernelversion|mysqlversion|cpanelversion|hostname"));
        
        $emailacctlist_tmp = $this->xmlapi->api2_query($user, "Email", "listpopswithdisk");
        $emaildomainslist_tmp = $this->xmlapi->api2_query($user, "Email", "listmaildomains");
        
        
        if (isset($emailacctlist_tmp["data"])) {
            $emailacctlist_filtered = $emailacctlist_tmp["data"];
            $emailacctlist = array();
            
            if (isset($emailacctlist_filtered["domain"])) {
                $ar = array("email" => $emailacctlist_filtered["email"], 
                    "diskused" => $emailacctlist_filtered["diskused"], 
                    "quota" => $emailacctlist_filtered["txtdiskquota"],
                    "user" => $emailacctlist_filtered["user"]);
                
                $emailacctlist[$emailacctlist_filtered["domain"]] = array();
                array_push($emailacctlist[$emailacctlist_filtered["domain"]], $ar);
            }
            else {
            
                foreach ($emailacctlist_filtered as $arr) {
                    $ar = array("email" => $arr["email"], 
                        "diskused" => $arr["diskused"], 
                        "quota" => $arr["txtdiskquota"],
                        "user" => $arr["user"]);

                    if (!isset($emailacctlist[$arr["domain"]]))
                        $emailacctlist[$arr["domain"]] = array();

                    array_push($emailacctlist[$arr["domain"]], $ar);
                }
            }
            
            $args["emailacctlist"] = $emailacctlist;
            
        }
        
        $emaildomains = array();
        
        if (isset($emaildomainslist_tmp) && isset($emaildomainslist_tmp["data"])) {
            $emaildomains_ = $emaildomainslist_tmp["data"];
            if (count($emaildomains_) > 0 && !isset($emaildomains_["domain"])) {
                foreach ($emaildomains_ as $tmp_)
                    array_push($emaildomains, $tmp_["domain"]);
            } else if (count($emaildomains_) > 0 && isset($emaildomains_["domain"])) {
                $emaildomains = $emaildomains_;
            }
        }
        
        if (is_array($emaildomains) && count($emaildomains) > 0)
            $args["emaildomains"] = $emaildomains;
        
        foreach($args as $arg) {
            if ($arg === NULL) {
                echo "One or more remote calls failed to return result. Cannot continue";
                exit;
            }
        }
        
        $this->load->view('servers/cpanel/email', $args);
    }
    
    public function create_email($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["domain"]) || !isset($_POST["email"]) || 
                !isset($_POST["password"]) || !isset($_POST["quota"])) {
            echo "Some options missing";
            exit;
        }
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "Email", "addpop", $_POST);
        
        if ($result == FALSE) {
            echo json_encode( array("msg"=> "Connection error", "status" => 0) );
            exit;
        }
        
        $str = "API Output: <hr/>Status : " . $result["data"]["result"] . "<br/>" .
                "Message : ";
        
        $str .= ($result["data"]["result"] == 0)? nl2br($result["data"]["reason"]):"Success";
        
        if ($result["data"]["result"] == 1)
            Essentials::insert_log ("ACTION", 1, "New email address created for account ". $user . 
                    " - " . $_POST["email"] . "@" . $_POST["domain"], '', $s_id);
        else
            Essentials::insert_log ("ACTION", 0, "Failed to create new email for account ". $user . 
                    " - " . $_POST["email"] . "@" . $_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        
        
        echo json_encode( array("msg"=> nl2br($str), "status" => $result["data"]["result"]) );
    }
    
    public function resetpasswd_email($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["domain"]) || !isset($_POST["email"]) || !isset($_POST["password"])) {
            echo "Some options missing";
            exit;
        }
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "Email", "passwdpop", $_POST);
        
        if ($result == FALSE) {
            echo json_encode( array("msg"=> "Connection error", "status" => 0) );
            exit;
        }
        
        $str = "API Output: <hr/>Status : " . $result["data"]["result"] . "<br/>" .
                "Message : ";
        
        $str .= ($result["data"]["result"] == 0)? nl2br($result["data"]["reason"]):"Success";
        
        if ($result["data"]["result"] == 1)
            Essentials::insert_log ("ACTION", 1, "Email password changed (account: $user) for " . 
                    $_POST["email"] . "@" .$_POST["domain"], '', $s_id);
        else
            Essentials::insert_log ("ACTION", 0, "Failed to change password (account: $user) for email address ". 
                    $_POST["email"] . "@" .$_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        
        echo json_encode( array("msg"=> nl2br($str), "status" => $result["data"]["result"]) );
    }

    public function resetquota_email($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["domain"]) || !isset($_POST["email"]) || !isset($_POST["quota"])) {
            echo "Some options missing";
            exit;
        }
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "Email", "editquota", $_POST);
        
        if ($result == FALSE) {
            echo json_encode( array("msg"=> "Connection error", "status" => 0) );
            exit;
        }
        
        $str = "API Output: <hr/>Status : " . $result["data"]["result"] . "<br/>" .
                "Message : ";
        
        $str .= ($result["data"]["result"] == 0)? nl2br($result["data"]["reason"]):"Success";
        
        if ($result["data"]["result"] == 1)
            Essentials::insert_log ("ACTION", 1, "Email quota modified (account: $user) for " . 
                    $_POST["email"] . "@" . $_POST["domain"] . ", new quota set to " . $_POST["quota"] . " MB", '', $s_id);
        else
            Essentials::insert_log ("ACTION", 0, "Failed to modify quota (account: $user) for email address ". 
                    $_POST["email"] . "@" .$_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        
        echo json_encode( array("msg"=> nl2br($str), "status" => $result["data"]["result"]) );
    }

    public function remove_email($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["domain"]) || !isset($_POST["email"])) {
            echo "Some options missing";
            exit;
        }
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "Email", "delpop", $_POST);
        
        if ($result == FALSE) {
            echo json_encode( array("msg"=> "Connection error", "status" => 0) );
            exit;
        }
        
        $str = "API Output: <hr/>Status : " . $result["data"]["result"] . "<br/>" .
                "Message : ";
        
        $str .= ($result["data"]["result"] == 0)? nl2br($result["data"]["reason"]):"Success";
        
        if ($result["data"]["result"] == 1)
            Essentials::insert_log ("ACTION", 1, "Email account removed (account: $user) ". $user . 
                    " - " . $_POST["email"] . "@". $_POST["domain"], '', $s_id);
        else
            Essentials::insert_log ("ACTION", 0, "Failed to remove email account (account: $user) " . $user . 
                    " - " . $_POST["email"] . "@". $_POST["domain"] . "<br/>". nl2br($result["data"]["reason"]), '', $s_id);
        
        echo json_encode( array("msg"=> nl2br($str), "status" => $result["data"]["result"]) );
    }
    
    public function custinfo($s_id = 0, $user = "")
    {
        $args = $this->initPage($s_id, $user);
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");

        $args["stats"] = $this->xmlapi->api2_query($user, "StatsBar", "stat", array("display" => "emailaccounts|subdomains|addondomains|parkeddomains|ftpaccounts|emailforwarders|mailinglists|mysqldiskusage|diskusage|bandwidthusage|hostingpackage|theme") );
        $args["serverinfo"] = $this->xmlapi->api2_query($user, "StatsBar", "stat", array("display" => "operatingsystem|machinetype|perlversion|shorthostname|sendmailpath|perlpath|phpversion|apacheversion|kernelversion|mysqlversion|cpanelversion|hostname"));
        $args["custinfo"] = $this->xmlapi->api2_query($user, "CustInfo", "displaycontactinfo");
        
        
        $args["email"] = (isset($args["custinfo"]["data"]) && !is_array($args["custinfo"]["data"][0]["value"])) ? $args["custinfo"]["data"][0]["value"] : "";
        $args["second_email"] = (isset($args["custinfo"]["data"]) && !is_array($args["custinfo"]["data"][1]["value"])) ? $args["custinfo"]["data"][1]["value"] : "";
        $args["notify_disk_limit"] = (isset($args["custinfo"]["data"]) && !is_array($args["custinfo"]["data"][2]["value"])) ? $args["custinfo"]["data"][2]["value"] : 0;
        $args["notify_bandwidth_limit"] = (isset($args["custinfo"]["data"]) && !is_array($args["custinfo"]["data"][3]["value"])) ? $args["custinfo"]["data"][3]["value"] : 0;
        $args["notify_email_quota_limit"] = (isset($args["custinfo"]["data"]) && !is_array($args["custinfo"]["data"][4]["value"])) ? $args["custinfo"]["data"][4]["value"] : 0;
        
        foreach($args as $arg) {
            if ($arg === NULL) {
                echo "One or more remote calls failed to return result. Cannot continue";
                exit;
            }
        }
        
        unset($args["custinfo"]);
        

        $this->load->view('servers/cpanel/custinfo', $args);
    }
    
    public function update_custinfo($s_id = 0, $user = "") {
        if ($s_id == 0)
            exit;
        
        if ($user == "" or $user == "root")
            exit;
        
        $det = Cpanelwhm::getIpRemoteKeyUsername($s_id);
        
        if (!isset($_POST["email"]) || !isset($_POST["second_email"]) || !isset($_POST["notify_email_quota_limit"]) ||
                !isset($_POST["notify_disk_limit"]) || !isset($_POST["notify_bandwidth_limit"])) {
            echo "Some options missing";
            exit;
        }
        
        $_POST["notify_email_quota_limit"] = ($_POST["notify_email_quota_limit"] == "TRUE")? TRUE:FALSE;
        $_POST["notify_disk_limit"] = ($_POST["notify_disk_limit"] == "TRUE")? TRUE:FALSE;
        $_POST["notify_bandwidth_limit"] = ($_POST["notify_bandwidth_limit"] == "TRUE")? TRUE:FALSE;
        
        
        $this->load->library("xmlapi");
        $this->xmlapi->setup($det["ip"]);
        $this->xmlapi->hash_auth($det["username"], Essentials::decrypt($det["remotekey"]));
        $this->xmlapi->set_output("array");
        
        $result = $this->xmlapi->api2_query($user, "CustInfo", "savecontactinfo", $_POST);
        
        if ($result == FALSE) {
            echo json_encode( array("msg"=> "Connection error", "status" => 0) );
            exit;
        }
        
        $str = "API Output: <hr/>Status : 1<br/>" .
                "Message : Success";
        
        $this->db->where("server_id", $s_id);
        $this->db->where("user", $user);
        $this->db->update("cpanelaccts", array("email" => $_POST["email"]));
        Essentials::insert_log ("ACTION", 1, "Customer info updated for account ". $user, '', $s_id);
        
        echo json_encode( array("msg"=> nl2br($str), "status" => 1 ));
    }


}
