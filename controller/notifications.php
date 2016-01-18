<?php 
require('dbConnection.php');

$conn = mysqli_connect(SERVER_ADDRESS,USER_NAME,PASSWORD,DATABASE);

if(strlen($match[1])<12){
	$time = $match[1];
	$sql = "SELECT * FROM `notification` WHERE notification.time > $time order by notification.time;";
	$result = mysqli_query($conn, $sql);
	if(!$result){
		header('Content-type: application/json');
		echo json_encode(array('status' => false, 'msgs' => 'mysql error'));
		mysqli_close($conn);
		die();
	}
	$row = array();
	while($r = mysqli_fetch_assoc($result)){
		$row[] = $r;
	}
	header('Content-type: application/json');
	echo json_encode(array('status' => true, 'msgs' => $row));
	mysqli_close($conn);
	die();
} else {
	header('Content-type: application/json');
	echo json_encode(array('status' => false, 'msgs' => 'time out of bounds'));
	die();
}
?>