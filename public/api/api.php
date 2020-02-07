<?php
session_start();
//header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization,X-Requested-With");
include_once './config/database.php';
include_once './objects/Log.php';
//session


// get database connection
$database = new Database();
$db = $database->getConnection();
$server   = 'https://api.saludmedica.io/lis-s';

$debug    = $_REQUEST["debug"] ? 1 : 0;
$method   = $_REQUEST["m"] ? $_REQUEST["m"] : "Orders";
$npi      = $_REQUEST["n"];
$location = $_REQUEST["l"];
$params = file_get_contents('php://input');
$params = urldecode($params);

if(isset($method)) {
  $data = false;
  
  switch($method) {
    case "Orders":
      $data = getOrdersForProvider($npi, $params);
      break;
      
    case "Details":
      $data = getOrderDetails($location);
      break;
  }
  
  if($data) {
	  http_response_code(200);
	  $log = new Log($db);
	  $log->user_id = $_SESSION["user_id"];
	  $log->npi     = $_SESSION["npi"];
	  $log->action  = $method;
	  $log->current_date_time = date("Y-m-d h:i:sa");
	  $log->create();

   if($debug) {
      echo "<pre>";
      echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
      print json_encode($data);
    }
  }
}


// Order details - table 2
function getOrderDetails($location) {
	global $server, $debug;
	
	$url = $server . $location;
	
	if($debug) echo $url;
	
	$data = json_decode(curl_get_contents($url), true);
	
	$retData = [];
	
	if($data["data"]) {
  	foreach($data["data"] as $result) {
      foreach($result["Tests"] as $test) {
        
        $order = array();
    		$order["Procedure"] = $test["TestCode"];
    		$order["ProcedureDesc"] = $test["TestDescription"];
    		$order["Status"] = $test["Status"];
    		$order["StatusDesc"] = $test["StatusDescription"];
    		$order["Priority"] = $test["Priority"];
    
        if($test["Attachments"]) {
          foreach($test["Attachments"] as $att) {
            if($att["Type"] == "PDF" && $att["Data"] != "") {
              $order["PDF"] = $att["Data"];
              $order["PDFName"] = $result["PatientCustomId"] . "_" . $result["OrderId"] . "_" . $test["TestCode"];
              break;
            }
          }
        }
        
    		$retData[] = $order;		
        
      }	
    }  	
	}
	
	return $retData;
} 

// Orders for providers - table 1
function getOrdersForProvider($npi, $filters) {
	global $server, $debug;

	// convert query string to object
	parse_str($filters, $f);
	// check for status
	if(isset($f["status"])) {
  	// store status 
  	$statusFilter = $f["status"];
  	// remove status
  	unset($f["status"]);  	
	}
	
	// create url
	$url = $server . "/v1/providers/$npi/orders?" . http_build_query($f);

  //error_log($url);
	$data = json_decode(curl_get_contents($url, $filters), true);
	$retData = [];
	
	if($data["data"]) {
  	foreach($data["data"] as $result) {
    	// check if the status is found
    	if(isset($statusFilter)) {
      	$s = explode("|", $statusFilter);
      	// if not in array skip
      	if(!in_array($result["Status"], $s)) {
          continue;	        	
      	}
    	}
  		$order["Requisition"] = $result["OrderId"];
  		$order["Date"] = formatDate($result["DateEnteredLocal"]);
  		$order["Patient"] = $result["PatientName"];
  		$order["Account"] = $result["PatientCustomId"];
  		$order["Status"] = $result["Status"];
  		$order["StatusDesc"] = $result["StatusDescription"];
      $order["DetailUri"] = $result["LocationUri"];
      
  		$retData[] = $order;
  	}	
	}
	
	return $retData;
}

function formatDate($dateStr) {
  // parse
  $dateTime = strtotime($dateStr);
  
  // format
  return date("m/d/Y", $dateTime);
}

// function to get data from API
function curl_get_contents($_url) {
	// Initiate the curl session
	$ch = curl_init();
	// Set the URL
	curl_setopt($ch, CURLOPT_URL, $_url);
	// Removes the headers from the output
	curl_setopt($ch, CURLOPT_HEADER, 0);
	// Return the output instead of displaying it directly
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// Set headers
	$headers = [
	    //'Cache-Control: no-cache',
	    //'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
	    'smx-api-client: STXLABSTAGING',
	    'smx-api-env: 	 Staging',
	    'smx-api-key: 	 a91d62cf50a749ff9915a835d11632e6',		
	];
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	
	
	// Execute the curl session
	$output = curl_exec($ch);
	// Close the curl session
	curl_close($ch);
	// Return the output as a variable
	return $output;
}

?>