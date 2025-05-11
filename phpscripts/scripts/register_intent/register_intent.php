<?php
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/constants.php';
    include $php_scripts_root.'utilities/mysql_connection.php';
    include $php_scripts_root.'utilities/email_sender.php';
    

    ob_start(); //this should be first line of your page
    session_start();
    
    $account_user = $_POST["username"];
    
    if(empty($_POST)) {
        die();
    }
    
    if($account_user === "") {
        die();
    }
    
    // Create connection
    MySQLConnection::getInstance();
    
    /*generate random number for account registration*/
    $random_number_register = rand(100000,999999);

    
    /*get time-date now plus 5 minutes*/
    $date_now = date("Y-m-d H:i:s");
    $thisMoment = new DateTime($date_now);
    $thisMoment->add(new DateInterval('PT5M'));
    $nowPlus5Minutes = $thisMoment->format('Y-m-d H:i:s');
    
    
    /*check if user already exists in email checker*/
    $sql_select_email = "select email from emailchecker where email='".$account_user."'";
    $sql_select_email_result = (MySQLConnection::getConnection())->query($sql_select_email);
    
    /*check if user already exists in new comers*/
    $sql_select_newcomer = "select username from newcomers where username='".$account_user."'";
    $sql_select_newcomer_result = (MySQLConnection::getConnection())->query($sql_select_newcomer);
    
    /*check if user already exists in users*/
    $sql_select_user = "select username from users where username='".$account_user."'";
    $sql_select_user_result = (MySQLConnection::getConnection())->query($sql_select_user);
    
    if(($sql_select_email_result->num_rows == 0) and ($sql_select_newcomer_result->num_rows == 0) and ($sql_select_user_result->num_rows == 0)) {
        /*insert row: email, code_registration and creg_date_time inside email checker table*/
        $sql_insert = "insert into emailchecker (email, code_registration, creg_date_time) values ('".$account_user."', '".$random_number_register."', '".$nowPlus5Minutes."')";
        $sql_insert_result = (MySQLConnection::getConnection())->query($sql_insert); 
        
        /*Close MySQL connection*/
        MySQLConnection::close();
        
        if ($sql_insert_result == true) {
            
            /*send email with register code.*/
            $emailSender = new EmailSender($account_user, "Register Code", "Register code: ".$random_number_register."<br>"." 5 minutes validation.");
            
            $_SESSION['userStatus'] = "Register code successfully sent by email!";
            header("Location: https://www.nestinbase.com/monitor/app/register/register.php");
        } else {
            header( 'Location: https://www.nestinbase.com/monitor/app/errors/404_error_page.php' ); die;
        }
        
    } else {
        /*Close MySQL connection*/
        MySQLConnection::close();
        
        $_SESSION['userStatus'] = "user already registered!";
        header("Location: https://www.nestinbase.com/monitor/app/register_intent/register_intent.php");
    }
?>