<?php
include 'dbcon.php';

// header for javascript 
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

$response = array();

// action for inserting of data / create

if ($action === 'create') {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];

	$sql = "INSERT INTO users (`name`,`email`,`phone`) VALUES ('$name','$email','$phone')";
	$result = $conn->query($sql);

	if ($result === TRUE) {
		$response['status'] = 1;
		$response['msg'] = "Successfully Added New User: ".$name;
	}else{
		$response['status'] = 0;
		$response['msg'] = 'Something went wrong. '.$conn->error;
	}
	echo json_encode($response);
	exit;
}
// if condition for read_all
	if ($action === 'read_all') {
		$sql = "SELECT * FROM users";
		$result = $conn->query($sql);

		$users = array();
		if ($result &&$result->num_rows > 0) {
			while($row =$result->fetch_assoc()){
				$users[] =$row;
			}
		}

		$response['status'] = 'success';
		$response['data'] =$users;
		echo json_encode($response);
		exit;
	}

	//read one data by id
if ($action === 'read_one') {
	$id = (int)($_GET['id'] ?? 0);

	$sql = "SELECT * FROM users WHERE user_id=$id";
	$result = $conn->query($sql);

	$user = $result ? $result->fetch_assoc() : null;

	if ($user) {$response['status'] = 'success';
		$response['data'] =$user;
	}else{
		$response['status'] = 'error';$response['msg'] = 'User not found';
	}

	echo json_encode($response);
	exit;
}

// UPDATE
if ($action === 'update') {
	$id = (int)($_POST['user_id'] ?? 0);
	$name =$_POST['name'] ?? '';
	$email =$_POST['email'] ?? '';
	$phone =$_POST['phone'] ?? '';

	if (empty($id) || empty($name) || empty($email) || empty($phone)) {
		$response['status'] = 'error';
		$response['msg'] = 'All fields are required';
	} else {
		$sql = "UPDATE users SET name = '$name', email = '$email', phone = '$phone' WHERE user_id =$id";

		if ($conn->query($sql) === TRUE) {
			$response['status'] = 1;
			$response['msg'] = 'User updated successfully!';
		} else {
			$response['status'] = 0;
			$response['msg'] = 'Failed to update user: ' .$conn->error;
		}
	}
	echo json_encode($response);
	exit;
}

// DELETE
if ($action === 'delete') {
	$id = (int)($_POST['id'] ?? 0);

	$sql = "DELETE FROM users WHERE user_id = $id";

	if ($conn->query($sql) === TRUE) {
		$response['status'] = 'success';
		$response['msg'] = 'User deleted successfully!';
	} else {
		$response['status'] = 'error';
		$response['msg'] = 'Failed to delete user: ' .$conn->error;
	}
	echo json_encode($response);
	exit;
}


