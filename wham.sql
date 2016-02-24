-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `wham`
--

-- --------------------------------------------------------

--
-- Table structure for table `allowedips`
--

CREATE TABLE `allowedips` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `a_ip` varchar(50) NOT NULL,
  `a_dateofa` int(11) NOT NULL COMMENT 'date of add',
  `a_comment` varchar(40) NOT NULL,
  PRIMARY KEY (`a_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blockedips`
--

CREATE TABLE `blockedips` (
  `b_id` int(11) NOT NULL AUTO_INCREMENT,
  `b_ip` varchar(50) NOT NULL,
  `b_dateofa` int(11) NOT NULL COMMENT 'date of add',
  `b_comment` varchar(40) NOT NULL,
  PRIMARY KEY (`b_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cpanel`
--

CREATE TABLE `cpanel` (
  `server_id` tinyint(4) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `remote_key` text NOT NULL,
  `apis_available` text NOT NULL,
  `account_list_deprecated` longtext NOT NULL,
  `package_list` text NOT NULL,
  `priv_list` text NOT NULL,
  `last_sync` int(11) NOT NULL,
  PRIMARY KEY (`server_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cpanelaccts`
--

CREATE TABLE `cpanelaccts` (
  `server_id` tinyint(4) NOT NULL,
  `user` varchar(15) NOT NULL,
  `domain` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `owner` varchar(15) NOT NULL,
  `plan` varchar(25) NOT NULL,
  `disklimit` varchar(15) NOT NULL,
  `diskused` varchar(15) NOT NULL,
  `suspended` tinyint(4) NOT NULL,
  `suspendreason` varchar(40) NOT NULL,
  KEY `server_id` (`server_id`),
  KEY `user` (`user`),
  KEY `domain` (`domain`),
  KEY `email` (`email`),
  KEY `ip` (`ip`),
  KEY `owner` (`owner`),
  KEY `plan` (`plan`),
  KEY `suspended` (`suspended`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `datacenter`
--

CREATE TABLE `datacenter` (
  `dc_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `dc_name` varchar(30) NOT NULL,
  `dc_websiteurl` varchar(60) NOT NULL,
  `dc_supporturl` varchar(60) NOT NULL,
  `dc_email` varchar(30) NOT NULL,
  `dc_location` varchar(20) NOT NULL,
  `dc_isactive` varchar(1) NOT NULL DEFAULT 'Y',
  `dc_notes` text NOT NULL,
  `dc_dateofc` int(11) NOT NULL COMMENT 'date of creation',
  `dc_dateofm` int(11) NOT NULL COMMENT 'date of modification',
  PRIMARY KEY (`dc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_type` varchar(10) NOT NULL,
  `log_status` tinyint(4) NOT NULL,
  `log_ip` varchar(15) NOT NULL,
  `log_msg` varchar(2048) NOT NULL,
  `log_server` int(11) DEFAULT NULL,
  `log_user` varchar(15) NOT NULL,
  `log_time` int(11) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_user` (`log_user`),
  KEY `log_ip` (`log_ip`),
  KEY `log_status` (`log_status`),
  KEY `log_type` (`log_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `panels`
--

CREATE TABLE `panels` (
  `p_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `p_name` varchar(15) NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(40) NOT NULL,
  `role_priv` text NOT NULL,
  `role_editable` varchar(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `server`
--

CREATE TABLE `server` (
  `s_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(40) NOT NULL,
  `s_hostname` varchar(40) NOT NULL,
  `s_cp` tinyint(4) NOT NULL COMMENT 'control panel',
  `s_ip` varchar(15) NOT NULL,
  `s_rack` varchar(20) NOT NULL,
  `s_dc` tinyint(4) NOT NULL,
  `s_notes` text NOT NULL,
  `s_dateofc` int(11) NOT NULL COMMENT 'date of creation',
  `s_dateofm` int(11) NOT NULL COMMENT 'modify date',
  `s_isactive` varchar(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`s_id`),
  KEY `s_dc` (`s_dc`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `w_option` varchar(25) NOT NULL,
  `w_val` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `u_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `u_name` varchar(15) NOT NULL,
  `u_pass` varchar(80) NOT NULL,
  `u_fullname` varchar(25) NOT NULL,
  `u_roleid` tinyint(4) NOT NULL,
  `s_timezone` varchar(10) DEFAULT NULL,
  `s_daylight` varchar(5) DEFAULT NULL,
  `s_sidebar` varchar(5) DEFAULT NULL,
  `s_sidebarview` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`u_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


INSERT INTO settings (id, w_option, w_val) VALUES
(1, 'adminpassword', 'aHOsVWKgjcJg1YOabaZzr3lfaJiih6lxptlUatCPkVk='),
(2, 'timezone', 'UP55'),
(3, 'daylight', 'FALSE'),
(4, 'allow_priv_reserve_ips', 'FALSE'),
(5, 'show_load_avg', 'FALSE'),
(6, 'whitelist_url', ''),
(7, 'whitelist_passwd', ''),
(8, 'logging', '{"logging":true,"log_all_login_attempts":true,"log_actions":true}'),
(9, 'email_alerts', 'FALSE'),
(10, 'send_email_to', ''),
(11, 'send_email_cc', ''),
(12, 'notify_settings', '{"notify":"failonly","notify_whitelist_from_url":false}'),
(13, 'email_settings', '{"protocol":"mail","smtp_user":"wham@domain"}'),
(14, 'firewall', 'FALSE'),
(15, 'firewall_mode', 'none'),
(16, 'sidebar', 'LEFT'),
(17, 'sidebar_view', 'expand');

INSERT INTO roles (role_id, role_name, role_priv, role_editable) VALUES
(1, 'Full Admin', '{"add_dc":true,"delete_dc":true,"edit_dc":true,"view_dc_note":true,"add_server":true,"delete_server":true,"edit_server":true,"view_server_note":true,"add_account":true,"delete_account":true,"modify_account":true}', 'N'),
(2, 'Data Center Admin', '{"add_dc":true,"delete_dc":true,"edit_dc":true,"view_dc_note":true,"add_server":true,"delete_server":true,"edit_server":true,"view_server_note":true,"add_account":false,"delete_account":false,"modify_account":false}', 'N'),
(3, 'Support Technician', '{"add_dc":false,"delete_dc":false,"edit_dc":false,"view_dc_note":false,"add_server":false,"delete_server":false,"edit_server":false,"view_server_note":false,"add_account":true,"delete_account":true,"modify_account":true}', 'N'),
(4, 'Support Technician - L2', '{"add_dc":false,"delete_dc":false,"edit_dc":false,"view_dc_note":true,"add_server":false,"delete_server":false,"edit_server":false,"view_server_note":true,"add_account":true,"delete_account":true,"modify_account":true}', 'N'),
(5, 'Support Technician - L3', '{"add_dc":false,"delete_dc":false,"edit_dc":true,"view_dc_note":true,"add_server":true,"delete_server":true,"edit_server":true,"view_server_note":true,"add_account":true,"delete_account":true,"modify_account":true}', 'N');

INSERT INTO `panels` VALUES(1, 'CPanel/WHM');
