<?php
session_start();
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where email = '".$email."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function signup(){
		extract($_POST);
		$data = " firstname = '$firstname' ";
		$data .= ", lastname = '$lastname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		$data .= ", email = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where email = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$login = $this->login();
			if($login == 1)
			return 1;
		}
	}

	function save_upload(){
		extract($_POST);
		$ids= array();
		for($i = 0 ; $i< count($img);$i++){
			$img[$i]= str_replace('data:image/jpeg;base64,', '', $img[$i] );
			$img[$i] = base64_decode($img[$i]);
			$fname = strtotime(date('Y-m-d H:i'))."_".$imgName[$i];
			$upload = file_put_contents("assets/img/uploads/".$fname,$img[$i]);
			$data = " file_path = 'img/uploads/".$fname."' ";
			$data .= ", user_id = '".$_SESSION['login_id']."' ";
			$save[] = $this->db->query("INSERT INTO file_uploads set".$data);
			$ids[] = $this->db->insert_id;
		}
		if(isset($save)){
			if($type == 1){
				$data = " user_id = '".$_SESSION['login_id']."' ";
				$data .= ", content = '$content' ";
				$data .= ", file_ids = '".implode(",",$ids)."' ";
				$save = $this->db->query("INSERT INTO posts set".$data);
			}
			return 1;
		}
	}
	function save_comment(){
		extract($_POST);

		$data = " post_id = $post_id ";
		$data .= ", user_id = ".$_SESSION['login_id']." ";
		$data .= ", comment = '$comment' ";

		$save = $this->db->query("INSERT INTO comments set ".$data);
		if($save){
			$id = $this->db->insert_id;
			$data = $this->db->query("SELECT c.*,concat(u.firstname,' ',u.middlename,' ',u.lastname) as uname FROM comments c inner join users u on u.id = c.user_id where c.id = $id ")->fetch_array();
			foreach($data as $k=>$v){
				if(!is_numeric($k))
				$c[$k] = $v;
			}
			$d['user'] = ucwords($c['uname']);
			$d['comment'] = $comment;
			$d['date'] = date("M d,Y",strtotime($c['date_created']));
			return json_encode(array("status"=>1,"data"=>$d));
		}
	}
	function save_posts(){
		extract($_POST);

		$data = " user_id = ".$_SESSION['login_id']." ";
		$data .= ", content = '$content' ";
		$data .= ", file_ids = '$file_id' ";

		$save = $this->db->query("INSERT INTO posts set ".$data);
		if($save){
			return 1;
		}
	}
}