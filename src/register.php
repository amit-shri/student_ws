<?php

include_once './config/database.php';

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$firstName = '';
$lastName = '';
$email = '';
$password = '';
$conn = null;

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));

$firstName = $data->firstname;
$lastName = $data->lastname;
$email = $data->email;
$password = $data->password;
$agree_condition = isset($data->agree_condition) ? $data->agree_condition : 0;

//---check mandatory fields
if (trim($firstName) == '' || trim($lastName) == '' || trim($email) == '' || trim($password) == '') {

    http_response_code(500);
    echo json_encode(array("status" => false, "msg" => "Please fill all mandatory fields."));
    die;
}
//---------------------


//---Term and conditions---
if (!$agree_condition) {

    http_response_code(500);
    echo json_encode(array("status" => false, "msg" => "Agree to term and conditions."));
    die;
}

//---------------------

//----Check email is valid

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    http_response_code(500);
    echo json_encode(array("status" => false, "msg" => "Please enter valid email address."));
    die;
}
//--------------------


//---check email already exists----

$table_name = 'Users';

$query = "SELECT * FROM  " . $table_name . " WHERE email = :email ";

$stmt = $conn->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($row['id']) && $row['id'] > 0) {
    http_response_code(500);
    echo json_encode(array("status" => false, "msg" => "User is already registed."));
    die;
}

//--------------


$query = "INSERT INTO " . $table_name . "
                SET first_name = :firstname,
                    last_name = :lastname,
                    email = :email,
                    password = :password";

$stmt = $conn->prepare($query);

$stmt->bindParam(':firstname', $firstName);
$stmt->bindParam(':lastname', $lastName);
$stmt->bindParam(':email', $email);

$password_hash = password_hash($password, PASSWORD_BCRYPT);

$stmt->bindParam(':password', $password_hash);


if ($stmt->execute()) {

    http_response_code(200);
    echo json_encode(array("status" => true, "msg" => "User is successfully registered. Please use your email address and password for login."));
} else {
    http_response_code(400);
    echo json_encode(array("status" => false, "msg" => "Unable to register the user."));
}

exit;
