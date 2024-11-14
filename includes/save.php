<?php
include 'db.php';
if ( count( $_POST ) > 0 ) {
    if ( $_POST['type'] == 1 ) {   
		$db->insertStudent($_POST);
    }
}
if ( count( $_POST ) > 0 ) {
    if ( $_POST['type'] == 2 ) { 
        $db->updateStudent($_POST);
    }
}
if ( count( $_POST ) > 0 ) {
    if ( $_POST['type'] == 3 ) {
        $db->deleteStudent($_POST);
    }
}
if ( count( $_POST ) > 0 ) {
    if ( $_POST['type'] == 4 ) {
        $db->deleteSelectedStudent($_POST);
    }
}

?>