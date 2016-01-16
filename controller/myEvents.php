<?php 

require('model/model.php');
require('dbConnection.php');

$conn = mysqli_connect(SERVER_ADDRESS,USER_NAME,PASSWORD,DATABASE);
require('middleware/authMiddleware.php');
$userID = $_SESSION['userID'];
$result = People::getMyEvents($userID,$conn);
header('Content-type: application/json');
echo json_encode($result);
?>