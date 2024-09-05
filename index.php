<?php
// php -S 127.0.0.1:8000
// 1. Include the class
require './src/library/Router.php';
include_once './config/database.php';

// 2. Init the Router class
$router = new Router();

// 3. Define routes

// 3.1 GET method
$router->get('/', function(){
    echo "Hello";
});

// 3.2 POST method
$router->post('/api/user/register', function(){
    include_once './src/register.php';
});

// 3.2 POST method
$router->post('/api/user/login', function(){
    include_once './src/login.php';
});

// 3.3 POST method
$router->post('/api/user/verify', function(){
    include_once './src/protected.php';
});

// 3.4 POST method with params
$router->post('/api/student/add', function(){
    // Code goes here
    // echo $params['id'];
    include_once './src/student/add.php';
});

// 3.5 POST method with params
$router->post('/api/student/update/:id', function($params){
    // Code goes here
    echo "update student";
});

// 3.6 POST method with params
$router->post('/api/student/get/:id', function($params){
    // Code goes here
    echo "get student";
});


// 3.7 POST method with params
$router->post('/api/student/delete/:id', function($params){
    // Code goes here
    echo "delete student";
});



// 4. Finish
$router->listen();