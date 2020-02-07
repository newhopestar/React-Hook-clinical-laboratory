<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// database connection will be here

// files needed to connect to database
include_once '../config/database.php';
include_once '../config/functions.php';
include_once '../objects/User.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// check email existence here
	if (isset($_POST['email'])) {
		if (!empty($_POST['email'])) {

		// get posted data
			$email = $_POST['email'];

		// set product property values
			$user->email = $email;
			$email_exists = $user->emailExists();
		// files for jwt will be here
			$user_id = $user->id;

		// check if email exists and if password is correct
			if($email_exists){

				$password = $user->randomPassword();
				$user->password = $password;
				if($user->update_password()) {
					$subject    = "User Forgot password request";
	                $mailformat = '<h2 style="text-align:center">Reset Password</h2>
	                
	                <p>
	                Dear User,</p>
	                <p>
	                We have received your request to generate a new password and your new password is listed below.</p>
	                
	                <p>
	                New Password: ' . $password . '</p>
	                <blockquote>
	                <p>
	                &nbsp;</p>
	                </blockquote>
	                <p>
	                <b>Thanks &amp; Regards<br />
	                Admin</b></p>
	                ';
	                				
	                mailSend($email, $subject, $mailformat);

					// set response code
					http_response_code(200);

					// display message: user was created
					echo json_encode(array(
						"status" => "success",
						// "mailformat" => $mailformat,
						"message" => "New password sent to user email."
					));
				} else {
				// set response code
					http_response_code(400);

					// display message: unable to create user
					echo json_encode(array("status" => "fail","message" => "Something wen't wrong."));
				}
			} else{

    		// set response code
				http_response_code(401);

    		// tell the user login failed
				echo json_encode(array("status" => "fail","message" => "No account found."));
			}
		} else {
		// message if unable to create user

    	// set response code
			http_response_code(400);

    	// display message: unable to create user
			echo json_encode(array("status" => "fail","message" => "Email is missing"));	
		}
	} else {
		// message if unable to create user

    	// set response code
		http_response_code(400);

    	// display message: unable to create user
		echo json_encode(array("status" => "fail","message" => "Email is missing"));	
	}
} else {
    // set response code - 404 Not found
	http_response_code(400);

    // tell the user no records found
	echo json_encode(
		array("status" => "fail","message" => "Invalid request")
	);
}
?>