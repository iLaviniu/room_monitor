<?php
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/mysql_connection.php';
    
    /*Create connection*/
    MySQLConnection::getInstance();
    
    $data = file_get_contents('php://input');
    $arr = json_decode($data);
    $requested_date = $arr[0];
    
    /*check if table exists*/
    $sql_all_tables = "show tables";
    $sql_all_tables_result = (MySQLConnection::getConnection())->query($sql_all_tables);
    
    $isTable = false;
    while($row = $sql_all_tables_result->fetch_assoc()) {
        $table_name = $row["Tables_in_ilaviniu_database"];
        if($requested_date === $table_name) {
            $isTable = true;
        }
    }
    
    if($isTable === true) {
        
        $sql_all_rows = "select * from `".$requested_date."` order by date_time asc";
        $sql_all_rows_result = (MySQLConnection::getConnection())->query($sql_all_rows);
            
        /*Close MySQL connection*/
        MySQLConnection::close();
        $number_of_reads = 0;
        $temp_c_as_float_sum = 0.0;
        $temp_f_as_float_sum = 0.0;
        $atmo_pa_float_sum = 0.0;
        $atmo_hpa_float_sum = 0.0;
        $humidity_as_float_sum = 0.0;
        
        $periodicityMinutes = "0";
        $temp_c_list = array();
        $temp_f_list = array();
        $atmp_pa_list = array();
        $atmp_hpa_list = array();
        $humidity_list = array();
        
        if($sql_all_rows_result->num_rows > 0) {
    
            while($row = $sql_all_rows_result->fetch_assoc()) {
                $number_of_reads += 1;
                
                $bmp_280_temp_c = $row["bmp_280_temp_c"];
                $bmp_280_temp_f = $row["bmp_280_temp_f"];
                $bmp_280_atmo_pa = $row["bmp_280_atmo_pa"];
                $bmp_280_atmo_hpa = $row["bmp_280_atmo_hpa"];
                $dht_11_temp_c = $row["dht_11_temp_c"];
                $dht_11_temp_f = $row["dht_11_temp_f"];
                $dht_11_humidity = $row["dht_11_humidity"];
                $periodicityMinutes = $row["periodicity_minutes"];
                
                $temp_c_as_float = floatval((floatval($bmp_280_temp_c) + floatval($dht_11_temp_c)) / 2);
                $temp_c_as_float_sum = $temp_c_as_float_sum + $temp_c_as_float;
                $temp_c_as_string = sprintf("%.2f", $temp_c_as_float);
                array_push($temp_c_list, $temp_c_as_string);
                
                $temp_f_as_float = floatval((floatval($bmp_280_temp_f) + floatval($dht_11_temp_f)) / 2);
                $temp_f_as_float_sum = $temp_f_as_float_sum + $temp_f_as_float;
                $temp_f_as_string = sprintf("%.2f", $temp_f_as_float);
                array_push($temp_f_list, $temp_f_as_string);
                
                $atmo_pa_float = floatval($bmp_280_atmo_pa);
                $atmo_pa_float_sum = $atmo_pa_float_sum + $atmo_pa_float;
                $atmo_pa_string = sprintf("%.2f", $atmo_pa_float);
                array_push($atmp_pa_list, $atmo_pa_string);
                
                $atmo_hpa_float = floatval($bmp_280_atmo_hpa);
                $atmo_hpa_float_sum = $atmo_hpa_float_sum + $atmo_hpa_float;
                $atmo_hpa_string = sprintf("%.2f", $atmo_hpa_float);
                array_push($atmp_hpa_list, $atmo_hpa_string);
                
                $humidity_as_float = floatval($dht_11_humidity);
                $humidity_as_float_sum = $humidity_as_float_sum + $humidity_as_float;
                $humidity_as_string = sprintf("%.2f", $humidity_as_float);
                array_push($humidity_list, $humidity_as_string);
            }
        } else {
            /*today table is empty*/
            $number_of_reads = 1;
            $periodicityMinutes = "0";
            $temp_c_list = array("0");
            $temp_f_list = array("0");
            $atmp_pa_list = array("0");
            $atmp_hpa_list = array("0");
            $humidity_list = array("0");
        }
        
        $stringJson = "{\"PERIODICITY_MINUTES\":\"" . $periodicityMinutes . "\",";
        
        $stringJson = $stringJson . "\"TEMP_C_AVG\":\"";
        $temp_c_avg_float = $temp_c_as_float_sum / $number_of_reads;
        $temp_c_avg_string = sprintf("%.2f", $temp_c_avg_float);
        $stringJson = $stringJson . $temp_c_avg_string;
        $stringJson = $stringJson . "\",";
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
        
        $stringJson = $stringJson . "\"TEMP_F_AVG\":\"";
        $temp_f_avg_float = $temp_f_as_float_sum / $number_of_reads;
        $temp_f_avg_string = sprintf("%.2f", $temp_f_avg_float);
        $stringJson = $stringJson . $temp_f_avg_string;
        $stringJson = $stringJson . "\",";
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
        
        $stringJson = $stringJson . "\"ATMO_PA_AVG\":\"";
        $atmo_pa_avg_float = $atmo_pa_float_sum / $number_of_reads;
        $atmo_pa_avg_string = sprintf("%.2f", $atmo_pa_avg_float);
        $stringJson = $stringJson . $atmo_pa_avg_string;
        $stringJson = $stringJson . "\",";
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
        
        $stringJson = $stringJson . "\"ATMO_HPA_AVG\":\"";
        $atmo_hpa_avg_float = $atmo_hpa_float_sum / $number_of_reads;
        $atmo_hpa_avg_string = sprintf("%.2f", $atmo_hpa_avg_float);
        $stringJson = $stringJson . $atmo_hpa_avg_string;
        $stringJson = $stringJson . "\",";
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
        
        $stringJson = $stringJson . "\"HUMIDITY_AVG\":\"";
        $humidity_avg_float = $humidity_as_float_sum / $number_of_reads;
        $humidity_avg_string = sprintf("%.2f", $humidity_avg_float);
        $stringJson = $stringJson . $humidity_avg_string;
        $stringJson = $stringJson . "\",";
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
        print($stringJson);
    } else {
        print("table_not_defined");
    }
?>