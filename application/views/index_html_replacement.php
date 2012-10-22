<?
/**
 * Brakcets PHP Shell
 *
 * A shell for Brakcets for host in a webserver made in PHP 
 *
 * Copyright (C) 2012  Rolando Corratge Nieves. 
 *
 * LICENSE
 *
 * Brakcets PHP Shell is released with dual licensing, using the GPL v3 (license-gpl3.txt) and the MIT license (license-mit.txt).
 * You don't have to do anything special to choose one license or the other and you don't have to notify anyone which license you are using.
 * Please see the corresponding license file for details of these licenses.
 * You are free to use, modify and distribute this software, but all copyright information must remain.
 *
 * @package    	Brakcets PHP Shell
 * @copyright  	Copyright (c)2012, Rolando Corratge Nieves
 * @license    	GPL v3 or MIT @todo url
 * @version    	0.1
 * @author     	Rolando Corratge Nieves
 */
/*----------------------------------------------------
 * Description:
 * A replacement of the default index.html of brackets 
 * Inject some extra script and css to work in a browser
 * every where you see php tags 
 * for a diferent version of index.html you can change it 
 * and insert the injects in the same order
 */
?>
<?$res_folder=base_url("assets/public/");?>
<!doctype html>

<!-- 
  Copyright (c) 2012 Adobe Systems Incorporated. All rights reserved.
   
  Permission is hereby granted, free of charge, to any person obtaining a
  copy of this software and associated documentation files (the "Software"), 
  to deal in the Software without restriction, including without limitation 
  the rights to use, copy, modify, merge, publish, distribute, sublicense, 
  and/or sell copies of the Software, and to permit persons to whom the 
  Software is furnished to do so, subject to the following conditions:
   
  The above copyright notice and this permission notice shall be included in
  all copies or substantial portions of the Software.
   
  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
  FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
  DEALINGS IN THE SOFTWARE.
-->

<html>
<head>
    <meta charset="utf-8">
    <title></title> 
    
    <link rel="shortcut icon" href="<?=$res_folder?>/images/favicon.ico" type="image/x-icon"/>
    <base href = '<?=base_url("adobe_brackets/src/")?>/'/>
    <!-- CSS/LESS -->

    <!-- CSS for CodeMirror search support, currently for debugging only -->
    <link rel="stylesheet" href="thirdparty/CodeMirror2/lib/util/dialog.css">
    <link rel="stylesheet" href="<?=$res_folder?>/css/loader.css"/>

    <!-- Temporary CSS for unobtrusive scrollbars. This can't live in LESS because it uses
         nonstandard WebKit-specific syntax. -->
    <link rel="stylesheet" href="styles/quiet-scrollbars.css">

    <!-- TODO (Issue #278): switch between runtime LESS compilation in dev mode and precompiled version -->
    <link rel="stylesheet/less" href="styles/brackets.less">
    <script src="thirdparty/less-1.3.0.min.js"></script>
    <script src="thirdparty/mustache/mustache.js"></script>
    <!-- <link rel="stylesheet" href="brackets.min.css"> -->

    <!-- JavaScript -->

    <!-- Pre-load third party scripts that cannot be async loaded. -->
    <script src="thirdparty/jquery-1.7.min.js"></script>        
    <script src="<?=site_url('main/path_config_js')?>"></script><?/*dirs*/?>
    <script src="<?=$res_folder?>/js/native.js"></script><?/*FOR NATIVE CALL IMPLEMENTED IN AJAX*/?>
    <script src="<?=site_url('main/app_shell_js')?>"></script>
    <script src="<?=$res_folder?>/js/fixes.js"></script>
    
    <script src="thirdparty/CodeMirror2/lib/codemirror.js"></script>
    
    <!-- JS for CodeMirror search support, currently for debugging only -->
    <script src="thirdparty/CodeMirror2/lib/util/dialog.js"></script>
    <script src="thirdparty/CodeMirror2/lib/util/searchcursor.js"></script>
    <script src="thirdparty/CodeMirror2/lib/util/search.js"></script>
    <script src="thirdparty/CodeMirror2/lib/util/closetag.js"></script>    
</head>
<body>

    <!-- HTML content is dynamically loaded and rendered by brackets.js.
         Any modules that depend on or modify HTML during load should 
         require the "utils/AppInit" module and install a callback for
         "htmlReady" (e.g. AppInit.htmlReady(handler)) before touching the DOM.
    -->
    
    <!-- All other scripts are loaded through require. -->
    <script src="thirdparty/require.js" data-main="brackets"></script>        
</body>
</html>
