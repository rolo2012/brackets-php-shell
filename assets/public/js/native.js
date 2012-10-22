/*jslint vars: true, plusplus: true, devel: true, nomen: true, indent: 4, maxerr: 50 */
/*global $,document,window,navigator */

/**
 * NAT
 * Redefined in app shell 
 */
var NAT;
if (!NAT) {
    NAT = {};
}

NAT.NO_ERROR                    = 0;
NAT.ERR_UNKNOWN                 = 1;
NAT.ERR_INVALID_PARAMS          = 2;
NAT.ERR_NOT_FOUND               = 3;
NAT.ERR_CANT_READ               = 4;
NAT.ERR_UNSUPPORTED_ENCODING    = 5;
NAT.ERR_CANT_WRITE              = 6;
NAT.ERR_OUT_OF_SPACE            = 7;
NAT.ERR_NOT_FILE                = 8;
NAT.ERR_NOT_DIRECTORY           = 9;
NAT.ERR_FILE_EXISTS             = 10;


/**
 * Call a function from other scope like window.opener
 */
NAT.call_callback = function (callback) {
    "use strict";
    callback();
};

/**
 * Bridge to php server ajax_api controller
 * @param func_name Name of the funcion in ajax_api.php
 * @param params array of parameters to the php function or a parameter if only one
 * @param callback funtion(data)  data is object whit 2 fields data.error and data.data
 */
NAT.ajaxCall = function (func_name, params, callback) {
    "use strict";
    $.ajax({
        url: NAT.AJAX_URL + "/" + func_name,
        cache: false,
        dataType: "json",
        type: 'POST',
        data: {
            params: params
        },
        success: function (data) {
            callback(data);
        },
        error: function () {
            alert("NET ERROR");
        }
    });
};
/**
 * Display the OS File Open dialog, allowing the user to select
 * files or directories.
 *
 * @param {boolean} allowMultipleSelection If true, multiple files/directories can be selected.
 * @param {boolean} chooseDirectory If true, only directories can be selected. If false, only 
 *        files can be selected.
 * @param {string} title Tile of the open dialog.
 * @param {string} initialPath Initial path to display in the dialog. Pass NULL or "" to 
 *        display the last path chosen.
 * @param {Array.<string>} fileTypes Array of strings specifying the selectable file extensions. 
 *        These strings should not contain '.'. This parameter is ignored when 
 *        chooseDirectory=true.
 * @param {function(err, selection)} callback Asynchronous callback function. The callback gets two arguments 
 *        (err, selection) where selection is an array of the names of the selected files.
 *        Possible error values:
 *          NO_ERROR
 *          ERR_INVALID_PARAMS
 *
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */

function ShowOpenDialog(callback, allowMultipleSelection, chooseDirectory, title, initialPath, fileTypes) {
    "use strict";
    var win;
    var dir_bit=chooseDirectory?"/1":'';
    win = window.open(NAT.MAIN_CC_URL + "/open_dialog"+dir_bit, title, "width=800,height=600,menubar=no,location=no,resizable=yes,scrollbars=no,status=no");
    $(win).on('load', function () {
        win.OpenDlg.callback=callback;
    });
}


/**
 * Reads the contents of a directory. 
 *
 * @param {string} path The path of the directory to read.
 * @param {function(err, files)} callback Asynchronous callback function. The callback gets two arguments 
 *        (err, files) where files is an array of the names of the files
 *        in the directory excluding '.' and '..'.
 *        Possible error values:
 *          NO_ERROR
 *          ERR_UNKNOWN
 *          ERR_INVALID_PARAMS
 *          ERR_NOT_FOUND
 *          ERR_CANT_READ
 *                 
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */

function ReadDir(callback, path) {
    "use strict";
    NAT.ajaxCall('read_dir', path, function (data) {
        if (!data.data) {
            callback(data.error);
            return;
        }
        callback(data.error, data.data);
        
    });
}


/**
 * Create a new directory.
 *
 * @param {string} path The path of the directory to create.
 * @param {number} mode The permissions for the directory, in numeric format (ie 0777)
 * @param {function(err)} callback Asynchronous callback function. The callback gets one argument.
 *
 * @return None. This is an asynchronous call that sends all return information to the callback.
 **/

function MakeDir(callback, path, mode) {
    "use strict";
    NAT.ajaxCall('read_dir', [path, mode], function (data) {
        callback(data.error);
    });
}

/**
 * Rename a file or directory.
 *
 * @param {string} oldPath The old name of the file or directory.
 * @param {string} newPath The new name of the file or directory.
 * @param {function(err)} callback Asynchronous callback function. The callback gets one argument.
 *
 * @return None. This is an asynchronous call that sends all return information to the callback.
 **/
function Rename(callback, oldPath, newPath) {
    "use strict";
    NAT.ajaxCall('rename', [oldPath, newPath], function (data) {
        callback(data.error);
    });
}

/**
 * Get information for the selected file or directory.
 *
 * @param {string} path The path of the file or directory to read.
 * @param {function(err, modtime ,isDir)} callback Asynchronous callback function. The callback gets 3 
 *        arguments 
 *        (err, modtime,isDir) where modtime is in seconds
 *        Possible error values:
 *          NO_ERROR
 *          ERR_UNKNOWN
 *          ERR_INVALID_PARAMS
 *          ERR_NOT_FOUND
 *                 
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */
function GetFileModificationTime(callback, path) {
    "use strict";
    NAT.ajaxCall('get_file_modification_time', path, function (data) {
        if (!data.data) {
            callback(data.error);
            return;
        }
        callback(data.error, data.data.modtime, data.data.isDir);
        
    });
}
/**
 * Quits native shell application
 */
function QuitApplication() {
    "use strict";
}
/**
 * Abort a quit operation
 */
function AbortQuit() {
    "use strict";
}

/**
 * Invokes developer tools application
 */
function ShowDeveloperTools() {
    "use strict";
    /**
     *@todo Simulate F12 in mozilla 
     */
}

/**
 * Reads the entire contents of a file. 
 *
 * @param {string} path The path of the file to read.
 * @param {string} encoding The encoding for the file. The only supported encoding is 'utf8'.
 * @param {function(err, data)} callback Asynchronous callback function. The callback gets two arguments 
 *        (err, data) where data is the contents of the file.
 *        Possible error values:
 *          NO_ERROR
 *          ERR_UNKNOWN
 *          ERR_INVALID_PARAMS
 *          ERR_NOT_FOUND
 *          ERR_CANT_READ
 *          ERR_UNSUPPORTED_ENCODING
 *                 
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */
function ReadFile(callback, path, encoding) {
    "use strict";
    NAT.ajaxCall('read_file', [path, encoding], function (data) {
        if (!data.data) {
            callback(data.error);
            return;
        }
        callback(data.error, data.data);
    });
}

/**
 * Write data to a file, replacing the file if it already exists. 
 *
 * @param {string} path The path of the file to write.
 * @param {string} data The data to write to the file.
 * @param {string} encoding The encoding for the file. The only supported encoding is 'utf8'.
 * @param {function(err)} callback Asynchronous callback function. The callback gets one argument (err).
 *        Possible error values:
 *          NO_ERROR
 *          ERR_UNKNOWN
 *          ERR_INVALID_PARAMS
 *          ERR_UNSUPPORTED_ENCODING
 *          ERR_CANT_WRITE
 *          ERR_OUT_OF_SPACE
 *                 
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */
function WriteFile(callback, path, data, encoding) {
    "use strict";
    NAT.ajaxCall('write_file', [path, data, encoding], function (data) {
        callback(data.error);
    });
}

/**
 * Set permissions for a file or directory.
 *
 * @param {string} path The path of the file or directory
 * @param {number} mode The permissions for the file or directory, in numeric format (ie 0777)
 * @param {function(err)} callback Asynchronous callback function. The callback gets one argument (err).
 *        Possible error values:
 *          NO_ERROR
 *          ERR_UNKNOWN
 *          ERR_INVALID_PARAMS
 *          ERR_CANT_WRITE
 *
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */
function SetPosixPermissions(callback, path, mode) {
    "use strict";
    //alert("SetPosixPermissions");
}

/**
 * Delete a file.
 *
 * @param {string} path The path of the file to delete
 * @param {function(err)} callback Asynchronous callback function. The callback gets one argument (err).
 *        Possible error values:
 *          NO_ERROR
 *          ERR_UNKNOWN
 *          ERR_INVALID_PARAMS
 *          ERR_NOT_FOUND
 *          ERR_NOT_FILE
 *
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */
function DeleteFileOrDirectory(callback, path) {
    "use strict";
}
/**
 * Return the number of milliseconds that have elapsed since the application
 * was launched. 
 */
function GetElapsedMilliseconds() {
    "use strict";
    if (!NAT.start_at) {
        NAT.start_at = (new Date()).getTime();
    }
    return (new Date()).getTime() - NAT.start_at;
}

/**
 * Open the live browser
 *
 * @param {string} url
 * @param {boolean} enableRemoteDebugging
 * @param {function(err)} callback Asynchronous callback function with one argument (the error)
 *        Possible error values:
 *          NO_ERROR
 *          ERR_INVALID_PARAMS - invalid parameters
 *          ERR_UNKNOWN - unable to launch the browser
 *          ERR_NOT_FOUND - unable to find a browers to launch
 *
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */
function OpenLiveBrowser(callback, url, enableRemoteDebugging) {
    "use strict";
    callback(NAT.ERR_UNKNOWN);
}

/**
 * Attempts to close the live browser. The browser can still give the user a chance to override
 * the close attempt if there is a page with unsaved changes. This function will fire the
 * callback when the browser is closed (No_ERROR) or after a three minute timeout (ERR_UNKNOWN). 
 *
 * @param {function(err)} callback Asynchronous callback function with one argument (the error)
 *        Possible error values:
 *          NO_ERROR (all windows are closed by the time the callback is fired)
 *          ERR_UNKNOWN - windows are currently open, though the user may be getting prompted by the 
 *                      browser to close them
 *
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */

function CloseLiveBrowser(callback) {
    "use strict";
    callback(NAT.ERR_UNKNOWN);
}
/**
 * Open a URL in the default OS browser window. 
 *
 * @param {function(err)} callback Asynchronous callback function with one argument (the error)
 * @param {string} url URL to open in the browser.
 *
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */
function OpenURLInDefaultBrowser(callback, url) {
    "use strict";
    window.open(url);
    callback(NAT.NO_ERROR);
}

/**
 * Return the user's language per operating system preferences.
 */
function GetCurrentLanguage() {
    "use strict";
    return navigator.language;
}
/**
 * Open the extensions folder in an OS file window.
 *
 * @param {string} appURL URL of the index.html file for the application
 * @param {function(err)} callback Asynchronous callback function with one argument (the error)
 *
 * @return None. This is an asynchronous call that sends all return information to the callback.
 */

function ShowExtensionsFolder(callback, appURL) {
    "use strict";
    callback(NAT.NO_ERROR);
}



/**
 * FILE ERROR FIX
 * 
 */
var FileError;
var DOMError;
if(!FileError){    
    if(DOMError){
        FileError=DOMError;
    }
}