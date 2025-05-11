<?php
    include "/home/ilaviniu/phpscripts/utilities/mysql_connection.php";
    include "/home/ilaviniu/phpscripts/utilities/email_sender.php";
    
    /*Create connection*/
    MySQLConnection::getInstance();
    
    $sql_query = "select * from arduino where status='LAST_SENT'";
    $sql_query_response = (MySQLConnection::getConnection())->query($sql_query);
    
    /*check if arduino status is available*/
    if($sql_query_response->num_rows == 1) {
        
        $row = $sql_query_response->fetch_row();

        $arduino_date_time = $row[0];
        $date_time_now = date("Y-m-d H:i:s");
        
        $object_date_time_arduino = date_create($arduino_date_time); 
        $object_date_time_now = date_create($date_time_now); 
        
        $interval = date_diff($object_date_time_arduino, $object_date_time_now); 
        
        $daysString = $interval->format('%R%a');
        $hoursString = $interval->format('%h');
        
        $daysInt = intval($daysString);
        $hoursInt = intval($hoursString);

        /*Total minutes = days * 24 * 60 + hours * 60 + minutes*/
        $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
        
        if($totalMinutes > 3)
        {
            /*send email to the administrator.*/
            $emailSender = new EmailSender("laviniu.ile@gmail.com", "Connection Lost", "Connection lost for ". strval($totalMinutes) ." minute(s).");
        }
        
    } else {
        
        print("arduino status unavailable");
    }

    /*Close MySQL connection*/
    MySQLConnection::close();
?>