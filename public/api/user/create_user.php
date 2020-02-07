<?php
// required headers
//header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
include_once '../config/database.php';
include_once '../config/functions.php';
include_once '../objects/User.php';
include_once '../objects/Verify.php';


// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate product object
$user = new User($db);
$verify = new Verify($db);

// submitted data will be here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// get posted data
	if (isset($_POST['name']) && isset($_POST['practice']) && isset($_POST['npi']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {

		if (!empty($_POST['name']) && !empty($_POST['practice']) && !empty($_POST['npi']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
			$password = $_POST['password'];
			$confirm_password = $_POST['confirm_password'];
			if ($password == $confirm_password) {

				$name = $_POST['name'];
				$practice = $_POST['practice'];
				$npi = $_POST['npi'];
				$email = $_POST['email'];

				$code = rand(11111,99999);

		// set product property values
				$user->name = $name;
				$user->practice = $practice;
				$user->npi = $npi;
				$user->email = $email;
				$user->password = $password;
				$user->active = 0;
				// $user->verify_code = $verify_code;

		// create the user
				
				if(!$user->emailExists()) {
					if($user_id = $user->create()) {
						$verify->user_id = $user_id;
						$verify->code = $code;
						if ($verify->create()) {
							$verify_url = BASE_URL."/user/verify_user?code=".$code;
							$verify_link = "<a href='".$verify_url."'>Click Here</a>";
							$subject    = "User registration confirmation";
							$mailformat = '<h2 style="text-align:center">Registration Confirmation</h2>
							
							<p>
							Dear User,</p>
							<p>
							You have successfully register. Please verify your account. To verify your account '.$verify_link.' </p>
							<blockquote>
							<p>
							&nbsp;</p>
							</blockquote>
							<p>
							<b>Thanks &amp; Regards,<br />
							Admin</b></p>
							';
							
							mailSend($email, $subject, $mailformat);

							// set response code
							http_response_code(200);

							// display message: user was created
							echo json_encode(array(
								"status" => "success",
								"message" => "Account created. Please check your email."
							));
						} else {
								// set response code
							http_response_code(400);

								// display message: unable to create user
							echo json_encode(array("status" => "fail","message" => "Something went wrong."));
						}
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
					echo json_encode(array("status" => "fail","message" => "Email address is already in use."));
				}
				
			} else {
				// set response code
				http_response_code(400);

				// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "Password and confirm password do not match"));	
			}
		} else {
			if (empty($_POST['name'])) {
			// set response code
				http_response_code(400);

			// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "name is missing"));	
			} else if (empty($_POST['practice'])) {
			// set response code
				http_response_code(400);

			// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "practice is missing"));	
			} else if (empty($_POST['npi'])) {
			// set response code
				http_response_code(400);

			// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "npi is missing"));	
			} else if (empty($_POST['email'])) {
			// set response code
				http_response_code(400);

			// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "Email is missing"));	
			} else if (empty($_POST['password'])) {
			// set response code
				http_response_code(400);

			// display message: unable to create user
				echo json_encode(array("message" => "Password is missing"));	
			} else if (empty($_POST['confirm_password'])) {
			// set response code
				http_response_code(400);

			// display message: unable to create user
				echo json_encode(array("status" => "fail","message" => "Confirm password is missing"));	
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
			echo json_encode(array("status" => "fail","message" => "name is missing"));	
		} else if (!isset($_POST['practice'])) {
			// set response code
			http_response_code(400);

			// display message: unable to create user
			echo json_encode(array("status" => "fail","message" => "practice is missing"));	
		} else if (!isset($_POST['npi'])) {
			// set response code
			http_response_code(400);

			// display message: unable to create user
			echo json_encode(array("status" => "fail","message" => "npi is missing"));	
		} else if (!isset($_POST['email'])) {
			// set response code
			http_response_code(400);

			// display message: unable to create user
			echo json_encode(array("status" => "fail","message" => "Email is missing"));	
		} else if (!isset($_POST['password'])) {
			// set response code
			http_response_code(400);

			// display message: unable to create user
			echo json_encode(array("status" => "fail","message" => "Password is missing"));	
		} else if (!isset($_POST['confirm_password'])) {
			// set response code
			http_response_code(400);

			// display message: unable to create user
			echo json_encode(array("status" => "fail","message" => "Confirm password is missing"));	
		} else {
			// set response code
			http_response_code(400);

			// display message: unable to create user
			echo json_encode(array("status" => "fail","message" => "Details are missing"));	
		}    	
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