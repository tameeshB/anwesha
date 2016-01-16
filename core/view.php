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
	<title>Anwesha</title>
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>

 		<script type="text/javascript"> 
		$(document).ready( function () {
    	$('#table_id').DataTable();
		} );

	</script>
</head>
<body>
<table id="table_id" class="display">
<?php 

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['eveId']) && !empty($_GET['eveId']) && preg_match('/[0-9]{1,3}/',$_GET['eveId'])){} 
else {
	die();
}
require '../dbConnection.php';
$conn = mysqli_connect(SERVER_ADDRESS,USER_NAME,PASSWORD,DATABASE);
$sql = 'select * from Events where Events.eveId = ' . $_GET['eveId'] . ';';
$result = mysqli_query($conn,$sql);
$data = mysqli_fetch_assoc($result);
$size = $data['size'];
$name = $data['eveName'];
if($size == '0'){
	die();
} elseif($size =='1'){
	?> 
	<thead>
	<tr><th>ID</th><th>Name</th><th>College</th><th>Sex</th><th>mobile</th><th>email</th><th>DOB</th><th>city</th><th>id confirmation</th></tr>
	</thead>
	<tbody>
<?php
	$sql = 	'SELECT People.name, People.pId, People.college, People.sex, People.mobile, People.email, People.dob, People.city, People.confirm FROM Registration INNER JOIN People ON People.pId = Registration.pId AND Registration.eveId = '.$_GET['eveId'] . ';';
	$result = mysqli_query($conn,$sql);
	// var_dump($sql);
	// var_dump($result);
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr><td>' . $row['pId'] . '</td><td>' . $row['name'] . '</td><td>' . $row['college'] . '</td><td>' . $row['sex'] . '</td><td>' . $row['mobile'] . '</td><td>' . $row['email'] . '</td><td>' . $row['dob'] . '</td><td>' . $row['city'] . '</td><td>' .  $row['confirm'] . '</td></tr>'; 
	}
	mysqli_close($conn);
} else {
	?>
	<thead>
	<tr><th>ID</th><th>Group</th><th>Name</th><th>College</th><th>Sex</th><th>mobile</th><th>email</th><th>DOB</th><th>city</th><th>id confirmation</th></tr> 
	</thead>
	<tbody>
<?php
	$sql = 'SELECT People.name, People.pId, People.college, People.sex, People.mobile, People.email, People.dob, People.city, People.confirm, GroupRegistration.grpName FROM Registration INNER JOIN People ON People.pId = Registration.pId AND Registration.eveId = ' . $_GET['eveId'] .' INNER JOIN GroupRegistration ON Registration.grpId = GroupRegistration.grpId ORDER by GroupRegistration.grpName;';
	$result = mysqli_query($conn,$sql);
	while($row = mysqli_fetch_assoc($result)){
		echo '<tr><td>' . $row['pId'] . '</td><td>' . $row['grpName']. '</td><td>' . $row['name'] . '</td><td>' . $row['college'] . '</td><td>' . $row['sex'] . '</td><td>' . $row['mobile'] . '</td><td>' . $row['email'] . '</td><td>' . $row['dob'] . '</td><td>' . $row['city'] . '</td><td>' .  $row['confirm'] . '</td></tr>'; 
	}
	mysqli_close($conn);
}
?>
</tbody>
</table>
</body>
</html>