<?php
include("configdb.php");
class DbOperations {
    private $conn = "";

    function getConn() {
        return $this->conn;
    }

    function __construct($conn) {
        $this->conn = $conn;
    }

    function select($select, $tableName, $whereClause) {
        // check for optional where clause
        $whereSQL = '';
        if(!empty($whereClause)) {
            // check to see if the 'where' keyword exists
            if(substr(strtoupper(trim($whereClause)), 0, 5) != 'WHERE') {
                // not found, add key word
                $whereSQL = " WHERE ".$whereClause;
            } else {
                $whereSQL = " ".trim($whereClause);
            }
        }
        if(is_array($select)) {
            // start the actual SQL statement
            $sql = "SELECT ";
            $sql .= implode(', ', $select);
            $sql .= " FROM ".$tableName." ";
        } else {
            // start the actual SQL statement
            $sql = "SELECT ".$select." FROM ".$tableName." ";
        }
        // append the where statement
        $sql .= $whereSQL;
        // run and return the query result
        return mysqli_query($this->conn,$sql);
    }

    function insert($tableName,$columnData) {
        // retrieve the keys of the array (column titles)
        $fields = array_keys($columnData);
        // build the query
        $sql = "INSERT INTO ".$tableName."
        (`".implode('`,`', $fields)."`)
        VALUES('".implode("','", $columnData)."')";

        // run and return the query result resource
        return mysqli_query($this->conn,$sql);
    }

    function update($tableName, $columnData, $whereClause) {
        // check for optional where clause
        $whereSQL = '';
        if(!empty($whereClause)) {
            // check to see if the 'where' keyword exists
            if(substr(strtoupper(trim($whereClause)), 0, 5) != 'WHERE') {
                // not found, add key word
                $whereSQL = " WHERE ".$whereClause;
            } else {
                $whereSQL = " ".trim($whereClause);
            }
        }
        // start the actual SQL statement
        $sql = "UPDATE ".$tableName." SET ";
        // loop and build the column /
        $sets = array();
        foreach($columnData as $column => $value) {
             $sets[] = "`".$column."` = '".$value."'";
        }
        $sql .= implode(', ', $sets);
        // append the where statement
        $sql .= $whereSQL;
        // run and return the query result
        return mysqli_query($this->conn,$sql);
    }

    function delete($tableName, $whereClause) {
        // check for optional where clause
        $whereSQL = '';
        if(!empty($whereClause)) {
            // check to see if the 'where' keyword exists
            if(substr(strtoupper(trim($whereClause)), 0, 5) != 'WHERE') {
                // not found, add keyword
                $whereSQL = " WHERE ".$whereClause;
            } else {
                $whereSQL = " ".trim($whereClause);
            }
        }
        // build the query
        $sql = "DELETE FROM ".$tableName.$whereSQL;
        // run and return the query result resource
        return mysqli_query($this->conn,$sql);
    }

}
?>
