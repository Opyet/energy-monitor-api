<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/26/15
 * Time: 10:26 PM
 */


require 'vendor/autoload.php';
require_once 'functions.php';
require_once 'DbHandler.php';




$app = new \Slim\Slim(array(
    'mode' => 'development'
));

$app->config('debug', true);


/**
 * The functions are routed with the
 *api calls. The api calls are the urls
 * you gave for access. E.g http://localhost/user/create
 * /user/create is routed with the function name addUser
 *
 */


//$app ->get('/addPatient', 'examples');
$app ->get("/name", "test");
$app ->post("/loginUser", "login");
$app ->post("/fetchCategories", "fetchCategories");
$app ->post("/fetchNodes", "fetchNodes");
$app ->post("/getLastReading", "getLastReading");
$app ->post("/setReading","setReading");



$app ->run();

use apiClass\DbEntry as DbEntry;

/**
 * @param $name
 * this uses the GET METHOD, to fetch
 * Note: the route has :name because the function expects an argument
 */
//test

/**
 * Register a user with the requested params
 */

function test(){
    echo json_encode(
        [
            "status" => true,
            "message"=> "user added successfully",
            "data"=> null
        ]
    );
}

function login(){
    if($_GET){
//        echo $_POST['username'];
//        echo $_POST['password'];

        if($_GET['username'] && $_GET['password']){
            $username = $_GET['username'];
            $password = $_GET['password'];
            if($username && $password){
                $result = new DbEntry();
                $out = $result->loginUser($username,$password);

                if($out == 1){
                    echo json_encode(
                        [
                            "status" => true,
                            "message"=> "user logged in successfully",
                            "data"=> null
                        ]
                    );
                }
                else{
                    header('Content-type: application/json');
                    $msg = "login details incorrect";
                    errormessage($msg);
                }
            }
        }else{
            echo 'not seeing';
        }
    }else{
        echo 'request not made';
    }
}

function fetchCategories(){
    $result = new DbEntry();
    $output = $result->fetchCategories();

    if(array($output)){
        echo json_encode(
            $output
        );
    }else{
        header('Content-type: application/json');
        $msg = "login details incorrect";
        errormessage($msg);
    }
}

function fetchNodes(){
    if($_GET){
        $category = $_GET['category'];

        $result = new DbEntry();
        $output = $result->fetchNodes($category);

        if(array($output)){
            echo json_encode(
                $output
            );
        }else{
            header('Content-type: application/json');
            $msg = "no node belonging to category in db";
            errormessage($msg);
        }
    }else{
        header('Content-type: application/json');
        $msg = "no request sent";
        errormessage($msg);
    }
}

function setReading(){
    if($_GET){
        $node = $_GET['node'];
        $reading = $_GET['reading'];

        $result = new DbEntry();
        $output = $result->setReading($node,$reading);

        print_r($output);

    }else{
        header('Content-type: application/json');
        $msg = "no request sent";
        errormessage($msg);
    }
}

function getLastReading(){
    if($_GET){
        $node = $_GET['node'];

        $result = new DbEntry();
        $output = $result->getLastReading($node);

        if(array($output)){
            echo json_encode(
                $output
            );
        }else{
            header('Content-type: application/json');
            $msg = "no reading belonging to node in db";
            errormessage($msg);
        }
    }else{
        header('Content-type: application/json');
        $msg = "no request sent";
        errormessage($msg);
    }
}

function getDayReading(){}
function  getMonthReading(){}
function  getYearReading(){}
function  setIndex(){}
function  getIndex(){}



function cleanInput($field){

    $field = trim($field);
    $field = htmlspecialchars($field);
    $field = strip_tags($field);
    $field = stripcslashes($field);
    $field = mysqli_escape_string($this->conn,$field);

    return $field;
}

function errormessage($msg){
    echo json_encode([
        'status'=> false,
        'message'=> $msg,
        'data'=> null
    ]);
}
