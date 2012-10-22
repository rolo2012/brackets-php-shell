<? $res_folder = base_url("assets/public/"); ?>
<? $src_folder = base_url("adobe_brackets/src/"); ?>
<? $open_dlg_folder = base_url("assets/open_dialog"); ?>
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

    <link rel="shortcut icon" href="<?= $res_folder ?>/images/favicon.ico" type="image/x-icon"/>    
    <link rel="stylesheet" href="<?= $open_dlg_folder ?>/css/main.css"/>
    <?if ($dir_search):?>
    <link rel="stylesheet" href="<?= $open_dlg_folder ?>/css/directory.css"/>
    <?else:?>
    <link rel="stylesheet" href="<?= $open_dlg_folder ?>/css/file.css"/>
    <?endif;?>    
    

    <?/*<link rel="stylesheet" href="<?= base_url('assets/js_libs/css/chosen/chosen.css') ?>"/>*/?>    
    <!-- JavaScript -->
    <script>
        is_dir_search=<?=$dir_search?"true":"false"?>;
    </script>        
    <script src="<?= $src_folder . config_item('jquery_file') ?>"></script>        
    <script src="<?= $src_folder . config_item('jquery_tree_file') ?>"></script>        
    <?/*<script src="<?= base_url('assets/js_libs/jquery.chosen.js') ?>"></script> */?>
    <script src="<?= site_url('main/path_config_js') ?>"></script><? /* dirs */ ?>
    <script src="<?= $res_folder ?>/js/native.js"></script><? /* FOR NATIVE CALL IMPLEMENTED IN AJAX */ ?>
    <script src="<?= site_url('main/app_shell_js') ?>"></script>

    <script src="<?= $open_dlg_folder ?>/js/open_dialog.js"></script>        
</head>
<body>
    <div id="main-view">
        <?/*<div id="drive_select_div">        
            <label for="drive_select"></label>
            <select name="drive_select" id="drive_select">                   
            </select>
        </div>*/?>
        <div id="directory_tree">
            &nbsp;
        </div>           
        <?if ($dir_search===FALSE):?>
        <div id="file_view">
            &nbsp;
        </div>        
        <?endif;?>
        <div id="filename_div">        
            <div style="display:<?=$dir_search?'none':'inline'?>;" >
                <label for="filename">Name:</label>                  
                <input type="text" id="filename" name="filename">                   
            </div>
            <input type="button"  id="btn_cancel"name="Cancel" value="Cancel">             
            <input type="submit" id="btn_select" name="Select" value="Select">             
        </div>    
    </div>    
</body>
</html>
