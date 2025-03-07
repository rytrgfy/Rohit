<?
session_start();
include "dbconn.php";


$user_id_del = $_SESSION['user_id']; //get user_id


if(isset($_SESSION['user_id'])){
    

    $sql_delete_command = "DELETE FROM signup WHERE id= $user_id_del;";
    
    if($conn->query($sql_delete_command)){
        echo "<script>
                alert('User deleted successfully');
                window.location.href='logout.php';
              </script>";
        exit();
    }
}
?>