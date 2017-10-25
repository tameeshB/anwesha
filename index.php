<?php
ini_set('display_errors', '1');     # don't show any errors...
error_reporting(E_ALL | E_STRICT);  # ...but do log them
ini_set('zlib_output_compression','On');

ini_set("log_errors", 1);
ini_set("error_log", "/tmp/php-error.log");
error_log("hi" );
/**
* New request lands in this class. After that it is routed accordingly to the respective controller.
* Also provides basic functions for loading models.
* Also provides basic methods for HTTP responses and redirects.
*/
class Routing
{

	function __construct()
	{
		return null;
	}

	public function Redirect($url)
	{
		return null;
	}

}

require('global.php');

$url = $_SERVER['REQUEST_URI'];
preg_match('@(.*)index.php(.*)$@', $_SERVER['PHP_SELF'], $mat );
$base = '@^'. $mat[1] ;

if (preg_match($base . '(ca|)_?([0-9]{4}|)$@', $url, $match)) {
	require ('new.php');
	// require ('new.php');
} elseif (preg_match($base . 'register/?$@', $url, $match)) {
	require ('view/reg.php');
} elseif (preg_match($base . 'leaderboard/api/?$@', $url, $match)) {
	require ('controller/leaderboardback.php');
} elseif (preg_match($base . 'ca/?$@', $url, $match)) {
	require ('view/ca.php');
	// header('Location: ../');
} elseif ('/anw/ca/?i=1' == $url) {
        require ('view/ca.php');
        // header('Location: ../');
} elseif (preg_match($base . 'register/?$@', $url, $match)) {
	header('Location: ../');
} elseif (preg_match($base . 'index.php?$@', $url, $match)) {
	header('Location: ./');
} elseif (preg_match($base . 'leaderboard/?$@', $url, $match)) {
	require ('view/leaderboard.php');
} elseif (preg_match($base . 'team/$@', $url, $match)) {
	require ('view/team.html');
} elseif (preg_match($base . 'accodomation/$@', $url, $match)) {
	require ('view/accodomation.html');
} elseif (preg_match($base . 'schedule_raw/$@', $url, $match)) {
	require ('view/schedule.html');
} elseif (preg_match($base . 'preloader/$@', $url, $match)) {
	require ('view/preloader.html');
} elseif (preg_match($base . 'auditions/$@', $url, $match)) {
	require ('view/multiCityAuditions.html');
} elseif (preg_match($base . 'auditions/linefollow/$@', $url, $match)) {
	require ('view/linefollow.html');
} elseif ( preg_match($base .'faq/?$@', $url, $match ) ) {
	require ('view/faq.php');
} elseif (preg_match($base . 'gallery/$@', $url, $match)) {
	require ('view/gallery.html');
} elseif (preg_match($base . 'sponsors/$@', $url, $match)) {
	require ('view/spons.html');
} elseif (preg_match($base . 'switchca/$@', $url, $match)) {
	require ('view/switchca.php');
// } elseif ( preg_match($base .'cssLoader/home/?$@', $url, $match ) ) {
// 	require ('controller/cssLoader.php');
} elseif ( preg_match($base .'events/?$@', $url, $match ) ) {
	require ('view/events.html');
} elseif ( preg_match($base .'eventsData/?$@', $url, $match ) ) {
	require ('controller/events.php');
} elseif (preg_match($base . 'allEvents/?$@', $url)) {
	require ('controller/allEvents.php');
} elseif (preg_match($base . 'events/([0-9]{1,3})/?$@', $url, $match)) {
	require ('controller/getSubEvents.php');
} elseif (preg_match($base . 'user/register/User/?$@', $url)) {
	require ('controller/userRegistration.php');
} elseif (preg_match($base . 'user/CAcheck/([0-9]+)/?$@', $url, $match)) {
	require ('controller/cacheck.php');
} elseif (preg_match($base . 'user/register/CampusAmbassador/?$@', $url)) {
	require ('controller/campusAmbassadorRegistration.php');
} elseif (preg_match($base . 'user/switch/CampusAmbassador/?$@', $url)) {
	require ('controller/campusAmbassadorSwitching.php');
} elseif (preg_match($base . 'verifyEmail/CampusAmbassador/([0-9]{4})/([A-Za-z0-9]{40})/?$@', $url, $match)) {
	require ('controller/verifyEmail.php');
} elseif (preg_match($base . 'verifyEmail/User/([0-9]{4})/([A-Za-z0-9]{40})/?$@', $url, $match)) {
	require ('controller/verifyEmail.php');
} elseif (preg_match($base . 'register/([0-9]{1,3})/?$@', $url, $match)) {
	require ('controller/registerUser.php');
// } elseif (preg_match($base . 'register/group/([0-9]{1,2})/?$@', $url, $match)) {
// 	require ('controller/registerGroup.php');
} elseif (preg_match($base . 'login/?$@', $url)) {
	require ('controller/loginUser.php');
} elseif (preg_match($base . 'logout/?$@', $url)) {
	require ('controller/logout.php');


//For Changing Passwords
} elseif (preg_match($base . 'changePassword/?$@', $url)) {
	require ('controller/changePassword.php');
}elseif (preg_match($base . 'viewRef/?$@', $url)) {
	require ('view/viewRef.php');
} elseif (preg_match($base . 'reset/([a-zA-Z0-9_.+-]+\@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+)/?$@', $url,$match)) {
	require ('controller/forgetpasswdmail.php');
} elseif (preg_match($base . 'resetpassword/([0-9]{4})/([A-Za-z0-9]{40})/$@', $url,$match)) {
	require ('view/reset.php');
} elseif (preg_match($base . 'resetpassword/([0-9]{4})/([A-Za-z0-9]{40})/now/?$@', $url,$match)) {
	require ('controller/forgetpassword_token.php');

} elseif (preg_match($base . 'resend/([a-zA-Z0-9_.+-]+\@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+)/?$@', $url, $match)) {
	require ('controller/resendVerification.php');
} elseif (preg_match($base . 'download_records/(.*)$@', $url, $match)) {
	require ('controller/download_records.php');
} elseif (preg_match($base . 'download_events/(.*)$@', $url, $match)) {
	require ('controller/download_events.php');
} elseif (preg_match($base . 'download_logs/([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4})/([0-9A-Za-z]{32})/?$@', $url, $match)) {
	require ('controller/download_logs.php');
} elseif (preg_match($base . 'payload$@', $url)) {
        require ('controller/payload.php');
//App
} elseif (preg_match($base . 'appversion/?$@', $url)) {
        echo "1";
} else {
	http_response_code(404);
	die('invalid url ' . $url);
}


?>
