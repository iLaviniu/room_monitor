<?php

    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/mysql_connection.php';
    include $php_scripts_root.'utilities/email_sender.php';
    include $php_scripts_root.'utilities/constants.php';

    ob_start(); //this should be first line of your page
    if((session_status() === PHP_SESSION_NONE) || (session_status() === PHP_SESSION_DISABLED)) { session_start(); }
    
    //create MySQL connection
    MySQLConnection::getInstance();
    
    //list of users, as json format, which were selected by admin.
    $data = file_get_contents('php://input');
    
    $arr = json_decode($data);
    foreach($arr as $currentUser) {
        
        /*remove user from table*/
        $sql_remove_user = "delete from users where username = '".$currentUser."'";
        $sql_remove_user_result = (MySQLConnection::getConnection())->query($sql_remove_user);
        
        if(!$sql_remove_user_result){
            header( 'Location: https://www.nestinbase.com/monitor/app/errors/404_error_page.php' ); die;
        } else {
            /*notify user deletion by email*/
            $emailSender = new EmailSender($currentUser, "Account Deletion", "Your nestinbase.com account has been deleted!");
        }
    }
    
    $remainingUsersJson = '[';
    
    /*get all new comers*/
    $sql_remaining_users = "select * from users";
    $sql_remaining_users_result = (MySQLConnection::getConnection())->query($sql_remaining_users);
    
    //close MySQL connection
    MySQLConnection::close();
    
    if(!$sql_remaining_users_result){
        header( 'Location: https://www.nestinbase.com/monitor/app/errors/404_error_page.php' ); die;
    } else {
            
        // output data of each row
        $userIndex = 0;
        while($row = $sql_remaining_users_result->fetch_assoc()) {
            if($row["role"] == viewer) {
                $userIndex++;
                $remainingUsersJson = $remainingUsersJson.'{"userindex":"'.strval($userIndex).'", "username":"'.$row["username"].'", "role":"'.viewer.'"},';
            }
        }
    }
    
    $remainingUsersJson = $remainingUsersJson."]";
    $remainingUsersJson = str_replace(",]", "]", $remainingUsersJson);
    
    //send the remaining users table as json format
    print($remainingUsersJson);
?>