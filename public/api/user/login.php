	<?php
session_start();
// required headers
//header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Methods: POST");
//header("Access-Control-Max-Age: 3600");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// database connection will be here

// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/User.php';
include_once '../objects/Log.php';
//session

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// check email existence here
	if (isset($_POST['email']) && isset($_POST['password'])) {

		// get posted data
		$email = $_POST['email'];
		$password = $_POST['password'];

		// set product property values
		$user->email = $email;
		$email_exists = $user->emailExists();


		// check if email exists and if password is correct
		if($email_exists && password_verify($password, $user->password)){
			if ($user->active == 1) {
				
			$access_token = md5(uniqid(rand(), true));
			$user->update_access_token($access_token,$user->id);
			$user_details = $user->user_details($user->id);
    		// set response code
			http_response_code(200);
    		// generate jwt		
   
			$_SESSION["access_token"] = $access_token;
			$_SESSION["user_id"]=$user_details['user_id'];
			$_SESSION["npi"]    =$user_details['npi'];
			$_SESSION["email"]    =$user_details['email'];
				
			$log = new Log($db);
			$log->user_id = $user_details['user_id'];
			$log->npi     = $user_details['npi'];
			$log->action  = "log in";
			$log->current_date_time = date("Y-m-d h:i:sa");
			$log->create();
     		echo json_encode(
				array(
					"status" => "Success",
					"message" => "Successful login.",
					"access_token" => $access_token,
					"user_detail" => $user_details,
					"session" => $_SESSION
				)
			);
			
			} else {

	    		// set response code
				http_response_code(401);
			

	    		// tell the user login failed
				echo json_encode(array("status" => "fail","message" => "User is not active."));
			}

		}

		// login failed will be here
		// login failed
		else{

    		// set response code
			http_response_code(401);

    		// tell the user login failed
			echo json_encode(array("status" => "fail","message" => "Login failed."));
		}
	} else {
		// message if unable to create user

    	// set response code
		http_response_code(400);

    	// display message: unable to create user
		echo json_encode(array("status" => "fail","message" => "Details are missing"));	
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