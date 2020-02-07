<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
include_once '../config/database.php';
include_once '../objects/User.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);

// submitted data will be here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// get posted data
	if (isset($_POST['user_id'])) {
		if (isset($_POST['name']) && isset($_POST['practice']) && isset($_POST['npi']) && isset($_POST['email'])) {

			if (!empty($_POST['name']) && !empty($_POST['practice']) && !empty($_POST['npi']) && !empty($_POST['email'])) {
				$password = "";
				$has_error = 0;
				if (isset($_POST['name']) && isset($_POST['confirm_password'])) { 
					if (!empty($_POST['name']) && !empty($_POST['confirm_password'])) { 
						$password = $_POST['password'];
						$confirm_password = $_POST['confirm_password'];
						if ($password != $confirm_password) {
							$has_error++;
					// set response code
							http_response_code(400);

	    			// display message: unable to create user
							echo json_encode(array("status" => "success","message" => "Password and confirm password does't match"));	
						}
					} else {
						$has_error++;
						// set response code
						http_response_code(400);

	    			// display message: unable to create user
						echo json_encode(array("status" => "fail","message" => "Password and confirm password is required"));	
					}
				}
				if($has_error == 0) {

					$user_id = $_POST['user_id'];
					$name = $_POST['name'];
					$practice = $_POST['practice'];
					$npi = $_POST['npi'];
					$email = $_POST['email'];

		// set product property values
					$user->id = $user_id;
					$user->name = $name;
					$user->practice = $practice;
					$user->npi = $npi;
					$user->email = $email;
					if ($password != "") {
						$user->password = $password;
					}
					$user_details = $user->user_details($user_id);
					if(!empty($user_details)) {

						// create the user
						if(!$user->emailExists($user_id)) {
							if($user->update()) {
    							// set response code
								http_response_code(200);

    							// display message: user was created
								echo json_encode(array("status" => "success","message" => "User was updated."));
							} else {
    						// set response code
								http_response_code(400);

    							// display message: unable to create user
								echo json_encode(array("status" => "fail","message" => "Unable to create user."));
							}
						} else {
    						// set response code
							http_response_code(400);

    						// display message: unable to create user
							echo json_encode(array("status" => "fail","message" => "Email address is already exiest."));
						}

					} else {
    					// set response code
						http_response_code(400);

    					// display message: unable to create user
						echo json_encode(array("status" => "fail","message" => "User not exiest."));
					}
				}
			} else {
				if (empty($_POST['name'])) {
			// set response code
					http_response_code(400);

	    	// display message: unable to create user
					echo json_encode(array("status" => "fail","message" => "name are missing"));	
				} else if (empty($_POST['practice'])) {
			// set response code
					http_response_code(400);

	    	// display message: unable to create user
					echo json_encode(array("status" => "fail","message" => "practice are missing"));	
				} else if (empty($_POST['npi'])) {
			// set response code
					http_response_code(400);

	    	// display message: unable to create user
					echo json_encode(array("status" => "fail","message" => "npi are missing"));	
				} else if (empty($_POST['email'])) {
			// set response code
					http_response_code(400);

	    	// display message: unable to create user
					echo json_encode(array("status" => "fail","message" => "Email are missing"));	
				} else {
			// set response code
					http_response_code(400);

	    	// display message: unable to create user
					echo json_encode(array("status" => "fail","message" => "Details are missing"));	
				}    	
			}
		} else {
			if (!isset($_POST['name'])) {
			// set response code
				http_response_code(400);

	    	// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "name are missing"));	
			} else if (!isset($_POST['practice'])) {
			// set response code
				http_response_code(400);

	    	// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "practice are missing"));	
			} else if (!isset($_POST['npi'])) {
			// set response code
				http_response_code(400);

	    	// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "npi are missing"));	
			} else if (!isset($_POST['email'])) {
			// set response code
				http_response_code(400);

	    	// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "Email are missing"));	
			} else {
			// set response code
				http_response_code(400);

	    	// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "Details are missing"));	
			}   	
		}
	} else {
		// set response code
		http_response_code(400);

    	// display message: unable to create user
		echo json_encode(array("status" => "fail","message" => "User Id are missing"));	
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