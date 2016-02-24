<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Template
 * is a custom class created to easily print data on screen
 */

Class Template {
    
    
    
    public static function printHeader_($jquery="") {
        $args = array( "jquery" => $jquery);
        $CI =& get_instance();
        $CI->load->view("includes/header", $args);
    }
    
    public static function printTopMenu_($current) {
        $args = array("current" => $current);
        $CI =& get_instance();
        $CI->load->view("includes/topmenu", $args);
    }
    
    public static function printSideBar_($sel="") {
        $args = array("sel" => $sel);
        $CI =& get_instance();
        $CI->load->view("includes/sidebar", $args);
    }
    
    public static function printFooter_() {
        $CI =& get_instance();
        $CI->load->view("includes/footer");
    }
}
