<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Brakcets PHP Shell
 *
 * A shell for Brakcets for host in a webserver made in PHP 
 * Copyright (C) 2012  Rolando Corratge Nieves. 
 * @package    	Brakcets PHP Shell
 * @author     	Rolando Corratge Nieves
 */
/*----------------------------------------------------
 * Description: 
 */
class Main extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * The IDE Loader itself
	 */
	public function index()
	{   
            $this->load->view('index_html_replacement');
	}
        
        public function path_config_js(){
            header("Content-Type: application/javascript");
            $this->load->view('path_config_js');
        }
        /**
         * 
         */
        public function open_dialog($dir_search=0){
            $dir_search=$dir_search?TRUE:FALSE;
            $this->load->view('open_dialog',array('dir_search'=>$dir_search));
                          
        }
        /**
	 * Parse appshell_extensions to 
         * remove the native function declaration
         * and output it
         * 
	 * File taken from bracket shell project
	 * 
	 */        
	public function app_shell_js()
	{            
            
            $app_shell=file_get_contents(FCPATH.'adobe_brackets/appshell_extensions.js');
            /**
             * @todo Add the modified date header
             */
            header("Content-Type: application/javascript");
            
            
            $output=str_replace('native', '//native', $app_shell);
            die($output);
	}
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */