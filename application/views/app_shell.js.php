<?php
$app_shell=file_get_contents('assets/js/appshell_extensions.js');
header("Content-Type: application/javascript");
die(str_replace('native', '//native', $app_shell));