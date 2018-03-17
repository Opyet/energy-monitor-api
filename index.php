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

$app ->get("/loginUser", "login");
$app ->get("/fetchCategories", "fetchCategories");
$app ->get("/fetchNodes", "fetchNodes");

$app ->get("/getLastReading", "getLastReading");

$app ->get("/setReading","setReading");
$app ->get("/setIndex","setIndex");

$app ->get("/getIndex","getIndex");

$app ->get("/getDayReading","getDayReading");
$app ->get("/getMonthReading","getMonthReading");
$app ->get("/getYearReading","getYearReading");



$app ->run();

use apiClass\DbEntry as DbEntry;

/**
 * @param $name
 * this uses the GET METHOD, to fetch
 * Note: the route has :name because the function expects an argument
 */


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
//        echo $_GET['username'];
//        echo $_GET['password'];

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

        if($output === true){
            // echo "$output";
            echo json_encode(
                [
                    "status" => true,
                    "message"=> "reading set successfully",
                    "data"=> null
                ]
            );
        }
        else{
            header('Content-type: application/json');
            $msg = "Reading upload failed";
            errormessage($msg);
        }

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

function getDayReading(){
    if($_GET){
        $node = $_GET['node'];
        $day = $_GET['date'];//format yyyy-mm-dd

        $result = new DbEntry();
        $output = $result->getDayReading($node,$day);

        if($output == 0){
            header('Content-type: application/json');
            $msg = "no reading belonging to node for input date";
            errormessage($msg);
        }else{
            echo json_encode(
                $output
            );

        }
    }else{
        header('Content-type: application/json');
        $msg = "no request sent";
        errormessage($msg);
    }
}

function  getMonthReading(){
    if($_GET){
        $node = $_GET['node'];
        $month = $_GET['month'];//format yyyy-mm

        $result = new DbEntry();
        $output = $result->getMonthReading($node,$month);

        if($output == 0){
            header('Content-type: application/json');
            $msg = "no reading belonging to node for input month";
            errormessage($msg);
        }else{
            echo json_encode(
                $output
            );

        }
    }else{
        header('Content-type: application/json');
        $msg = "no request sent";
        errormessage($msg);
    }
}

function  getYearReading(){
    if($_GET){
        $node = $_GET['node'];
        $year = $_GET['year'];//format yyyy

        $result = new DbEntry();
        $output = $result->getYearReading($node,$year);

        if($output == 0){
            header('Content-type: application/json');
            $msg = "no reading belonging to node for input year";
            errormessage($msg);
        }else{
            echo json_encode(
                $output
            );

        }
    }else{
        header('Content-type: application/json');
        $msg = "no request sent";
        errormessage($msg);
    }
}


function  setIndex(){
    if($_GET){
        $node = $_GET['node'];
        $dailyIndex = $_GET['dailyIndex'];
        $monthlyIndex = $_GET['monthlyIndex'];
        $yearlyIndex = $_GET['yearlyIndex'];

        $result = new DbEntry();
        $output = $result->setIndex($node,$dailyIndex,$monthlyIndex,$yearlyIndex);


        if($output === true){
            echo json_encode(
                [
                    "status" => true,
                    "message"=> "Usage index was successfully updated",
                    "data"=> null
                ]
            );
        }
        elseif($output == 'values empty'){
            echo json_encode(
                [
                    "status" => false,
                    "message"=> "No values were sent, check entry",
                    "data"=> null
                ]
            );
        }elseif($output == 'node not found'){
            header('Content-type: application/json');
            $msg = "Node entry not found";
            errormessage($msg);
        }
        else{
            header('Content-type: application/json');
            $msg = "Update failed";
            errormessage($msg);
        }
    }
}

function  getIndex(){
    if($_GET){
        $node = $_GET['node'];

        $result = new DbEntry();
        $output = $result->getIndex($node);

        if(array($output)){
            echo json_encode(
                $output
            );
        }elseif($output == 'node not found'){
            header('Content-type: application/json');
            $msg = "Node entry not found";
            errormessage($msg);
        }
        else{
            header('Content-type: application/json');
            $msg = "No reading belonging to node in db";
            errormessage($msg);
        }
    }else{
        header('Content-type: application/json');
        $msg = "No request sent";
        errormessage($msg);
    }
}



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
