<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();

if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'signup'){
	$login = $crud->signup();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'save_upload'){
	$save = $crud->save_upload();
	if($save)
		echo $save;
}
if($action == 'save_comment'){
	$save = $crud->save_comment();
	if($save)
		echo $save;
}
if($action == 'save_posts'){
	$save = $crud->save_posts();
	if($save)
		echo $save;
}