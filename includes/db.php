<?php
class DB {
    private $host = 'localhost';
    private $username = 'root';
    private $password = 'mysql';
    private $dbname = 'yhs_students';
    private $conn;

    public function __construct() {
        $this->connect();
        $this->createDatabase();
    }

    private function connect() {
        $this->conn = new mysqli( $this->host, $this->username, $this->password );
        // Check connection
        if ( $this->conn->connect_error ) {
            die( "Connection failed: " . $this->conn->connect_error );
        }
    }

    private function createDatabase() {
        $sql = "CREATE DATABASE IF NOT EXISTS " . $this->dbname;
        if ( $this->conn->query( $sql ) === true ) {
            $this->conn->select_db( $this->dbname );
        } else {
            die( "Error creating database: " . $this->conn->error );
        }
    }

    public function query( $sql ) {
        $result = $this->conn->query( $sql );
        if ( $result === false ) {
            echo "Error: " . $this->conn->error;
            return false;
        }
        return $result;
    }

    public function prepareAndExecute( $sql, $types, ...$params ) {
        $stmt = $this->conn->prepare( $sql );
        if ( !$stmt ) {
            echo "Prepare failed: " . $this->conn->error;
            return false;
        }
        $stmt->bind_param( $types, ...$params );
        $stmt->execute();
        if ( $stmt->error ) {
            echo "Execution failed: " . $stmt->error;
            return false;
        }   
        if ( str_starts_with( strtoupper( $sql ), "SELECT" ) ) {
            return $stmt->get_result(); 
        } else {
            return true; 
        }
    }

    // Fetch all results as an associative array
    public function fetchAll( $sql ) {
        $result = $this->query( $sql );
        if ( $result ) {
            return $result->fetch_all( MYSQLI_ASSOC );
        }
        return [];
    }

    // Fetch a single row as an associative array
    public function fetchRow( $sql ) {
        $result = $this->query( $sql );
        if ( $result ) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function insertStudent( $post ) {
        $name = isset( $post['name'] ) ? $post['name'] : '';
        $email = isset( $post['email'] ) ? $post['email'] : '';
        $phone = isset( $post['name'] ) ? $post['phone'] : '';
        $city = isset( $post['city'] ) ? $post['city'] : '';
        $gender = isset( $post['gender'] ) ? $post['gender'] : '';
        $sql = "INSERT INTO students (name, email, phone, city, gender) VALUES (?, ?, ?, ?, ?)";
        $result = $this->prepareAndExecute( $sql, 'sssss', $name, $email, $phone, $city, $gender );
        if ( $result ) {
            echo json_encode( ["statusCode" => 200] );
        } else {
            echo "Error: " . $sql . "<br>";
        }

    }
    public function updateStudent($post){
        $id = isset( $post['id'] ) ? $post['id'] : '';
        $name = isset( $post['name'] ) ? $post['name'] : '';
        $email = isset( $post['email'] ) ? $post['email'] : '';
        $phone = isset( $post['name'] ) ? $post['phone'] : '';
        $city = isset( $post['city'] ) ? $post['city'] : '';
        $gender = isset( $post['gender'] ) ? $post['gender'] : '';
        $sql = "UPDATE students SET `name`='$name',`email`='$email',`phone`='$phone',`city`='$city',gender = '$gender' WHERE id=$id";

       if( $this->query($sql)){
            echo json_encode( ["statusCode" => 200] );
       }else {
        echo "Error: " . $sql . "<br>";
       }
    }

    public function deleteStudent($post){
        $id = isset( $post['id'] ) ? $post['id'] : '';
        $sql = "DELETE FROM `students` WHERE id = $id ";
        if ( $this->query($sql)) {
            echo $id;
        } else {
            echo "Error: " . $sql . "<br>" . $sql;
        }
    }

    public function deleteSelectedStudent($post){
        $id = isset( $post['id'] ) ? $post['id'] : '';
        $sql = "DELETE FROM crud WHERE id in ($id)";
        if ( $this->query($sql) ) {
            echo $id;
        } else {
            echo "Error: " . $sql . "<br>" . $sql;
        }
      
    }

    public function getStudents(){
            $sql = "SELECT * FROM students";
            return $this->fetchAll($sql);

    }

    // Close the database connection
    public function close() {
        if ( $this->conn ) {
            $this->conn->close();
        }
    }

    // Destructor - Closes the connection when the object is destroyed
    public function __destruct() {
        $this->close();
    }
}

$db = new DB();

$sql = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    city VARCHAR(15) NOT NULL,
    gender VARCHAR(10) NOT NULL
)";

$db->query( $sql );