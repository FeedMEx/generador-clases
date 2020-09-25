<?php
include_once 'connection.php';
$connection = new Connection();
$cnx = $connection->connect();

$name = (isset($_POST['name'])) ? $_POST['name'] : '';
$ip_adress = (isset($_POST['ip_adress'])) ? $_POST['ip_adress'] : '';
$home_adress = (isset($_POST['home_adress'])) ? $_POST['home_adress'] : '';
$phone = (isset($_POST['phone'])) ? $_POST['phone'] : '';
$month_payment = (isset($_POST['month_payment'])) ? $_POST['month_payment'] : '';
$mbps = (isset($_POST['mbps'])) ? $_POST['mbps'] : '';
$install_date = (isset($_POST['install_date'])) ? $_POST['install_date'] : '';
$status = (isset($_POST['status'])) ? $_POST['status'] : '';

$type = (isset($_POST['type'])) ? $_POST['type'] : '';
$clients_view = (isset($_POST['clients_view'])) ? $_POST['clients_view'] : '';
$order_ip = (isset($_POST['order_ip'])) ? $_POST['order_ip'] : '';
$client_id = (isset($_POST['client_id'])) ? $_POST['client_id'] : '';

$history_id = (isset($_POST['history_id'])) ? $_POST['history_id'] : '';
$description = (isset($_POST['description'])) ? $_POST['description'] : '';
$payment_date = (isset($_POST['payment_date'])) ? $_POST['payment_date'] : '';
$payment = (isset($_POST['payment'])) ? $_POST['payment'] : '';

$current_pass = (isset($_POST['current_pass'])) ? $_POST['current_pass'] : '';
$new_pass = (isset($_POST['new_pass'])) ? $_POST['new_pass'] : '';


switch($type){
    case 'new_client':
        $query = "CALL add_client('$name','$ip_adress','$home_adress','$phone','$month_payment','$mbps','$install_date')";			
        $result = $cnx ->prepare($query);
        $result -> execute();
        break;

    case 'edit_client':        
        $query = "CALL edit_client('$client_id','$name','$ip_adress','$home_adress','$phone','$month_payment','$mbps','$install_date')";		
        $result = $cnx ->prepare($query);
        $result -> execute();        
        break;

    case 'delete_client':        
        $query = "CALL delete_client('$client_id')";		
        $result = $cnx ->prepare($query);
        $result->execute();                           
        break;

    case 'consult_client':    
        $query = "CALL consult_client ('$clients_view','$order_ip')";
        $result = $cnx ->prepare($query);
        $result -> execute();        
        $data=$result -> fetchAll(PDO::FETCH_ASSOC);
        break;
    
    case 'search_client':    
        $query = "CALL search_client ('$clients_view','$name%')";
        $result = $cnx ->prepare($query);
        $result -> execute();        
        $data=$result -> fetchAll(PDO::FETCH_ASSOC);
        break;
    
    case 'upload_client':    
        $query = "CALL upload_client ('$client_id')";
        $result = $cnx ->prepare($query);
        $result -> execute();        
        $data=$result -> fetchAll(PDO::FETCH_ASSOC);
        break;
    
    case 'edit_status':
           
        $query = "CALL edit_status('$client_id','$description','$payment_date')";		
        $result = $cnx ->prepare($query);
        $result -> execute(); 
        break;
    
    case 'consult_history':
        $query = "CALL consult_history ('$client_id')";
        $result = $cnx ->prepare($query);
        $result -> execute();        
        $data=$result->fetchAll(PDO::FETCH_ASSOC);
        break;
    
    case 'delete_history':        
        $query = "CALL delete_history ('$history_id')";		
        $result = $cnx ->prepare($query);
        $result -> execute();                           
        break;
    
    case 'edit_history':        
        $query = "CALL edit_history('$history_id','$description','$payment_date','$payment')";		
        $result = $cnx ->prepare($query);
        $result -> execute();   
        break;
    
    case 'add_history':        
        $query = "CALL add_history('$description','$payment_date','$payment','$client_id')";		
        $result = $cnx ->prepare($query);
        $result -> execute();   
        break;  

    case 'get_last_client':
        $query = "SELECT * FROM clients ORDER BY id DESC LIMIT 1";
        $result = $cnx ->prepare($query);
        $result->execute();
        $data=$result->fetchAll(PDO::FETCH_ASSOC);       
        break;
    
    case 'validate_password':
        $query = "SELECT * FROM login WHERE user='admin' AND password=?";
        $result = $cnx ->prepare($query);
        $result -> execute(array($current_pass));
    
        if ($result->rowCount() > 0){
            $query = "UPDATE login SET password=? WHERE user='admin'";
            $result = $cnx ->prepare($query);
            $result -> execute(array($new_pass));
            $data = [array("message" => true)];
        }else{
            $data = [array("message" => false)];
        }
        break;
    
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
$cnx = null;
?>