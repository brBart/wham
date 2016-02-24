<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portscan extends CI_Controller {
    
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
            
            $this->load->view('utilities/portscan', $args);
        }
        
        public function backend($val="") {
            if ($val == "")
                echo "Invalid input received";
            else {
                $ch = curl_init();
                $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
                
                $str = json_encode(array("inputText" => "scan:$val", "resultIndex" => 1));
                
                $str_len = strlen($str);
                
                $arr = array(
                    "Content-Type: application/json; charset=UTF-8",
                    "X-Requested-With: XMLHttpRequest",
                    "Accept: application/json, text/javascript, */*; q=0.01",
                    "Content-Length: $str_len",
                    "Cookie: _vis_opt_s=1%7C; ASP.NET_SessionId=4wtgz0idakyldfkm1vzwgzmw; ismobile=False; _vis_opt_test_cookie=1; __utma=74094414.256549006.1356199559.1356870517.1356873158.4; __utmb=74094414.4.8.1356873159660; __utmc=74094414; __utmz=74094414.1356870517.3.3.utmcsr=all-nettools.com|utmccn=(referral)|utmcmd=referral|utmcct=/toolbox/blacklist-check.php; __utmv=74094414.|2=OnBlacklist=true=1; isloggedin=False; ispaid=False"
                );
                
                curl_setopt($ch, CURLOPT_URL, "http://www.mxtoolbox.com/Public/Lookup.aspx/DoLookup2");
                curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                curl_setopt($ch, CURLOPT_POST, true); 
                curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                
                
                $tmp = curl_exec($ch);
                
                if ($tmp === FALSE){
                    echo "<div class='tool-result-div well'>Error retrieving information from external source. 
                        Please try after some time.</div>";
                } else {
                    $a = json_decode($tmp);

                    $html1 = $a->d;
                    $html2 = json_decode($html1);

                    $final_html = preg_replace("/Reported by .*on.*<\/a>\./", "", $html2->HTML_Value);

                    echo $final_html;
                }
            }
        }
}
