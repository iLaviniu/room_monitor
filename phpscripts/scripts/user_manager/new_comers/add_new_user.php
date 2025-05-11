<?php

    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/mysql_connection.php'; 
    include $php_scripts_root.'utilities/email_sender.php'; 
    include $php_scripts_root.'utilities/constants.php';

    ob_start(); //this should be first line of your page
    if((session_status() === PHP_SESSION_NONE) || (session_status() === PHP_SESSION_DISABLED)) { session_start(); }
    
    //create MySQL connection
    MySQLConnection::getInstance();
    
    //list of new comers, as json format, which were selected by admin server.
    $data = file_get_contents('php://input');
    
    $arr = json_decode($data);
    foreach($arr as $currentNewComer) {
        
        //copy encrypted password for current User
        $sql_get_new_comer = "select * from newcomers where username = '".$currentNewComer."'";
        $sql_get_new_comer_result = (MySQLConnection::getConnection())->query($sql_get_new_comer);
        $row_new_comer = $sql_get_new_comer_result->fetch_row();
        $new_comer_encrypted_pass = $row_new_comer[2];
        
        //insert new comer in users
        $sql_insert_user = "insert into users (username, password, role, code_reset_password, crp_date_time) values ('".$currentNewComer."', '".$new_comer_encrypted_pass."', '".viewer."', '000000', '0000-00-00 00:00:00')";
        $sql_insert_user_result = (MySQLConnection::getConnection())->query($sql_insert_user);
        
        if(!$sql_insert_user_result){
            header( 'Location: https://www.nestinbase.com/monitor/app/errors/404_error_page.php' ); die;
        } else {
            //remove new comer from newcomers table;
            $sql_remove_new_comer = "delete from newcomers where username='".$currentNewComer."'";
            $sql_remove_new_comer_result = (MySQLConnection::getConnection())->query($sql_remove_new_comer);
            
            /*notify newComer by email*/
            $emailSender = new EmailSender($currentNewComer, "Administrator Acceptance", "Congratulations! Now you can access nestinbase with your credentials.");
        }
    }
    
    $newComersJson = '[';
    
    /*get all new comers*/
    $sql_new_comers = "select * from newcomers order by userid";
    $result_new_comers = (MySQLConnection::getConnection())->query($sql_new_comers);
    
    //close MySQL connection
    MySQLConnection::close();
    
    if(!$result_new_comers){
        header( 'Location: https://www.nestinbase.com/monitor/app/errors/404_error_page.php' ); die;
    } else {
            
        // output data of each row
        while($row = $result_new_comers->fetch_assoc()) {
            
            $newComersJson = $newComersJson.'{"userid":"'.$row["userid"].'", "username":"'.$row["username"].'", "date_time":"'.$row["date_time"].'"},';
        }
    }
    
    $newComersJson = $newComersJson."]";
    $newComersJson = str_replace(",]", "]", $newComersJson);
    
    //send the latest newcomers table as json format
    print($newComersJson);
?>