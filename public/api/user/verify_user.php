<?php

// database connection will be here
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
// files needed to connect to database
include_once '../config/database.php';
include_once '../config/functions.php';
include_once '../objects/User.php';
include_once '../objects/Verify.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);
$verify = new Verify($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
// check email existence here
	if (isset($_GET['code'])) {
		if (!empty($_GET['code'])) {

		// get geted data
			$code = $_GET['code'];

		// set product property values
			$verify->code = $code;
			$verify_user_exists = $verify->codeExists();
			

		// check if email exists and if password is correct
			if($verify_user_exists) {
				$user_id = $verify->user_id;
				$user_exists = $user->user_details($user_id);
				if ($user_exists) {
					if ($user_exists['active'] != 1) {
						

						$user->id = $user_id;
						$user->active = 1;
						if ($user->active_user($user_id)) {
						// set response code
							http_response_code(200);
							echo json_encode(array(
								"status" => "success",
								"message" => "User Active successfully."
							));
						/*// display message: user was created
						echo json_encode(array(
							"status" => "success",
						// "mailformat" => $mailformat,
							"message" => "User Active successfully."
						));*/
					} else {
						// set response code
						http_response_code(400);

						// display message: unable to create user
						// echo json_encode(array("status" => "fail","message" => "Something wen't wrong."));
							echo json_encode(array(
								"status" => "fail",
								"message" => "Something wen't wrong."
							));
					}
				} else {
						// set response code
					http_response_code(400);

						// display message: unable to create user
						// echo json_encode(array("status" => "fail","message" => "Something wen't wrong."));
						echo json_encode(array(
								"status" => "fail",
								"message" => "User is already actived."
							));
				}
			} else {

	    		// set response code
				http_response_code(401);

	    		// tell the user login failed
					// echo json_encode(array("status" => "fail","message" => "No account found."));
				echo json_encode(array(
								"status" => "fail",
								"message" => "No account found."
							));	
			}
		} else{

    		// set response code
			http_response_code(401);

    		// tell the user login failed
				// echo json_encode(array("status" => "fail","message" => "No account found."));
				echo json_encode(array(
								"status" => "fail",
								"message" => "No account found."
							));	
		}
	} else {
		// message if unable to create user

    	// set response code
		http_response_code(400);

    	// display message: unable to create user
			// echo json_encode(array("status" => "fail","message" => "Code is missing"));	
			echo json_encode(array(
				"status" => "fail",
				"message" => "Code is missing"
			));	
	}
} else {
		// message if unable to create user

    	// set response code
	http_response_code(400);

    	// display message: unable to create user
		// echo json_encode(array("status" => "fail","message" => "Code is missing"));	
	echo json_encode(array(
				"status" => "fail",
				"message" => "Code is missing"
			));	
}
} else {
    // set response code - 404 Not found
	http_response_code(400);

    // tell the user no records found
	// echo json_encode(array("status" => "fail","message" => "Invalid request"));
	echo json_encode(array(
				"status" => "fail",
				"message" => "Invalid request"
			));	
}

?>