<?php
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/mysql_connection.php';

    $scriptStatus = "OK";
    $jsonString = file_get_contents('php://input');
    
    if($jsonString === false) {
        $scriptStatus = "error_reading_php_input";
    } else {
        
        $jsonObject = json_decode($jsonString, true);
        
        if($jsonObject === null) {
            $scriptStatus = "error_decoding_json";
        } else {
            $date_time_now = date("Y-m-d H:i:s");
            
            /*Create connection*/
            MySQLConnection::getInstance();
            
            $sql_update_status = "update `arduino` set `date_time`='".$date_time_now."' where `status`='LAST_SENT'";
        
            $sql_update_status_result = (MySQLConnection::getConnection())->query($sql_update_status);
            
            if(!$sql_update_status_result) {
                $scriptStatus = "error_updating_arduino_status";
            } else {
                $scriptStatus = "OK";
            }
            
            /*close MySQL connection*/
            MySQLConnection::close();
        }
    }
    print("<".$scriptStatus."|".$date_time_now.">");
?>