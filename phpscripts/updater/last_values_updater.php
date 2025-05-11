<?php
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/mysql_connection.php';
    
    /*Create connection*/
    MySQLConnection::getInstance();
    
    $today_table = date("Y-m-d");
    $date_time_last_status = "";
    $sql_last_status = "select * from `arduino` where status='LAST_SENT'";
    $sql_last_status_result = (MySQLConnection::getConnection())->query($sql_last_status);
    while($row_last_sent = $sql_last_status_result->fetch_assoc()) {
            
        $date_time_last_status = $row_last_sent["date_time"];
    }
    
    $sql_new_data_rows = "select * from `".$today_table."` where data_status='NEW_DATA' order by date_time asc";
    $sql_new_data_rows_result = (MySQLConnection::getConnection())->query($sql_new_data_rows);
    
    $date_time_last_sent = "";
    $bmp_280_temp_c = "";
    $bmp_280_temp_f = "";
    $bmp_280_atmo_pa = "";
    $bmp_280_atmo_hpa = "";
    $dht_11_temp_c = "";
    $dht_11_temp_f = "";
    $dht_11_humidity = "";
    $periodicityMinutes = "";
    
    /*check if there is new data*/
    if($sql_new_data_rows_result->num_rows > 0) {
        /*there is new data*/
        
        while($row = $sql_new_data_rows_result->fetch_assoc()) {
            
            $date_time_last_sent = $row["date_time"];
            
            $bmp_280_temp_c = $row["bmp_280_temp_c"];
            $bmp_280_temp_f = $row["bmp_280_temp_f"];
            $bmp_280_atmo_pa = $row["bmp_280_atmo_pa"];
            $bmp_280_atmo_hpa = $row["bmp_280_atmo_hpa"];
            $dht_11_temp_c = $row["dht_11_temp_c"];
            $dht_11_temp_f = $row["dht_11_temp_f"];
            $dht_11_humidity = $row["dht_11_humidity"];
            $periodicityMinutes = $row["periodicity_minutes"];
            
            /*update column: data_status by OLD_DATA*/
            $sql_update = "update `".$today_table."` set data_status='OLD_DATA' where date_time='".$date_time_last_sent."'";
            $sql_update_result = (MySQLConnection::getConnection())->query($sql_update);
        }
    } else {
        /*there isn't new data*/
        /*no implementation needed*/
        
        /*show the last old data for other clients, the first client was able to see the new data*/
        
        $sql_last_old_data_rows = "select * from `".$today_table."` where data_status='OLD_DATA' order by date_time asc";
        $sql_last_old_data_rows_result = (MySQLConnection::getConnection())->query($sql_last_old_data_rows);
        
        while($row = $sql_last_old_data_rows_result->fetch_assoc()) {
            
            $date_time_last_sent = $row["date_time"];
            
            $bmp_280_temp_c = $row["bmp_280_temp_c"];
            $bmp_280_temp_f = $row["bmp_280_temp_f"];
            $bmp_280_atmo_pa = $row["bmp_280_atmo_pa"];
            $bmp_280_atmo_hpa = $row["bmp_280_atmo_hpa"];
            $dht_11_temp_c = $row["dht_11_temp_c"];
            $dht_11_temp_f = $row["dht_11_temp_f"];
            $dht_11_humidity = $row["dht_11_humidity"];
            $periodicityMinutes = $row["periodicity_minutes"];
        }
    }
    
    $stringJson = "";
    
    $temp_c_as_float = floatval((floatval($bmp_280_temp_c) + floatval($dht_11_temp_c)) / 2);
    $temp_c_as_string = sprintf("%.2f", $temp_c_as_float);
    
    $temp_f_as_float = floatval((floatval($bmp_280_temp_f) + floatval($dht_11_temp_f)) / 2);
    $temp_f_as_string = sprintf("%.2f", $temp_f_as_float);
    
    $atmo_pa_float = floatval($bmp_280_atmo_pa);
    $atmo_pa_string = sprintf("%.2f", $atmo_pa_float);
    
    $atmo_hpa_float = floatval($bmp_280_atmo_hpa);
    $atmo_hpa_string = sprintf("%.2f", $atmo_hpa_float);
    
    $humidity_as_float = floatval($dht_11_humidity);
    $humidity_as_string = sprintf("%.2f", $humidity_as_float);
    
    /*get date time now*/
    $date_time_now = date("Y-m-d H:i:s");
    
    /*calculate minutes between last status and now*/
    $last_sent = new DateTime($date_time_last_status);
    $now = new DateTime($date_time_now);
    $difference = $last_sent->diff($now);
    
    $minutes = $difference->days * 24 * 60;
    $minutes += $difference->h * 60;
    $minutes += $difference->i;
    
    $minutes_since_last_status_Int = intval($minutes, 10);
    
    /*check if arduino module is online or offline*/
    $arduinoStatus = "online";
    if($minutes_since_last_status_Int > 1) { /* checking arduino status each 1 minute. */
        $arduinoStatus = "offline";
    } else {
        $arduinoStatus = "online";
    }
    
    
    $stringJson = "{\"DATE_TIME_LAST_STATUS\": \"".$date_time_last_status."\",\"DATE_TIME\": \"".$date_time_last_sent."\",\"PERIODICITY_MINUTES\": \"".$periodicityMinutes."\",\"ARDUINO_STATUS\": \"".$arduinoStatus."\",\"OFFLINE_MINUTES\": \"".$minutes_since_last_status_Int."\",\"TEMP_C\": \"".$temp_c_as_string."\",\"TEMP_F\": \"".$temp_f_as_string."\",\"ATMO_PA\": \"".$atmo_pa_string."\",\"ATMO_HPA\": \"".$atmo_hpa_string."\",\"HUMIDITY\": \"".$humidity_as_string."\"}";

    /*Close MySQL connection*/
    MySQLConnection::close();

    header('Content-Type: text/event-stream');
    header('Connection: keep-alive');


    echo 'data: '. $stringJson;
    echo "\n\n";
        

    ob_flush();
    flush();

?>