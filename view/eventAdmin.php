<?php
// ini_set('display_errors', '1');  

session_start();	
require('model/model.php');
require('dbConnection.php');
if($match[1]=='logout'){
	unset($_SESSION['userID']);
    unset($_SESSION['user_name']);
    header('Location: /eventAdmin');
}
$conn = mysqli_connect(SERVER_ADDRESS,USER_NAME,PASSWORD,DATABASE);
	$purp = 0;
	$arr = array();
	///############# User not logged in
	if(!isset($_SESSION['userID'])){
		$purp = -1;
		$title = "Login first";
		if(isset($_POST['username']) && isset($_POST['password'])){
			$username = $_POST['username'];
			if(strlen($username)==7){
				$username = substr($username, 3);
			}
			$result = Auth::loginUser($username,$_POST['password'],$conn);
			if($result['status']==200)
				$purp = 0;
			else{
				$purp = -1;
				$title = $result["msg"];
			}
		}

	}
	///############# User Logged in
if($purp!=-1){
	$isSuperUser = Events::isSuperUser($_SESSION['userID'],$conn);
	$resp = People::isSpecial($_SESSION['userID'],$conn);
	///############# Home, logged in, List all events they are an admin for
	if($match[1]==''){
		$purp = 1;
		//$arr[];
		$arr = $resp["eventOrganiser"];

		$title = "List of Events and Categories";
		if($isSuperUser){
			$arr = $resp["eventOrganiser"];
			$sql = "SELECT eveId,eveName FROM Events ORDER BY eveId ASC";
	        $result_ = mysqli_query($conn, $sql);
	        $arr= array();
			while ($row_ = mysqli_fetch_assoc($result_)) {
			   $arr[] = [
			        "id"=>$row_['eveId'],
			        "name"=>$row_['eveName'],
			    ];
			}
			$title = "SuperUser:: All Events";
		} 
		///#############   Add Event
	}else if($match[1]== 'addEvent'){
		if(Events::isValidOrg($_SESSION['userID'],$match[2],$conn)[0]==-1 && !$isSuperUser){
			echo "403";
			die();
		}
		$purp = 2;
		foreach ($resp["eventOrganiser"] as $event) {
			if($event["id"]==$match[2]){
				$title = "Add new event under ".$event["name"];
				break;
			}
		}

		if(isset($_POST['eveName'])){
			$sql = "SELECT eveId FROM Events ORDER BY eveId DESC LIMIT 1";
			if($match[2]==0)
				$sql = "SELECT eveId FROM Events WHERE eveId < 10 ORDER BY eveId DESC LIMIT 1";
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($result);
            $evID =  $row['eveId'] + 1;
			$eveName = mysqli_real_escape_string($conn,$_POST['eveName']);
			$sql = "INSERT INTO `Events` (`eveId`, `eveName`, `code`) VALUES ('$evID', '".$eveName."' ,'".$match[2]."');";
			if($result = mysqli_query($conn,$sql)){

				$title = "Successfully added";
				$purp = 21;
			}
			// $sql = "INSERT INTO `Events` (`eveId`, `eveName`, `fee`, `day`, `size`, `code`, `tagline`, `date`, `time`, `venue`, `organisers`, `short_desc`, `long_desc`, `cover_url`, `icon_url`, `rules_url`, `owner1`, `owner2`, `owner3`, `owner4`) VALUES ('16', 'Loul', NULL, '1', '1', '1', 'Loolwa', '12 jan', '12 baje', 'admin block', 'Guyzzz', 'This is some desc', 'This is some more desc.', 'http://coverurl', 'http://iconurl', 'http://rules.pdf', '1000', NULL, NULL, NULL);";
		}
		///############# Update Event
	}else if($match[1]== 'update'){
		if(Events::isValidOrg($_SESSION['userID'],$match[2],$conn)[0]==-1 && !$isSuperUser){
			echo "403";
			die();
		}
		$title = "Update Info for Event no ".$match[2];
		$purp = 3;

		$fields = array( 'eveName', 'fee', 'day', 'tagline', 'date', 'time', 'venue', 'organisers', 'short_desc', 'long_desc', 'cover_url', 'icon_url', 'rules_url', 'owner1', 'owner2', 'owner3', 'owner4');
		if(isset($_POST['eveName']) || isset($_POST['fee']) || isset($_POST['day']) || isset($_POST['size']) || isset($_POST['tagline']) || isset($_POST['date']) || isset($_POST['time']) || isset($_POST['venue']) || isset($_POST['organisers']) || isset($_POST['short_desc']) || isset($_POST['long_desc']) || isset($_POST['cover_url']) || isset($_POST['icon_url']) || isset($_POST['rules_url']) || isset($_POST['owner1']) || isset($_POST['owner2']) || isset($_POST['owner3']) || isset($_POST['owner4'])){
			foreach ($fields as $fieldName ) {
				if($_POST[$fieldName]=='' || $_POST[$fieldName]==null || empty($_POST[$fieldName]) ){
					if(in_array($fieldName, array('fee','day','owner1','owner2','owner3','owner4'), TRUE)){
						$_POST[$fieldName] = NULL;//might case some errors
					}else{
						$_POST[$fieldName] = '';
						
					}
				}
			}
			// $eveId = mysqli_real_escape_string($conn,$_POST['eveId']);
			$eveName = mysqli_real_escape_string($conn,$_POST['eveName']);
			$fee = mysqli_real_escape_string($conn,$_POST['fee']);
			$day = mysqli_real_escape_string($conn,$_POST['day']);
			// $size = mysqli_real_escape_string($conn,$_POST['size']);
			// $code = mysqli_real_escape_string($conn,$_POST['code']);
			$tagline = mysqli_real_escape_string($conn,$_POST['tagline']);
			$date = mysqli_real_escape_string($conn,$_POST['date']);
			$time = mysqli_real_escape_string($conn,$_POST['time']);
			$venue = mysqli_real_escape_string($conn,$_POST['venue']);
			$organisers = mysqli_real_escape_string($conn,$_POST['organisers']);
			$short_desc = mysqli_real_escape_string($conn,$_POST['short_desc']);
			$long_desc = mysqli_real_escape_string($conn,$_POST['long_desc']);
			$cover_url = mysqli_real_escape_string($conn,$_POST['cover_url']);
			$icon_url = mysqli_real_escape_string($conn,$_POST['icon_url']);
			$rules_url = mysqli_real_escape_string($conn,$_POST['rules_url']);
			$owner1 = mysqli_real_escape_string($conn,$_POST['owner1']);
			$owner2 = mysqli_real_escape_string($conn,$_POST['owner2']);
			$owner3 = mysqli_real_escape_string($conn,$_POST['owner3']);
			$owner4 = mysqli_real_escape_string($conn,$_POST['owner4']);
			$intQuer = "";
			if($fee!=null && $fee!='')
				$intQuer.="`fee` = $fee, ";
			if($day!=null && $day!='')
				$intQuer.="`day` = $day, ";
			if($owner1!=null && $owner1!='')
				$intQuer.="`owner1` = $owner1, ";
			if($owner2!=null && $owner2!='')
				$intQuer.="`owner2` = $owner2, ";
			if($owner3!=null && $owner3!='')
				$intQuer.="`owner3` = $owner3, ";
			if($owner4!=null && $owner4!='')
				$intQuer.="`owner4` = $owner4, ";

			$query = "UPDATE `Events` SET `eveName` = '$eveName', $intQuer `tagline` = '$tagline', `date` = '$date', `time` = '$time', `venue` = '$venue', `organisers` = '$organisers', `short_desc` = '$short_desc', `long_desc` = '$long_desc', `cover_url` = '$cover_url', `icon_url` = '$icon_url', `rules_url` = '$rules_url' WHERE `Events`.`eveId` = ".$match[2].";";
			if($result = mysqli_query($conn,$query)){
				$title = "Successfully Updated for ".$match[2];
				$purp = 3;
			} else {
				error_log(mysqli_error($conn));
				error_log($query);
			}
		}

		$sql = "SELECT * FROM Events WHERE eveId=".$match[2];
            $result = mysqli_query($conn,$sql);
            $row = mysqli_fetch_assoc($result);
            $arr = $row;

	}else if($match[1]== 'delete'){
		if(Events::isValidOrg($_SESSION['userID'],$match[2],$conn)[0]==-1 && !$isSuperUser){
			echo "403";
			die();
		}

		$purp = 4;
		$title = "Delete ".Events::getEventDetails($match[2],$conn)[1]["eveName"]."? <br> confirm?";

		if(isset($match[3])){
			if($match[3]==0){
			    header('Location: /eventAdmin');
			}else if($match[3]==1){
				$eveID = mysqli_real_escape_string($conn,$match[2]);
				$sql="DELETE FROM Events WHERE eveId=$eveID";
	            $result = mysqli_query($conn,$sql);
	            if(!$result)
	            	error_log(mysqli_error($conn));
			    header('Location: /eventAdmin');

			}
		}

	}
}
?>
<!DOCTYPE html>
<html>
<head>

	<title>Admin control panel</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.0/css/bulma.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			<?php
				foreach ($arr as $key => $value) {
					echo "$(\"[name=".$key."]\").val('$value');
					";
				}
			?>
			// $("#logout").onClick(function(){
			// 	window.location=;
			// });
		}); 
	</script>
	<style>
	html {
  height: 100%;
}
body {
  min-height: 100%;
			background: #00d0b2;
  
}
		
		.field{
			width:300px;
		}
	</style>
</head>
<body bgcolor="#00d0b2">
<center>
	<section class="hero is-primary" >
		<div class="tags has-addons" style="position: fixed;left: 0;top:0">
	      <span class="tag is-success is-large" onclick="window.location='/eventAdmin';">
			  Home
			  <!-- <button class="delete is-small"></button> -->
			</span>
			
	    </div>
		<?php
			if($purp!=-1)
				echo "<div class=\"tags has-addons\" style=\"position: fixed;right: 0;top:0\">
					<span class=\"tag is-danger is-large\" id=\"logout\" onclick=\"window.location='/eventAdmin/logout';\">
						  Logout
						  <button class=\"delete\"></button>
						</span>
				    </div>";
		?>
	    <br><br>
  <div class="hero-body is-fullheight">
    <div class="container is-fullheight">
      <h1 class="title">
       	Event Admin control panel
      </h1>
      <h2 class="subtitle">
        <?php echo $title; ?>
      </h2>
    </div>
    <!-- login -->
    <?php
	if($purp == -1)
    	echo "<form action=\"\" method=\"POST\" >
		<div class=\"field\">
  			<label class=\"label\">AnweshaID</label>
			<div class=\"control\">
				<input class=\"input\" type=\"text\" name=\"username\" placeholder=\"ANWxxx\">
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Password</label>
			<div class=\"control\">
				<input class=\"input\" type=\"password\" name=\"password\" placeholder=\"********\">
			</div>
		</div>
		
		    <button class=\"button is-link\">Login</button>
		  
  
	</form>";
    ?>
    
	<!-- login ends -->

	<!-- Add new event part -->
	<?php
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
		if($purp==1)
		foreach ($arr as $eve) {
			if($eve["id"]=='' || $eve["id"]==null)
				continue;
			$action = ($eve["id"]<10)? "Add New Event":"Edit Event";
			$color = ($eve["id"]<10)? "is-warning":"is-success";
			$action_ = ($eve["id"]<10)? "addEvent":"update";
			echo "<a href='$actual_link/eventAdmin/$action_/".$eve['id']."'><div class=\"tags has-addons\" style=\"width:500px\">
			      <span class=\"tag is-large is-dark\">".$eve['name']."</span>
			      <span class=\"tag is-large is-info\">".$eve['id']."</span>
			      <span class=\"tag is-large $color\">".$action."</span>
			    </div><br>
			    ".PHP_EOL;
		}
	?>
	<?php
		if($purp==2)
			echo "<form action=\"\" method=\"POST\" >
		<div class=\"field\">
  			<label class=\"label\">Event Name</label>
			<div class=\"control\">
				<input class=\"input\" type=\"text\" name=\"eveName\" placeholder=\"Event Name\">
			</div>
		</div>
		
		    <button class=\"button is-link\">Add Event</button>
		  
  
	</form>
	<button class=\"button is-danger\" onclick=\"window.location='/eventAdmin/delete/".$match[2]."'\">Delete this category</button>
	
	";

		if($purp == 21){
			echo "<a href='../update/".$evID."'>Add more info to event-> </a>";
		}
	?>
	<!-- Add new event part ends -->
	<?php
	if($purp==3)
		echo "<form action=\"\" method=\"POST\" >
		<div class=\"field\">
  			<label class=\"label\">Event Name</label>
			<div class=\"control\">
				<input class=\"input\" type=\"text\" name=\"eveName\" placeholder=\"Event Name\">
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Fee</label>
			<div class=\"control\">
				<input class=\"input\" type=\"number\" name=\"fee\" >
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Day 1/2/3</label>
			<div class=\"control\">
				<input class=\"input\" min='1' max='3' type=\"number\" name=\"day\" >
			</div>
		</div>
		<input class=\"input\" type=\"hidden\" name=\"eveId\" >
		<input class=\"input\" type=\"hidden\" name=\"code\" >

		<div class=\"field\">
  			<label class=\"label\">Tag Line</label>
			<div class=\"control\">
				<input class=\"input\" type=\"text\" name=\"tagline\" maxlength=\"100\" placeholder=\"Tag Line\">
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Date(in any format)</label>
			<div class=\"control\">
				<input class=\"input\" type=\"text\" name=\"date\" maxlength=\"15\" placeholder=\"2nd Feb\">
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Time(in any format)</label>
			<div class=\"control\">
				<input class=\"input\" type=\"text\" name=\"time\" maxlength=\"15\" placeholder=\"9am - 10am\">
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Venue</label>
			<div class=\"control\">
				<input class=\"input\" type=\"text\" name=\"venue\" maxlength=\"50\" placeholder=\"Main stage\">
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Organisers Contact Info</label>
			<div class=\"control\">
				 <textarea name='organisers' class=\"input\" rows=\"4\" cols=\"5\" maxlength=\"400\">
				Org1-9920202020#
				Org2-9920211111#
				</textarea> 
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Short Description</label>
			<div class=\"control\">
				 <textarea name='short_desc' class=\"input\" rows=\"4\" cols=\"4\" maxlength=\"400\">Short Desc about the event.
				</textarea> 
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Long Description</label>
			<div class=\"control\">
				 <textarea name='long_desc' class=\"input\" rows=\"4\" cols=\"10\" maxlength=\"800\">Long Desc about the event.
				</textarea> 
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Link to cover image</label>
			<div class=\"control\">
				<input class=\"input\" type=\"text\" name=\"cover_url\" placeholder=\"http://xyz.com/xyz.jpg\">
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Link to Icon image</label>
			<div class=\"control\">
				<input class=\"input\" type=\"text\" name=\"icon_url\" placeholder=\"http://xyz.com/xyz.jpg\">
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Link to rules pdf</label>
			<div class=\"control\">
				<input class=\"input\" type=\"text\" name=\"rules_url\" placeholder=\"http://xyz.com/xyz.pdf\">
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Admin1 of the event(enter 4 digits of anwesha ID)</label>
			<div class=\"control\">
				<input class=\"input\" type=\"number\" name=\"owner1\" min='1000' max='9999'>
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Admin2 of the event(enter 4 digits of anwesha ID)</label>
			<div class=\"control\">
				<input class=\"input\" type=\"number\" name=\"owner2\" min='1000' max='9999'>
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Admin3 of the event(enter 4 digits of anwesha ID)</label>
			<div class=\"control\">
				<input class=\"input\" type=\"number\" name=\"owner3\" min='1000' max='9999'>
			</div>
		</div>
		<div class=\"field\">
  			<label class=\"label\">Admin4 of the event(enter 4 digits of anwesha ID)</label>
			<div class=\"control\">
				<input class=\"input\" type=\"number\" name=\"owner4\" min='1000' max='9999'>
			</div>
		</div>
		    <button class=\"button is-link\">Update</button>
		  	
  
	</form>
	<button class=\"button is-danger\" onclick=\"window.location='/eventAdmin/delete/".$match[2]."'\">Delete this event</button>
";
	?>
		
    <!-- delete -->
<?php
if($purp == 4)
	echo " <button class=\"button is-danger\" onclick=\"window.location = window.location + '/1'\">Yes</button>
	 <button class=\"button is-success\" onclick=\"window.location = window.location + '/0'\">Cancel</button>
		";
?>
	
    <!-- delete end -->
  </div>
</section>
	
</center>
</body>
</html>