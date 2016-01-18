<?php
$user='CA_2015_CA';
$pass='fcamp%us$$Ambes&2015';
if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) || ($_SERVER['PHP_AUTH_USER']!=$user) || ($_SERVER['PHP_AUTH_PW']!=$pass))
{
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Basic realm="Campus Princess 2015 Anwesha Auditions"');
    exit('Authorization failed.');
}
?><?php 
require '../dbConnection.php';
$conn = mysqli_connect(SERVER_ADDRESS,USER_NAME,PASSWORD,DATABASE);
$sql = "SELECT `eveId`, `eveName` from Events WHERE size != 0 order by code";
$result = mysqli_query($conn,$sql);
// $events = mysqli_fetch_array($result);
$rows = mysqli_num_rows($result);
mysqli_close($conn);
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Core Committee</title>
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
<table id="table_id" class="display">
<thead>
	<tr>
		<th>eveId</th><th>Name</th>
	</tr>
</thead>
<tbody>
<?php 
while ($row = mysqli_fetch_assoc($result)) {
	echo '<tr> <td>' . $row['eveId'] . '</td>'.'<td>' .'<a href="view.php?eveId=' . $row['eveId'] . '">'. $row['eveName'] . '</a></td></tr>';
}
 ?>
</tbody>
</table>
<br><br><br><br><br>
<strong>Click <a href="unRegistered.php">Here</a> to view Unregistered People</strong>
</body>
</html>