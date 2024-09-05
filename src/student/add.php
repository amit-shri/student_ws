<?php

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$fullName = '';  
$country = '';
$state = '';
$city = '';
$address = '';
$gender = '';
$subjects = [];

$conn = null;

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));

print_r($data); die;

$fullname = $data->fullname;
$country = $data->country;
$state = $data->state;
$city = $data->city;
$address = $data->address;
$gender = $data->gender;
$subjects = $data->subjects;
$table_name = 'student';
 
$query = "INSERT INTO " . $table_name . "
                SET fullname = :fullname,
                    country = :country,
                    state = :state,
                    city = :city
                    address = :address,
                    gender = :gender,
                    subjects = :subjects
                    
                    ";

$stmt = $conn->prepare($query);

$stmt->bindParam(':fullname', $fullname);
$stmt->bindParam(':country', $country);
$stmt->bindParam(':state', $state);
$stmt->bindParam(':city', $city);
$stmt->bindParam(':address', $address);
$stmt->bindParam(':gender', $gender);
$stmt->bindParam(':subjects', $subjects);
  

if($stmt->execute()){
 
    http_response_code(200);
    echo json_encode(array("message" => "User was successfully registered."));
}
else{
    http_response_code(400);
 
    echo json_encode(array("message" => "Unable to register the user."));
}
?>