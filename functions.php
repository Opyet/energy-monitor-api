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
        $Db = new DbHandler();
        $todayReading = 0.0;
        $thisMonthReading = 0.0;

//        [tm_sec] - seconds
//        [tm_min] - minutes
//        [tm_hour] - hour
//        [tm_mday] - day of the month
//        [tm_mon] - month of the year (January=0)
//        [tm_year] - Years since 1900
//        [tm_wday] - Day of the week (Sunday=0)
//        [tm_yday] - Day of the year
//        [tm_isdst] - Is daylight savings time in effect


        //accumulated reading for the day
        $localtime = localtime(time(),true);
        $currentTime = time();


        $timeTomorrowStarts = strtotime('tomorrow', $currentTime);
        $thisMonth = $localtime['tm_mon'] + 1;
        $nextMonth = $localtime['tm_mon'] + 2;
        if($localtime['tm_mon'] == 11){
            $year = $localtime['tm_year']+1901;
        }else{
            $year = $localtime['tm_year']+1900;
        }

        $timeTodayStarted = strtotime('today');
        $timeThisMonthStarted = strtotime('01-'.$thisMonth.'-'.$year, $currentTime);
        $timeNextMonthStarts = strtotime('01-'.$nextMonth.'-'.$year, $currentTime);
        $timeThisYearStarted = strtotime('01-01-'.$year, $currentTime);

//        $timeNextMonthStarts = $nextMonth - (($localtime['tm_mday']-1) * 24 * 60 *60) + ($localtime['tm_hour']* 60 *60) - ($localtime['tm_min'] *60);

//        return date("F j, Y, g:i a",$timeTomorrowStarts). '<br><br>'. date("F j, Y, g:i a",$timeNextMonthStarts);
       // return date("F j, Y, g:i a",$timeTodayStarted). '<br><br>'. date("F j, Y, g:i a",$timeThisMonthStarted). '<br><br>'. date("F j, Y, g:i a",$timeThisYearStarted);


        //fetch the last record on table
        $select = $Db->select('readings','*',"nodeId = '$node'",'created DESC LIMIT 1');
        $prevTimestamp = $select[0]['created'];
        $prevTimestamp = strtotime($prevTimestamp);
        $prevDayAcc = $select[0]['dayAccumulation'];
        $prevMonAcc = $select[0]['monthAccumulation'];
        $prevYearAcc = $select[0]['yearAccumulation'];

        //DETERMINE DAILY READING
        if($timeTodayStarted < $prevTimestamp){ //check if a new day has started
            $todayReading = $prevDayAcc + $reading;
        }else{ $todayReading = $reading;}

        //DETERMINE MONTHLY READING
        if($timeThisMonthStarted < $prevTimestamp){ //check if a month day has started
            $thisMonthReading = $prevMonAcc + $reading;
        }else{ $thisMonthReading = $reading;}

        //DETERMINE YEARLY READING
        if($timeThisYearStarted < $prevTimestamp){ //check if a year day has started
            $thisYearReading = $prevYearAcc + $reading;
        }else{ $thisYearReading = $reading;}

        

        $insert = $Db->insert('readings',[$node,$reading,$todayReading,$thisMonthReading,$thisYearReading,'NOW()'],'nodeId,reading,dayAccumulation,monthAccumulation,yearAccumulation,created');

        if(is_int($insert)){
            $this->result = true;
        }else{
            $this->result = false;
        }
        return $this->result;

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

    public function getDayReading($node,$day){ //format yyyy-mm-dd
        $Db = new DbHandler();

        $dayArray = explode('-',$day);
        $dayEnd = $dayArray[0].'-'.$dayArray[1].'-'.($dayArray[2]+1).' 00:00:00';
        $day = $day.' 00:00:00';
//        $day = strtotime($day);
//        $dayEnd = $day + (24*60*60);
        $select = $Db->select('readings','*',"nodeId = '$node' AND created < '$dayEnd' AND created >= '$day'",'created DESC');

        if(count($Db->query_array)>0){
            $this->result = $Db->query_array;//accumulated reading of date successfully fetched
        }elseif($select == false){
            $this->result = 0;//no reading belonging to node in db for that date
        }
        else{
            $this->result = 0;//no reading belonging to node in db for that date
        }
        return $this->result;
    }



    public function  getMonthReading($node,$month){//format yyyy-mm
        $Db = new DbHandler();

        $dateArray = explode('-',$month);
        $monthEnd = $dateArray[0].'-'.($dateArray[1]+1).'-01 00:00:00';
        $month = $month.'-01 00:00:00';

        $select = $Db->select('readings','*',"nodeId = '$node' AND created < '$monthEnd' AND created >= '$month'",'created DESC');

        if(count($Db->query_array)>0){
            $this->result = $Db->query_array;//accumulated reading of date successfully fetched
        }elseif($select == false){
            $this->result = 0;//no reading belonging to node in db for that date
        }
        else{
            $this->result = 0;//no reading belonging to node in db for that date
        }
        return $this->result;
    }
    public function  getYearReading($node,$year){//format yyyy
        $Db = new DbHandler();

        $yearEnd = ($year +1).'-01-01 00:00:00';
        $year = $year.'-01-01 00:00:00';

        $select = $Db->select('readings','*',"nodeId = '$node' AND created < '$yearEnd' AND created >= '$year'",'created DESC');

        if(count($Db->query_array)>0){
            $this->result = $Db->query_array;//accumulated reading of date successfully fetched
        }elseif($select == false){
            $this->result = 0;//no reading belonging to node in db for that date
        }
        else{
            $this->result = 0;//no reading belonging to node in db for that date
        }
        return $this->result;
    }

    public function  setIndex($node,$daily,$monthly,$yearly){
        if($daily==null && $monthly ==null && $yearly==null){
            return 'values empty';
        }else{
            $Db = new DbHandler();

            $select = $Db->select('nodes','*',"id = '$node'");

            if(count($Db->query_array)>0){
                if($daily == null){
                    $daily = $Db->query_array[0]['dayIndex'];
                }
                if($monthly == null){
                    $monthly = $Db->query_array[0]['monthIndex'];
                }
                if($yearly == null){
                    $yearly = $Db->query_array[0]['yearIndex'];
                }

                $update = $Db->update('nodes',['dayIndex => '.$daily,'monthIndex => '.$monthly,'yearIndex => '.$yearly],['id => '.$node]);
                return true;

            }elseif(empty($select)){
                return 'node not found';
            }
            else{
                return 'node not found';
            }
        }
    }
    public function  getIndex($node){
        $Db = new DbHandler();

        $select = $Db->select('nodes','*',"id = '$node'");

        if(count($Db->query_array)>0){
            $this->result = $Db->query_array[0];

        }elseif(empty($select)){
            return 'node not found';
        }else{
            $this->result = 0;
        }
        return $this->result;
    }
    // http://arduino.esp8266.com/stable/package_esp8266com_index.json
}


