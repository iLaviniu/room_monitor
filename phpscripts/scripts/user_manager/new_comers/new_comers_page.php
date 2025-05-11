<?php
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/constants.php';
    include $php_scripts_root.'utilities/types.php';
    include $php_scripts_root.'utilities/mysql_connection.php';

    ob_start(); //this should be first line of your page
    if((session_status() === PHP_SESSION_NONE) || (session_status() === PHP_SESSION_DISABLED)) { session_start(); }

    // Create connection
    MySQLConnection::getInstance();
    
    /*get all new comers*/
    $sql_new_comers = "select * from newcomers order by userid";
    $result_new_comers = (MySQLConnection::getConnection())->query($sql_new_comers);
    
    if(!$result_new_comers){
        header( 'Location: https://www.nestinbase.com/monitor/app/errors/404_error_page.php' ); die;
    }

    MySQLConnection::close();
    
    $newComers = [];
    
    if ($result_new_comers->num_rows > 0) {
        
        // output data of each row
        while($row = $result_new_comers->fetch_assoc()) {
            
            $newComer = new NewComer($row["userid"], $row["username"], $row["date_time"]);
            $newComers[] = $newComer;
        }
    } else {
        echo "0 results";
    }
    
    $_SESSION['newComersList'] = serialize($newComers);
    
    header( 'Location: https://www.nestinbase.com/monitor/app/user_manager/new_comers/new_comers.php' );
?>