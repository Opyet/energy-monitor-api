<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/26/15
 * Time: 10:26 PM
 */

namespace apiClass;

include 'config.php';

class DbHandler {

    private $tableName;
    private $con;
    private $query;
    private $_result = array();
    public $query_array = array();
    private $_numResults;

    public function __construct() {
        $this->host = HOSTNAME;
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->db = DBNAME;
        $this->con = mysqli_connect($this->host, $this->username, $this->password, $this->db) or die("Could not connect to " + $this->host);
    }

    public function setTable($table_name) {
        $this->tableName = $table_name;
    }

    public function closeDb() {
        if (isset($this->con)) {
            mysqli_close($this->con);
            unset($this->con);
        }
    }

//    public function selectDatabase($dbName) {
//        $this->dbName = $dbName;
//        mysql_select_db($this->dbName, $this->con) or die("Could not select database" + $this->dbName);
//    }

    public function query($query){
        return $this->query = @mysqli_query($this->con, $query);
    }
    
    public function insert($table,$values = array(),$cols = null){
        //NOTE: $values must be an array

        if($this->tableExists($table)){
            $this->query = "INSERT INTO ".$table;
            if($cols != null){
                $this->query .= '('.$cols.')';
            }

            for($i=0; $i < count($values); $i++){
                if(is_string($values[$i])){
                    $values[$i] = "'".$values[$i]."'";
                    if($values[$i] == "'NOW()'"){
                        $values[$i] = 'NOW()';
                    }
                }
            }
            $values = implode(',',$values);
            $this->query .= ' VALUES ('.$values.')';

            $insert = @mysqli_query($this->con,$this->query);
            if($insert){
                return $this->con->insert_id;
            }else{
                return false;
            }
        }
    }

    public function update($table,$set = array(['column => value']),$where = array('key => value')){
        if($this->tableExists($table)){
            //parse the where values
            //even values (including 0) contain the where rows
            //odd values contain the clauses for the row

            $assoc_array_where = array();
            $where_query ='';
            for($i = 0;$i < count($where);$i++){
                $assoc_array_where = explode('=>',$where[$i]);

                if(($i+1) < count($where)){
                    $where_query .= $assoc_array_where[0]." = '".$assoc_array_where[1]."' AND ";
                }else{
                    $where_query .= $assoc_array_where[0]." = '".$assoc_array_where[1]."'";
                }
            }
//            print_r($where_query);

            $set_query ='';

//            print_r(count($set));

            for($i = 0;$i < count($set);$i++){
                $assoc_array_set = explode('=> ',$set[$i]);
//                $assoc_array_set = explode('=>',$assoc_array_set[0]);
//                print_r($assoc_array_set);

                if(($i+1) < count($set)){
//                    print_r($assoc_array_set[1]);
                    if($assoc_array_set[1] == 'NOW()'){
                        $set_query .= $assoc_array_set[0]." = ".$assoc_array_set[1].", ";
                    }else{
                        $set_query .= $assoc_array_set[0]." = '".$assoc_array_set[1]."', ";
                    }
                }else{
//                    print_r($assoc_array_set);
                    if($assoc_array_set[1] == 'NOW()'){
                        $set_query .= $assoc_array_set[0]." = ".$assoc_array_set[1];
                    }else{
                        $set_query .= $assoc_array_set[0]." = '".$assoc_array_set[1]."'";
                    }
                }
//                print_r($set_query);
//               var_dump($assoc_array_where);
            }

            $update_query = "UPDATE ".$table." SET ".$set_query;
            if($where !=null){
                $update_query .= " WHERE ".$where_query;
            }
//            print_r($update_query);
            $update = @mysqli_query($this->con,$update_query);
            if($update){
                return $set_query;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }

    public function select($table,$rows = '*',$where = null, $order = null){

        $sel_query = "SELECT ". $rows." FROM ".$table;
        if($where != null){
            $sel_query .= " WHERE ".$where;
        }
        if($order != null){
            $sel_query .= " ORDER BY ".$order;
        }

//        print_r($this->tableExists($table));
//        echo '<br><br>';
        if($this->tableExists($table)){
            $query = mysqli_query($this->con,$sel_query);

            if($query){
                $this->_numResults = mysqli_num_rows($query);
//                echo '<br>'.$this->_numResults.'</br>';
                if($this->_numResults == 0){
                    return false;
                }else{
                    for($i = 0; $i< $this->_numResults;$i++){
                        $this->query_array[$i] = mysqli_fetch_assoc($query);
                    }
                }
//                $this->disconnect();
                return $this->query_array;
            }else{
                return 'query not successful';
            }
        }else{
            return 'table does not exist <br>';
        }
    }

    public function delete($table, $where = null){
        if($this->tableExists($table)){
            if($where == null){
                $this->query = "DELETE FROM ".$table;
            }else{
                $this->query = "DELETE FROM ".$table." WHERE " .$where;
            }

            $delete = @mysqli_query($this->con,$this->query);
            if($delete){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function tableExists($table){
        $this->query = "SHOW TABLES FROM ". DBNAME ." LIKE '". $table."'";
        $tablesInDb = @mysqli_query($this->con,$this->query);
//        echo '<br>';
//        print_r($this->query);
//        echo '<br>';
        if(!$tablesInDb){
//            print_r('false');
        }
        if($tablesInDb){
            if(mysqli_num_rows($tablesInDb)==1){
                return true;
            }else{
                return false;
            }

        }else{
            print_r('Table not found');
        }
    }
}
