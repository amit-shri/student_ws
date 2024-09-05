<?php

include_once './config/database.php';
require "./vendor/autoload.php";

use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$secret_key = "YOUR_SECRET_KEY";
$jwt = null;
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

$arr = explode(" ", $authHeader);
if (isset($arr[1])) {
    $jwt = $arr[1];
}

if ($jwt) {

    try {

        $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
        if ($decoded->data->id > 0) {
            
            $table_name = 'Users';
            $query = "SELECT first_name FROM " . $table_name . " WHERE status = 1 and id = ? LIMIT 0,1";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $decoded->data->id);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                echo json_encode(array(
                    "message" => "Access granted: ",
                    "status" => true,
                    "firstname" => $row['first_name']
                ));
                die;
            }
        } 
        
    } 
    catch (Exception $e) {

        http_response_code(401);

        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage(),
            "status" => false
        ));
    }
} 

echo json_encode(array(
    "message" => "Access denied: ",
    "status" => false
));
