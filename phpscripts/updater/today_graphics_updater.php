<?php
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/mysql_connection.php';
    
    /*Create connection*/
    MySQLConnection::getInstance();
    
    $today_table = date("Y-m-d");
    
    $sql_all_rows = "select * from `".$today_table."` order by date_time asc";
    $sql_all_rows_result = (MySQLConnection::getConnection())->query($sql_all_rows);
    
    $periodicityMinutes = "";
    $temp_c_list = array();
    $temp_f_list = array();
    $atmp_pa_list = array();
    $atmp_hpa_list = array();
    $humidity_list = array();
    
    $isTableEmpty = true;
    
    if($sql_all_rows_result->num_rows > 0) {
        
        $isTableEmpty = false;

        while($row = $sql_all_rows_result->fetch_assoc()) {
            
            $bmp_280_temp_c = $row["bmp_280_temp_c"];
            $bmp_280_temp_f = $row["bmp_280_temp_f"];
            $bmp_280_atmo_pa = $row["bmp_280_atmo_pa"];
            $bmp_280_atmo_hpa = $row["bmp_280_atmo_hpa"];
            $dht_11_temp_c = $row["dht_11_temp_c"];
            $dht_11_temp_f = $row["dht_11_temp_f"];
            $dht_11_humidity = $row["dht_11_humidity"];
            $periodicityMinutes = $row["periodicity_minutes"];
            
            $temp_c_as_float = floatval((floatval($bmp_280_temp_c) + floatval($dht_11_temp_c)) / 2);
            $temp_c_as_string = sprintf("%.2f", $temp_c_as_float);
            array_push($temp_c_list, $temp_c_as_string);
            
            $temp_f_as_float = floatval((floatval($bmp_280_temp_f) + floatval($dht_11_temp_f)) / 2);
            $temp_f_as_string = sprintf("%.2f", $temp_f_as_float);
            array_push($temp_f_list, $temp_f_as_string);
            
            $atmo_pa_float = floatval($bmp_280_atmo_pa);
            $atmo_pa_string = sprintf("%.2f", $atmo_pa_float);
            array_push($atmp_pa_list, $atmo_pa_string);
            
            $atmo_hpa_float = floatval($bmp_280_atmo_hpa);
            $atmo_hpa_string = sprintf("%.2f", $atmo_hpa_float);
            array_push($atmp_hpa_list, $atmo_hpa_string);
            
            $humidity_as_float = floatval($dht_11_humidity);
            $humidity_as_string = sprintf("%.2f", $humidity_as_float);
            array_push($humidity_list, $humidity_as_string);
        }
    } else {
        /*today table is empty*/
    }
    
    $content_empty = "";
    if($isTableEmpty === true) {
        $content_empty = "TRUE";
        $periodicityMinutes = "0";
        $temp_c_list = array("0");
        $temp_f_list = array("0");
        $atmp_pa_list = array("0");
        $atmp_hpa_list = array("0");
        $humidity_list = array("0");
    } else {
        $content_empty = "FALSE";
    }
    
    $stringJson = "{\"IS_CONTENT_EMPTY\":\"" . $content_empty . "\",\"PERIODICITY_MINUTES\":\"" . $periodicityMinutes . "\",";
    
    $stringJson = $stringJson . "\"TEMP_C_LIST\":[";
    for ($i = 0; $i < count($temp_c_list); $i++) {
        if((count($temp_c_list) - 1) == $i) {
            /*last item*/
            $stringJson = $stringJson . "\"$temp_c_list[$i]\"";
        } else {
            $stringJson = $stringJson . "\"$temp_c_list[$i]\", ";
        }
    }
    $stringJson = $stringJson . "],";
    
    $stringJson = $stringJson . "\"TEMP_F_LIST\":[";
    for ($i = 0; $i < count($temp_f_list); $i++) {
        if((count($temp_f_list) - 1) == $i) {
            /*last item*/
            $stringJson = $stringJson . "\"$temp_f_list[$i]\"";
        } else {
            $stringJson = $stringJson . "\"$temp_f_list[$i]\", ";
        }
    }
    $stringJson = $stringJson . "],";
    
    $stringJson = $stringJson . "\"ATMO_PA_LIST\":[";
    for ($i = 0; $i < count($atmp_pa_list); $i++) {
        if((count($atmp_pa_list) - 1) == $i) {
            /*last item*/
            $stringJson = $stringJson . "\"$atmp_pa_list[$i]\"";
        } else {
            $stringJson = $stringJson . "\"$atmp_pa_list[$i]\", ";
        }
    }
    $stringJson = $stringJson . "],";
    
    $stringJson = $stringJson . "\"ATMO_HPA_LIST\":[";
    for ($i = 0; $i < count($atmp_hpa_list); $i++) {
        if((count($atmp_hpa_list) - 1) == $i) {
            /*last item*/
            $stringJson = $stringJson . "\"$atmp_hpa_list[$i]\"";
        } else {
            $stringJson = $stringJson . "\"$atmp_hpa_list[$i]\", ";
        }
    }
    $stringJson = $stringJson . "],";
    
    $stringJson = $stringJson . "\"HUMIDITY_LIST\":[";
    for ($i = 0; $i < count($humidity_list); $i++) {
        if((count($humidity_list) - 1) == $i) {
            /*last item*/
            $stringJson = $stringJson . "\"$humidity_list[$i]\"";
        } else {
            $stringJson = $stringJson . "\"$humidity_list[$i]\", ";
        }
    }
    $stringJson = $stringJson . "]}";

    /*Close MySQL connection*/
    MySQLConnection::close();
    
    header('Content-Type: text/event-stream');
    header('Connection: keep-alive');


    echo 'data: '. $stringJson;
    echo "\n\n";
        

    ob_flush();
    flush();

?>