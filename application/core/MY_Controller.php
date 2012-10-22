<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Brakcets PHP Shell
 *
 * A shell for Brakcets for host in a webserver made in PHP 
 * Copyright (C) 2012  Rolando Corratge Nieves. 
 * @package    	Brakcets PHP Shell
 * @author     	Rolando Corratge Nieves
 */
/* ----------------------------------------------------
 * Description: 
 */
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
/*
    function get_ajax_site_url() {
       die(json_encode(array('url'=>site_url('ajax_api'))));
    }*/

}

/* End of file main.php */
/* Location: ./application/controllers/main.php */