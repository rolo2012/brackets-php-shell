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

define("NO_ERROR", 0);
/**
 * @constant Unknown error occurred.
 */
define("ERR_UNKNOWN", 1);

/**
 * @constant Invalid parameters passed to function.
 */
define("ERR_INVALID_PARAMS", 2);

/**
 * @constant File or directory was not found.
 */
define("ERR_NOT_FOUND", 3);

/**
 * @constant File or directory could not be read.
 */
define("ERR_CANT_READ", 4);

/**
 * @constant An unsupported encoding value was specified.
 */
define("ERR_UNSUPPORTED_ENCODING", 5);

/**
 * @constant File could not be written.
 */
define("ERR_CANT_WRITE", 6);

/**
 * @constant Target directory is out of space. File could not be written.
 */
define("ERR_OUT_OF_SPACE", 7);

/**
 * @constant Specified path does not point to a file.
 */
define("ERR_NOT_FILE", 8);

/**
 * @constant Specified path does not point to a directory.
 */
define("ERR_NOT_DIRECTORY", 9);

/**
 * @constant Specified file already exists.
 */
define("ERR_FILE_EXISTS", 10);

class Ajax_api extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
        $this->load->helper('directory');
    }
   
    protected function fixSamplePath(&$path) {
        $sample_path = "/samples/";
        if (substr($path, 0, strlen($sample_path)) === $sample_path) {
            $path = FCPATH . "/adobe_brackets/" . $path;
            return;
        }
        $base_name = basename(FCPATH);
        $adobe_path = "/$base_name/../";
        if (substr($path, 0, strlen($adobe_path)) === $adobe_path) {
            $path = FCPATH . "adobe_brackets/" . substr($path, strlen($adobe_path));
        }

        $extensions_path = "/$base_name/extensions/";
        if (substr($path, 0, strlen($extensions_path)) === $extensions_path) {
            $path = FCPATH . "adobe_brackets/src/extensions/" . substr($path, strlen($extensions_path));
            return;
        }
    }

    /**
     * @param path
     */
    function read_dir() {
        $path = $this->get_params();
        /**
         * @todo remove this fix and implement correctly
         *  this fix is for get the /samples/ folder
         */
        $this->fixSamplePath($path);

        $this->checkFile($path, true);

        $files = directory_map($path, 1, TRUE);

        if ($files === FALSE) {
            $this->ajax_return(ERR_UNKNOWN);
        }

        $this->ajax_return(NO_ERROR, $files);
    }
    /**
     * @param path
     */


    /**
     * @param path
     */
    function make_dir() {
        $params = $this->get_params(2);
        $path = $params[0];
        /**
         * @todo remove this fix and implement correctly
         *  this fix is for get the /samples/ folder
         */
        $this->fixSamplePath($path);

        $mode = octdec($params[1]);

        $this->checkCreateFile($path);

        if (!@mkdir($path, $mode)) {
            $this->ajax_return(ERR_CANT_WRITE);
        }
        $this->ajax_return(NO_ERROR);
    }

    function rename() {
        $params = $this->get_params(2);
        $oldpath = $params[0];
        $newpath = $params[1];
        /**
         * @todo remove this fix and implement correctly
         *  this fix is for get the /samples/ folder
         */
        $this->fixSamplePath($oldpath);
        $this->fixSamplePath($newpath);


        $this->checkFileExist($oldpath);
        $this->checkFileNoExist($newpath);
        if (!@rename($oldpath, $newpath)) {
            $this->ajax_return(ERR_CANT_WRITE);
        }
        $this->ajax_return(NO_ERROR);
    }

    function get_file_modification_time() {
        $path = $this->get_params();
        /**
         * @todo remove this fix and implement correctly
         *  this fix is for get the /samples/ folder
         */
        $this->fixSamplePath($path);

        $this->checkFileExist($path);
        $data['isDir'] = is_dir($path);
        $stats = stat($path);
        $data['modtime'] = $stats['mtime'] / 1000;
        $this->ajax_return(NO_ERROR, $data);
    }

    function read_file() {
        $params = $this->get_params(2);
        $path = $params[0];
        /**
         * @todo remove this fix and implement correctly
         *  this fix is for get the /samples/ folder
         */
        $this->fixSamplePath($path);

        $encoding = $params[1];

        /**
         * @todo do something with the encoding
         */
        $this->checkFile($path);
        $file = read_file($path);

        if ($file === FALSE) {
            $this->ajax_return(ERR_CANT_READ);
        }
        /**
         * @todo here is a pach for utf-8 empty files
         * Having correct encode handling this is'nt necessary
         */
        if ($file === "" && $encoding === "utf8") {
            $file = "\xEF\xBB\xBF";
        }
        $this->ajax_return(NO_ERROR, $file);
    }

    function write_file() {

        $params = $this->get_params(3);
        $path = $params[0];
        /**
         * @todo remove this fix and implement correctly
         *  this fix is for get the /samples/ folder
         */
        $this->fixSamplePath($path);

        $data = $params[1];
        $encoding = $params[2];
        /**
         * @todo do something with the encoding         
         */
        if (is_file($path) || is_link($path)) {
            $this->checkFile($path, false, true, false);
        } else {
            $this->checkCreateFile($path);
        }

        if (!write_file($path, $data)) {
            $this->ajax_return(ERR_CANT_WRITE);
        }
        $this->ajax_return(NO_ERROR);
    }

    function delete_file_or_dir() {
        $path = $this->get_params();
        /**
         * @todo remove this fix and implement correctly
         *  this fix is for get the /samples/ folder
         */
        $this->fixSamplePath($path);

        $this->checkFileExist($path);
        if (is_dir($path)) {
            if (!@rmdir($path)) {
                $this->ajax_return(ERR_UNKNOWN);
            }
        } else {
            if (!@unlink($path)) {
                $this->ajax_return(ERR_UNKNOWN);
            }
        }

        $this->ajax_return(NO_ERROR);
    }

    function set_posix_permissions() {
        $params = $this->get_params();
        $path = $params[0];
        /**
         * @todo remove this fix and implement correctly
         *  this fix is for get the /samples/ folder
         */
        $this->fixSamplePath($path);

        $mode = octdec($params[1]);
        $this->checkFileExist($path);
        if (!@chmod($path, $mode)) {
            $this->ajax_return(ERR_CANT_WRITE);
        }
        $this->ajax_return(NO_ERROR);
    }

    protected function ajax_return($error=NO_ERROR, $data=FALSE) {
        $out['error'] = $error;
        if ($data !== FALSE) {
            $out['data'] = $data;
        }
        /**
         * @todo ADD no utf8 support
         */
        die(json_encode($out));
    }

    protected function get_params($nums_params=1) {
        $params = $this->input->get_post('params');
        if (!$params) {
            $this->ajax_return(ERR_INVALID_PARAMS);
        }
        if ($nums_params === 1) {
            if (is_array($params) && isset($params[0])) {
                return $params[0];
            } else {
                return $params;
            }
        }
        if (!is_array($params)) {
            $this->ajax_return(ERR_INVALID_PARAMS);
        } elseif (count($params) < $nums_params) {
            $this->ajax_return(ERR_INVALID_PARAMS);
        }
        return $params;
    }
     /**
     * FOR Opendialog
     */
    function get_top_dirs() {
        
        if (isset($_SERVER['WINDIR']) || isset ($_SERVER['windir'])) {
            $drive_leters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            $drives = array();
            for ($i = 0; $i < strlen($drive_leters); $i++) {
                $drive = $drive_leters[$i] . ":\\";
                if (@is_dir($drive)) {
                    $drives[] = $drive;
                }
            }
            $this->ajax_return(NO_ERROR, $drives);
            var_dump($drives);
        }else{
            $this->ajax_return(NO_ERROR, array('/'));
        }        
    }
    /**
     * FOR Opendialog
     */
    
    function read_dir_dirs() {
        $path = $this->get_params();
        $path =str_replace("\\", "/",$path);
        $path =rtrim($path,"/")."/";
        /**
         * @todo remove this fix and implement correctly
         *  this fix is for get the /samples/ folder
         */
        $this->fixSamplePath($path);

        $this->checkFile($path, true);

        $files = directory_map($path, 1, TRUE);

        if ($files === FALSE) {
            $this->ajax_return(ERR_UNKNOWN);
        }
        $dirs=array();
        foreach ($files as $key => $file) {
            if(@is_dir($path.$file)){
                $dirs[]=$path.$file;
            }
        }
        
        $this->ajax_return(NO_ERROR, $dirs);
    }
   
    function read_dir_files() {
        $path = $this->get_params();
        $path =str_replace("\\", "/",$path);
        $path =rtrim($path,"/");
        /**
         * @todo remove this fix and implement correctly
         *  this fix is for get the /samples/ folder
         */
        $this->fixSamplePath($path);

        $this->checkFile($path, true);

        $files = directory_map($path, 1, TRUE);

        if ($files === FALSE) {
            $this->ajax_return(ERR_UNKNOWN);
        }
        $rtr_files=array();
        foreach ($files as $key => $file) {
            $file_full=$path.'/'.$file;
            if(@is_file($file_full)||@is_link($file_full)){
                $rtr_files[]=$file_full;
            }
        }
        
        $this->ajax_return(NO_ERROR, $rtr_files);
    }
    
    
    /**
     * Validations
     * @param type $path 
     */

    protected function checkCreateFile($path) {
        $dir = dirname($path);
        if (file_exists($path)) {
            $this->ajax_return(ERR_FILE_EXISTS);
        }
        if (!file_exists($dir) || !is_dir($dir)) {
            $this->ajax_return(ERR_NOT_DIRECTORY);
        }
        if (!is_writable($dir)) {
            $this->ajax_return(ERR_CANT_WRITE);
        }
    }

    protected function checkFileExist($path) {
        if (!is_string($path)) {
            $this->ajax_return(ERR_INVALID_PARAMS);
        }

        if (!file_exists($path)) {
            $this->ajax_return(ERR_NOT_FOUND);
        }
    }

    protected function checkFileNoExist($path) {
        if (!is_string($path)) {
            $this->ajax_return(ERR_INVALID_PARAMS);
        }

        if (file_exists($path)) {
            $this->ajax_return(ERR_CANT_WRITE);
        }
    }

    protected function checkFile($path, $is_dir=false, $write=false, $read=true) {
        $this->checkFileExist($path);

        if ($is_dir === true) {
            if (!is_dir($path)) {
                $this->ajax_return(ERR_NOT_DIRECTORY);
            } else {
                if (!is_readable($path) && $read) {
                    $this->ajax_return(ERR_CANT_READ);
                }
            }
        } elseif (!is_file($path) && !is_link($path)) {
            $this->ajax_return(ERR_NOT_FILE);
            if (!is_readable($path) && $read) {
                $this->ajax_return(ERR_CANT_READ);
            }
            if (!is_writable($path) && $write) {
                $this->ajax_return(ERR_CANT_WRITE);
            }
        }
    }

}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
