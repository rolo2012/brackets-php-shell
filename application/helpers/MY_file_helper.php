<?php

/**
 * Get Filenames top
 *
 * Reads the specified directory and builds an array containing the filenames.
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	bool	whether to include the path as part of the filename
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */
if (!function_exists('get_filenames_top')) {

    function get_filenames_top($source_dir, $include_path = FALSE) {
        static $_filedata = array();

        if ($fp = @opendir($source_dir)) {
            $_filedata = array();
            $source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            while (FALSE !== ($file = readdir($fp))) {
                if (strncmp($file, '.', 1) !== 0) {
                    $_filedata[] = ($include_path == TRUE) ? $source_dir . $file : $file;
                }
            }
            return $_filedata;
        } else {
            return FALSE;
        }
    }

}