<?php
include 'db.php';

if ( count( $_POST ) > 0 ) {
    if ( $_POST['type'] == 1 ) {   
		$db->insertStudent($_POST);
    }
}
if ( count( $_POST ) > 0 ) {
    if ( $_POST['type'] == 2 ) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $city = $_POST['city'];
        $sql = "UPDATE `crud` SET `name`='$name',`email`='$email',`phone`='$phone',`city`='$city' WHERE id=$id";
        if ( mysqli_query( $conn, $sql ) ) {
            echo json_encode( [ "statusCode" => 200 ] );
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error( $conn );
        }
        mysqli_close( $conn );
    }
}
if ( count( $_POST ) > 0 ) {
    if ( $_POST['type'] == 3 ) {
        $id = $_POST['id'];
        $sql = "DELETE FROM `crud` WHERE id=$id ";
        if ( mysqli_query( $conn, $sql ) ) {
            echo $id;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error( $conn );
        }
        mysqli_close( $conn );
    }
}
if ( count( $_POST ) > 0 ) {
    if ( $_POST['type'] == 4 ) {
        $id = $_POST['id'];
        $sql = "DELETE FROM crud WHERE id in ($id)";
        if ( mysqli_query( $conn, $sql ) ) {
            echo $id;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error( $conn );
        }
        mysqli_close( $conn );
    }
}

?>