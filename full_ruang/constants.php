<?php 
error_reporting(E_ALL);
include("config.php");
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 
    $protocol = 'https';
} else { 
    $protocol = 'http';
}
$cur_dirname = basename(__DIR__);
if($cur_dirname=='public_html'){
	$cur_dirname='';
}
if($cur_dirname=='' || $cur_dirname=='/'){
	$cur_dir = $_SERVER['REQUEST_URI']."/";
}else{
	$cur_dir = substr($_SERVER['REQUEST_URI'], 0, @strpos($_SERVER['REQUEST_URI'], $cur_dirname)).$cur_dirname."/";
}
if($cur_dir==$cur_dirname.'/'){
	$cur_dir = '/';	
}
$dots = explode(".",$_SERVER['HTTP_HOST']);
if(sizeof($dots)>2 && $dots[0]!='www' && strlen($dots[1])>3){
	define("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"].'/');
	define("BASE_URL", '');
	define("SITE_URL",$protocol.'://'.$_SERVER['HTTP_HOST'].'/');
	define("AJAX_URL",$protocol.'://'.$_SERVER['HTTP_HOST'].'/includes/lib/');
}else{
	define("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"] .$cur_dir);
	define("BASE_URL", substr($cur_dir,0,-1));
	define("SITE_URL",$protocol.'://'.$_SERVER['HTTP_HOST'].$cur_dir);
	define("AJAX_URL",$protocol.'://'.$_SERVER['HTTP_HOST'].$cur_dir.'includes/lib/');
}