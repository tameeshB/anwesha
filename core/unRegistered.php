<?php 
$user='CA_2015_CA';
$pass='fcamp%us$$Ambes&2015';
if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || ($_SERVER['PHP_AUTH_USER']!=$user) || ($_SERVER['PHP_AUTH_PW']!=$pass))
{
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="Campus Princess 2015 Anwesha Auditions"');
    exit('Authorization failed.');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Unregistered Anwesha</title>
	<link rel="stylesheet" type="text/css" href="first.css">
	<script src="second.js"></script>
	<script type="text/javascript" charset="utf8" src="third.js"></script>

 		<script type="text/javascript"> 
		$(document).ready( function () {
    	$('#table_id').DataTable();
		} );

	</script>
</head>
<body>
<table  id="table_id" class="display">
	<thead>
		<tr><th>ID</th><th>Name</th><th>College</th><th>Sex</th><th>mobile</th><th>email</th><th>DOB</th><th>city</th><th>EmailConfirm</th><th>time</th><th>totalLogin</th></tr>
	</thead>
	<tbody>
	<?php 
	require '../dbConnection.php';
	$conn = mysqli_connect(SERVER_ADDRESS,USER_NAME,PASSWORD,DATABASE);
	$sql = "Select a.name, a.pId, a.college, a.sex, a.mobile, a.email, a.dob, a.city, a.confirm, a.time, LoginTable.totalLogin from LoginTable INNER JOIN (SELECT * FROM `People` WHERE People.pId not in (SELECT Registration.pId from Registration)) as a ON a.pId = LoginTable.pId ";
	$result = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr><td>' . $row['pId'] . '</td><td>' . $row['name'] . '</td><td>' . $row['college'] . '</td><td>' . $row['sex'] . '</td><td>' . $row['mobile'] . '</td><td>' . $row['email'] . '</td><td>' . $row['dob'] . '</td><td>' . $row['city'] . '</td><td>' .  $row['confirm'] . '</td><td>' . $row['time'] . '</td><td>' . $row['totalLogin']. '</td></tr>'; 
	}
	mysqli_close($conn);
	 ?>
	</tbody>
</table>
</body>
</html>