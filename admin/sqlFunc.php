<?php
// require '../backend/databaseconnection.php';
// class select{
//     function selectQuery($tableName)
//     {
//         $sql="SELECT * FROM '$tableName'";
//         $result=mysqli_query($conn,$sql);
//         return $result;
//         }
// @override
//     function selectQuery($tableName, $id){
//         $sql = "SELECT * from `$tableName` WHERE id=$id ";
//         $result = mysqli_query($conn, $sql);
//         return $result;
//     }
// }

require '../backend/databaseconnection.php';

class Select {
    protected $conn;

    function __construct($connection) {
        $this->conn = $connection;
    }

    function selectQuery($tableName) {
        $sql = "SELECT * FROM `$tableName`";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }

    function selectQueryById($tableName, $id) {
        $sql = "SELECT * FROM `$tableName` WHERE id=$id";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
}

class Delete {
    protected $conn;

    function __construct($connection) {
        $this->conn = $connection;
    }

    function deleteQueryById($tableName, $id) {
        $sql = "DELETE FROM $tableName WHERE id=$id";
        $result = mysqli_query($this->conn, $sql);
        return $result;
    }
}


?>
