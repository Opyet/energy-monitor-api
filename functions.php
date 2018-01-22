<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/26/15
 * Time: 10:37 PM
 */

namespace apiClass;

include 'DbHandler.php';



class DbEntry{

    public  $conn;
    private $result;


    public function __construct(){
        //DATABASE CONNECTION
        $this->conn = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DBNAME);
        if (!$this->conn) {
            echo "Unable to connect to db";
        }
//        else{echo "database connection successful";}
    }


    public function loginUser($username,$password){
        $Db = new DbHandler();
        $select = $Db->select('login','*',"username = '$username' AND password = '$password'");

        if(count($Db->query_array)>0){
            $this->result = 1;//login successful
        }else{
            $this->result = 0;//login failed
        }
        return $this->result;
    }

    //to fetch different categories of nodes
    public function fetchCategories(){
        $Db = new DbHandler();
        $select = $Db->select('categories','*');//select all from table 'categories'

        if(count($Db->query_array)>0){
            $this->result = $Db->query_array;//categories successfully fetched
        }else{
            $this->result = 0;//no category in db
        }
        return $this->result;
    }

    //to fetch the nodes pertaining to a particular category
    public function fetchNodes($category){
        $Db = new DbHandler();
        $select = $Db->select('nodes','*',"categoryId = '$category'");//select all from table 'nodes' with particular category

        if(count($Db->query_array)>0){
            $this->result = $Db->query_array;//nodes successfully fetched
        }else{
            $this->result = 0;//no node belonging to category in db
        }
        return $this->result;
    }

    public function setReading($node,$reading){
        //accumulated reading for the day
        $localtime = localtime();

        $currentTime = ($localtime[2]*3600)+($localtime[1]*60)+($localtime[0]);
        $timeDayStarted = (time() - $currentTime); //using the time spent since 1960

        $timeMonthStarted = (time() - (($localtime[3]*86400)+$currentTime));
//        return $timeDayStarted;
        return strtotime("this month",time()).'<br>'.$timeMonthStarted ;

//        [tm_sec] - seconds
//        [tm_min] - minutes
//        [tm_hour] - hour
//        [tm_mday] - day of the month
//        [tm_mon] - month of the year (January=0)
//        [tm_year] - Years since 1900
//        [tm_wday] - Day of the week (Sunday=0)
//        [tm_yday] - Day of the year
//        [tm_isdst] - Is daylight savings time in effect


//        $Db = new DbHandler();
//        $insert = $Db->insert();
    }
    public function getLastReading($node){
        $Db = new DbHandler();
        $select = $Db->select('readings','*',"nodeId = '$node'",'created DESC');

        if(count($Db->query_array)>0){
            $this->result = $Db->query_array[0];//reading successfully fetched
        }else{
            $this->result = 0;//no reading belonging to node in db
        }
        return $this->result;
    }

    public function getDayReading(){}
    public function  getMonthReading(){}
    public function  getYearReading(){}
    public function  setIndex(){}
    public function  getIndex(){}
}
