<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Essentials
 * is a custom class created to easily perform common tasks related to WHAM
 */

Class Essentials {
    
    private static $password = '8Bs#2nZa)s!66sAzI*7b<Ws';
    
    /**
     * Converts UNIXTIMESTAMP (in GMT) from db to localtime, human readable 
     * string format.
     * 
     * @param UNIXTIMESTAMP $gmt
     * @return String
     */
    public static function toLocalTime($gmt) {
        
        $timezone = "";
        $dst = NULL;
        
        if (Mysession::getVar("timezone") == NULL) {
            $sql = "SELECT * FROM settings WHERE w_option = ?";
            $CI =& get_instance();
            $query = $CI->db->query($sql, "timezone");
            
            if ($query->num_rows() == 1) {
                $row = $query->row();
                $timezone = $row->w_val;
            } else
                $timezone = "UP55"; // IST default
        } else
            $timezone = Mysession::getVar("timezone");
        
        if (Mysession::getVar("daylight") == NULL) {
            $sql = "SELECT * FROM settings WHERE w_option = ?";
            $CI =& get_instance();
            $query = $CI->db->query($sql, "daylight");
            
            if ($query->num_rows() == 1) {
                $row = $query->row();
                $daylight = ($row->w_val == "TRUE")?TRUE:FALSE ;;
            } else
                $daylight = FALSE; // default daylight is false
        } else
            $dst = (Mysession::getVar("daylight") == "TRUE")?TRUE:FALSE ;

        return unix_to_human(gmt_to_local($gmt, $timezone, $dst));
        
    }
    
    /**
     * Encrypts a string or TEXT and returns the encrypted string. Useful to 
     * store info in db.
     * 
     * @param string $string
     * @return string 
     */
    public static function encrypt($string) {
        $password = self::$password;
        $strlen = strlen($string);
        $enc_str = "";
            
        for ($i = 0; $i < $strlen ; $i++){
            //current char
            $chr = substr($string,$i,1);

            //get password char by char
            $modulus = $i % strlen($password);
            $passwordchr = substr($password,$modulus, 1);

            //encryption algorithm
            $enc_str .= chr(ord($chr)+ord($passwordchr));
        }
        return base64_encode($enc_str);
    }
    
    /**
     * Decrypts a string or TEXT from WHAM! db and returns the original string.
     * @param string $string
     * @return string 
     */
    public static function decrypt($string) {
        $password = self::$password;
        $enc_str = base64_decode($string);
        $strlen = strlen($enc_str);
        $dec_str = "";
        
        for ($i = 0; $i < $strlen ; $i++){
            //current char
            $chr = substr($enc_str,$i,1);

            //get password char by char
            $modulus = $i % strlen($password);
            $passwordchr = substr($password,$modulus, 1);

            //encryption algorithm
            $dec_str .= chr(ord($chr)-ord($passwordchr));
        }
        return $dec_str;
    }
    
    public static function checkWhamUserRole($role, $redirect=0, $user="") {
        
        $redirect_url = 'Location: ' . site_url() . "/welcome/access_forbidden/";
        
        if ($user == "")
            $user = Mysession::getVar("username");
        
        if ($user == "admin") {
            return TRUE;
        } else {
            $sql = "SELECT users.u_name, roles.role_priv FROM users 
                LEFT JOIN roles ON (users.u_roleid = roles.role_id) WHERE 
                users.u_name = ?";
            
            $CI =& get_instance();
            $query = $CI->db->query($sql, $user);
            
            if ($query->num_rows() == 1) {
                $row = $query->row();
                $roles = json_decode($row->role_priv);
                
                if (isset($roles->$role) && $roles->$role == 1){
                    if ($redirect == 0)
                        return TRUE;
                } else {
                    if ($redirect == 0)
                        return FALSE;
                    else
                        header($redirect_url);
                }
                
            } else {
                if ($redirect == 0)
                    return FALSE;
                else
                    header($redirect_url);
            }
        }
    }
    
    
    /**
     * Function to insert a log record
     * 
     * @param string $log_type  (AUTH | ACTION)
     * @param int $log_status   (0 = fail, 1 = success)
     * @param string $log_msg   log message
     * @param string $log_user  username
     * @param int $log_server   server id of the server 
     */
    public static function insert_log($log_type="", $log_status=0, $log_msg="", $log_user="", $log_server = NULL) {
        $sql = "SELECT * FROM settings WHERE w_option=?";
        
        $CI =& get_instance();
        $query = $CI->db->query($sql, "logging");
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $cur_log_settings = json_decode($row->w_val);
            
            /**
             * Fix for bug: 
             * 
             * On the front page, pressing the "Login" button with nothing entered in 
             * the Username field generates a database exception stating "Column 
             * 'log_user' cannot be null"
             */
            
            if ($log_user == "" && Mysession::getVar('username') != NULL) 
                $log_user = Mysession::getVar('username');
            
            
            if (isset($cur_log_settings->logging) && $cur_log_settings->logging == TRUE) {
                
                // get remote ip address
                $ipaddress = '';
                if (isset($_SERVER['HTTP_CLIENT_IP']))
                    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                else if(isset($_SERVER['HTTP_X_FORWARDED']))
                    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                else if(isset($_SERVER['HTTP_FORWARDED']))
                    $ipaddress = $_SERVER['HTTP_FORWARDED'];
                else if(isset($_SERVER['REMOTE_ADDR']))
                    $ipaddress = $_SERVER['REMOTE_ADDR'];
                else
                    $ipaddress = 'UNKNOWN';
                
                if ($log_type == "AUTH" && isset($cur_log_settings->log_all_login_attempts) && 
                        $cur_log_settings->log_all_login_attempts == TRUE) {
                    
                    // code for logging all login/logout
                    
                    $date = now();
                    
                    $data = array(
                        "log_type" => $log_type,
                        "log_status" => $log_status,
                        "log_ip" => $ipaddress,
                        "log_msg" => $log_msg,
                        "log_user" => $log_user,
                        "log_time" => $date
                    );
                    
                    $CI->db->insert("logs", $data);
                    
                }
                else if ($log_type == "ACTION" && isset($cur_log_settings->log_actions) && 
                        $cur_log_settings->log_actions == TRUE) {
                    
                    // code to log all actions
                    
                    $date = now();
                    
                    $data = array(
                        "log_type" => $log_type,
                        "log_status" => $log_status,
                        "log_ip" => $ipaddress,
                        "log_msg" => $log_msg,
                        "log_user" => $log_user,
                        "log_time" => $date
                    );
                    
                    if ($log_server != NULL)
                        $data["log_server"] = $log_server;
                    
                    $CI->db->insert("logs", $data);
                } else if ($log_type == "SYNC") {
                    // code to log all actions
                    
                    $date = now();
                    
                    $data = array(
                        "log_type" => $log_type,
                        "log_status" => $log_status,
                        "log_ip" => $ipaddress,
                        "log_msg" => $log_msg,
                        "log_user" => $log_user,
                        "log_time" => $date
                    );
                    
                    if ($log_server != NULL)
                        $data["log_server"] = $log_server;
                    
                    $CI->db->insert("logs", $data);
                }
            }

        }
    }
    
    public static function notifyLogin($successOrFail) {
        $CI =& get_instance();
        $sql = "SELECT * FROM settings WHERE w_option = ?";
        $query1 = $CI->db->query($sql, "email_alerts");
        
        if ($query1->num_rows() == 1) {
            $row1 = $query1->row();
            if ($row1->w_val == "TRUE") {
                $query2 = $CI->db->query($sql, "send_email_to");
                $row2 = $query2->row();
                $email_to = $row2->w_val;
                
                $query3 = $CI->db->query($sql, "send_email_cc");
                $row3 = $query3->row();
                if (strlen(trim($row3->w_val)) > 4) $email_cc = $row3->w_val;
                
                $query4 = $CI->db->query($sql, "notify_settings");
                $row4 = $query4->row();
                $notify_all_settings = json_decode($row4->w_val);
                
                $notify = $notify_all_settings->notify;
                
                if ($notify == "failonly" || $notify == "all") {
                    $query5 = $CI->db->query($sql, "email_settings");
                    $row5 = $query5->row();
                    $all_email_settings = json_decode($row5->w_val);

                    $config["protocol"] = $all_email_settings->protocol;
                    $config["smtp_user"] = $all_email_settings->smtp_user;
                    $config["wordwrap"] = TRUE;
                    $config["mailtype"] = "text";
                    $config["charset"] = "iso-8859-1";
                    $config["useragent"] = "WHAM!";

                    if ($config["protocol"] == "smtp") {
                        $config["smtp_pass"] = $all_email_settings->smtp_pass;
                        $config["smtp_host"] = $all_email_settings->smtp_host;
                        $config["smtp_port"] = $all_email_settings->smtp_port;
                        $config["smtp_timeout"] = 15;
                        if (isset($all_email_settings->smtp_crypto)) {
                            if ($all_email_settings->smtp_crypto == "ssl" || $all_email_settings->smtp_crypto == "tls")
                                $config["smtp_crypto"] = $all_email_settings->smtp_crypto;
                        }
                    }

                    // get remote ip address
                    $ipaddress = '';
                    if (isset($_SERVER['HTTP_CLIENT_IP']))
                        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    else if(isset($_SERVER['HTTP_X_FORWARDED']))
                        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    else if(isset($_SERVER['HTTP_FORWARDED']))
                        $ipaddress = $_SERVER['HTTP_FORWARDED'];
                    else if(isset($_SERVER['REMOTE_ADDR']))
                        $ipaddress = $_SERVER['REMOTE_ADDR'];
                    else
                        $ipaddress = 'UNKNOWN';

                    $CI->load->library("email");
                    $CI->email->initialize($config);

                    $CI->email->from($config["smtp_user"], "WHAM!");
                    $CI->email->to($email_to);

                    if(isset($email_cc)) $CI->email->cc($email_cc);

                    $wham_url = base_url();

                    $time_now = self::toLocalTime(now());

                    $status = strtoupper($successOrFail);

                    if ($status == "FAIL") 
                        $CI->email->subject("Failed login attempt notification");
                    else
                        $CI->email->subject("Login attempt notification");
                    
                    $msg = "
Hello,

This is an automated email to notify you that someone tried to login to 
WHAM! ($wham_url), details given below:


Login attempt from ip ..: $ipaddress
Time of attempt ........: $time_now
Status .................: $status


If this looks suspicious, please investigate further. It is recommended 
that you secure your WHAM! install using the security features available 
at WHAM! -> Settings -> Configure Firewall.

Thank You

Regards,
WHAM!" ;
                    $CI->email->message($msg);

                    if ($notify == "failonly" && $successOrFail == "fail") {
                        $CI->email->send();

                    } else if ($notify == "all") {
                        $CI->email->send();
                    }
                }
            }
        }
    }
    
    
    public static function notifyWhiteListAuth($successOrFail) {
        $CI =& get_instance();
        $sql = "SELECT * FROM settings WHERE w_option = ?";
        $query1 = $CI->db->query($sql, "email_alerts");
        
        if ($query1->num_rows() == 1) {
            $row1 = $query1->row();
            if ($row1->w_val == "TRUE") {
                $query2 = $CI->db->query($sql, "send_email_to");
                $row2 = $query2->row();
                $email_to = $row2->w_val;
                
                $query3 = $CI->db->query($sql, "send_email_cc");
                $row3 = $query3->row();
                if (strlen(trim($row3->w_val)) < 4) $email_cc = $row3->w_val;
                
                $query4 = $CI->db->query($sql, "notify_settings");
                $row4 = $query4->row();
                $notify_all_settings = json_decode($row4->w_val);
                
                $notify_whitelist = $notify_all_settings->notify_whitelist_from_url;
                
                if ($notify_whitelist == TRUE) {
                    $query5 = $CI->db->query($sql, "email_settings");
                    $row5 = $query5->row();
                    $all_email_settings = json_decode($row5->w_val);

                    $config["protocol"] = $all_email_settings->protocol;
                    $config["smtp_user"] = $all_email_settings->smtp_user;
                    $config["wordwrap"] = TRUE;
                    $config["mailtype"] = "text";
                    $config["charset"] = "iso-8859-1";
                    $config["useragent"] = "WHAM!";

                    if ($config["protocol"] == "smtp") {
                        $config["smtp_pass"] = $all_email_settings->smtp_pass;
                        $config["smtp_host"] = $all_email_settings->smtp_host;
                        $config["smtp_port"] = $all_email_settings->smtp_port;
                        $config["smtp_timeout"] = 15;
                        if (isset($all_email_settings->smtp_crypto)) {
                            if ($all_email_settings->smtp_crypto == "ssl" || $all_email_settings->smtp_crypto == "tls")
                                $config["smtp_crypto"] = $all_email_settings->smtp_crypto;
                        }
                    }

                    // get remote ip address
                    $ipaddress = '';
                    if (isset($_SERVER['HTTP_CLIENT_IP']))
                        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
                    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
                    else if(isset($_SERVER['HTTP_X_FORWARDED']))
                        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
                    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
                        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
                    else if(isset($_SERVER['HTTP_FORWARDED']))
                        $ipaddress = $_SERVER['HTTP_FORWARDED'];
                    else if(isset($_SERVER['REMOTE_ADDR']))
                        $ipaddress = $_SERVER['REMOTE_ADDR'];
                    else
                        $ipaddress = 'UNKNOWN';

                    $CI->load->library("email");
                    $CI->email->initialize($config);

                    $CI->email->from($config["smtp_user"], "WHAM!");
                    $CI->email->to($email_to);

                    if(isset($email_cc)) $CI->email->cc($email_cc);

                    $wham_url = base_url();

                    $time_now = self::toLocalTime(now());

                    $status = strtoupper($successOrFail);

                    if ($status == "FAIL") 
                        $CI->email->subject("Failed Whitelist URL login attempt notification");
                    else
                        $CI->email->subject("Login (Whitelist URL) attempt notification");
                    
                    $msg = "
Hello,

This is an automated email to notify you that someone tried to login to 
WHAM! secret whitelist URL, details given below:


Login attempt from ip ..: $ipaddress
Time of attempt ........: $time_now
Status .................: $status


If this looks suspicious, please investigate further.

Thank You

Regards,
WHAM!" ;
                    $CI->email->message($msg);

                    
                    $CI->email->send();
                    
                }
            }
        }
    }
    
    
    public static function checkIfIpIsAllowed() {
        
        // get remote ip address
        
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        
        // exit if unable to find valid ip from request
        
        if (!filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && 
                !filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            exit;
        }
        
        $CI =& get_instance();
        
        // check if firewall is enabled or not
        
        $sql = "SELECT * FROM settings WHERE w_option = ? AND w_val = ?";
        
        $query = $CI->db->query($sql, array("firewall", "TRUE"));
        
        if ($query->num_rows() == 0)
            return FALSE;
        
        // firewall = TRUE, now check what is firewall mode
        $sql2 = "SELECT * FROM settings WHERE w_option = ?";
        $query2 = $CI->db->query($sql2, "firewall_mode");
        
        if ($query2->num_rows() == 0)
            return FALSE; // firewall mode not configured
        
        $row2 = $query2->row();
        $firewall_mode = $row2->w_val;
        
        /**
         * $firewall_mode can either be "ALLOW_ALL_BLOCK_FEW" or 
         * "BLOCK_ALL_ALLOW_FEW".
         *  
         */
        
        if ($firewall_mode == "ALLOW_ALL_BLOCK_FEW") {
            if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $range = preg_replace("/(\d+\.\d+\.\d+\.)(\d+)/", '${1}0', $ipaddress);

                $sql3 = "SELECT * FROM blockedips WHERE b_ip = ? OR b_ip = ?";
                $query3 = $CI->db->query($sql3, array($ipaddress, $range));
                
                if ($query3->num_rows() == 0)
                    return TRUE;
                else
                    exit;
            }
            
            if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $sql4 = "SELECT * FROM blockedips WHERE b_ip = ?";
                $query4 = $CI->db->query($sql4, $ipaddress);
                
                if ($query4->num_rows() == 0)
                    return TRUE;
                else
                    exit;
            }
            
        } else if ($firewall_mode == "BLOCK_ALL_ALLOW_FEW") {
            if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $range2 = preg_replace("/(\d+\.\d+\.\d+\.)(\d+)/", '${1}0', $ipaddress);

                $sql5 = "SELECT * FROM allowedips WHERE a_ip = ? OR a_ip = ?";
                $query5 = $CI->db->query($sql5, array($ipaddress, $range2));
                
                if ($query5->num_rows() > 0)
                    return TRUE;
                else
                    exit;
            }
            
            if (filter_var($ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                $sql6 = "SELECT * FROM allowedips WHERE a_ip = ?";
                $query6 = $CI->db->query($sql6, $ipaddress);
                
                if ($query6->num_rows() == 1)
                    return TRUE;
                else
                    exit;
            }
        }
    }
    
    
}