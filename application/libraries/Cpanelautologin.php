<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Cpanelautologin {
    // The available services with their HTTPS ports
    private static $servicePorts = array('cpanel' => 2083, 'whm' => 2087, 'webmail' => 2096);
    
    public static function getLoggedInUrl($user, $pass, $hostname, $service, $goto = '/') {
        $CI =& get_instance();
        $CI->db->cache_delete_all();
        
        // If no valid service has been given, default to cPanel
        $port = isset(self::$servicePorts[$service]) ? self::$servicePorts[$service] : 2083;
        $ch = curl_init();
        $fields = array('user' => $user, 'pass' => $pass, 'goto_uri' => $goto);
        // Sets the POST URL to something like: https://example.com:2083/login/
        curl_setopt($ch, CURLOPT_URL, 'https://' . $hostname . ':' . $port . '/login/');
        curl_setopt($ch, CURLOPT_POST, true);
        // Turn our array of fields into a url encoded query string i.e.: ?user=foo&pass=bar
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // RFC 2616 14.10 compliance
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection' => 'close'));
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Execute POST query returning both the response headers and content into $page
        $page = curl_exec($ch);
        
        
        curl_close($ch);
        $session = $token = array();
        // Find the session cookie in the page headers
        if(!preg_match('/session=([^\;]+)/', $page, $session)) {
            // This will also fail if the login authentication failed. No need to explicitly check for it.
            return false;
        }
        // Find the cPanel session token in the page content
        if(!preg_match('|<META HTTP-EQUIV="refresh"[^>]+URL=/(cpsess\d+)/|i', $page, $token)) {
            return false;
        }
        // Append the goto_uri to the query string if it's been manually set
        $extra = $goto == '/' ? '' : '&goto_uri=' . urlencode($goto);
        return 'https://' . $hostname . ':' . $port . '/' . $token[1] . '/login/?session=' . $session[1] . $extra;
        
        
    }
}




