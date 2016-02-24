<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dnsreport extends CI_Controller {
    
	public function __construct() {
            parent::__construct();
            Essentials::checkIfIpIsAllowed();
            if (Mysession::getVar('username') == NULL) {
                header('Location: ' . base_url());
                exit;
            }
        }
        
        public function index($dom="")
	{ 
            $args = array();
            
            if (strlen(trim($dom)) > 4)
                $args["args"] = trim($dom);
            
            $this->load->view('utilities/dnsreport', $args);
        }
        
        public function backend($val="") {
            if ($val == "")
                echo "Invalid input received";
            else {
                $ch = curl_init();
                $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                
                curl_setopt($ch, CURLOPT_URL, "http://www.intodns.com/$val");
                curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                curl_setopt($ch, CURLOPT_POST, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                
                
                $tmp = curl_exec($ch);
                
                if ($tmp === FALSE){
                    echo "<div class='tabular well'>Error retrieving information from external source. 
                        Please try after some time.</div>";
                } else {
                    

                    $final_html = preg_replace("/<img src=\"\//", '<img src="http://www.intodns.com/', $tmp);
                    $final_html = preg_replace("/<img src=\"\.\./", '<img src="http://www.intodns.com', $final_html);
                    $final_html = preg_replace("/<link href=\"/", '<link href="http://www.intodns.com', $final_html);
                    $final_html = preg_replace("/<script src.*$/", '', $final_html);

                    echo $final_html;
                }
            }
        }
}
