
<?php

include_once 'connection.php';
$connection = new Connection();
$cnx = $connection->connect();

$user = (isset($_POST['user'])) ? $_POST['user'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';

$query = "SELECT * FROM login WHERE user= :user AND password= :password";
$result = $cnx ->prepare($query);
$user = htmlentities (addslashes($user));
$password = htmlentities (addslashes($password));
$result->bindValue(":user", $user);
$result->bindValue(":password", $password);

$result -> execute();

if ($result->rowCount() > 0){
    session_start();

    $_SESSION['admin'] = $user;
    

    $data = [array("message" => true )];
}else{
    $data = [array("message" => false)];  
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);
$cnx = null;

?>


