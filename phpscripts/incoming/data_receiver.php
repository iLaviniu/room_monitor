<?php
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/mysql_connection.php';

    function getDataStatus($jsonObject) {
        $dataStatus = $jsonObject["DATA_STATUS"];
        return ["dataStatus" => "$dataStatus"];
    }

    function getBmp280Temp($jsonObject) {
        $bmp280Object = $jsonObject["BMP280"];
        $temperatureObject = $bmp280Object["temperature"];
        $celsiusValue = $temperatureObject["celsius"];
        $fahrenheitValue = $temperatureObject["fahrenheit"];
        
        return ["celsius" => $celsiusValue,
                "fahrenheit" => $fahrenheitValue,
                ];
    }
    
    function getBmp280AtmPres($jsonObject) {
        $bmp280Object = $jsonObject["BMP280"];
        $atmoPresObject = $bmp280Object["atmospheric_pressure"];
        $pascalValue = $atmoPresObject["Pa"];
        $hectoPascalValue = $atmoPresObject["hPa"];
        
        return ["Pa" => $pascalValue,
                "hPa" => $hectoPascalValue,
                ];
    }
    
    function getDht11Temp($jsonObject) {
        $dht11Object = $jsonObject["DHT11"];
        $temperatureObject = $dht11Object["temperature"];
        $celsiusValue = $temperatureObject["celsius"];
        $fahrenheitValue = $temperatureObject["fahrenheit"];
        
        return ["celsius" => $celsiusValue,
                "fahrenheit" => $fahrenheitValue,
                ];
    }
    
    function getDht11Humidity($jsonObject) {
        $dht11Object = $jsonObject["DHT11"];
        $humidityObject = $dht11Object["humidity"];
        $humidityValue = $humidityObject["%"];
        
        return ["%" => $humidityValue];
    }
    
    function getPeriodicityMinutes($jsonObject) {
        $periodicityMinutes = $jsonObject["PERIODICITY_MINUTES"];
        
        return $periodicityMinutes;
    }

    $scriptStatus = "OK";
    $incoming_data_rows = "0";
    $jsonString = file_get_contents('php://input');
    
    if($jsonString === false) {
        $scriptStatus = "error_reading_php_input";
    } else {
        
        $jsonObject = json_decode($jsonString, true);
        
        if($jsonObject === null) {
            $scriptStatus = "error_decoding_json";
        } else {
            
            $dataStatusResponse = getDataStatus($jsonObject);
            $dataStatus = $dataStatusResponse["dataStatus"];
            
            $bmp280Temperaure = getBmp280Temp($jsonObject);
            $bmp280TemperaureCelsius = $bmp280Temperaure["celsius"];
            $bmp280TemperaureFahrenheit = $bmp280Temperaure["fahrenheit"];
            
            $bmp280AtmoPres = getBmp280AtmPres($jsonObject);
            $bmp280AtmoPresPascal = $bmp280AtmoPres["Pa"];
            $bmp280AtmoPresHectoPascal = $bmp280AtmoPres["hPa"];
            
            $dht11Temperaure = getDht11Temp($jsonObject);
            $dht11TemperaureCelsius = $dht11Temperaure["celsius"];
            $dht11TemperaureFahrenheit = $dht11Temperaure["fahrenheit"];
            
            $dht11Humidity = getDht11Humidity($jsonObject);
            $dht11HumidityValue = $dht11Humidity["%"];
            
            $periodicityMinutes = getPeriodicityMinutes($jsonObject);
            
            $scriptStatus = "OK";
            
            $date_time_now = date("Y-m-d H:i:s");
            
            /*Create connection*/
            MySQLConnection::getInstance();
            
            $today_table = date("Y-m-d");
            
            $sql_insert_data = "insert into `".$today_table."` (date_time, data_status, periodicity_minutes, bmp_280_temp_c, bmp_280_temp_f, bmp_280_atmo_pa, bmp_280_atmo_hpa, dht_11_temp_c, dht_11_temp_f, dht_11_humidity) values ('".$date_time_now."', '".$dataStatus."', '".$periodicityMinutes."', '".$bmp280TemperaureCelsius."', '".$bmp280TemperaureFahrenheit."', '".$bmp280AtmoPresPascal."', '".$bmp280AtmoPresHectoPascal."', '".$dht11TemperaureCelsius."', '".$dht11TemperaureFahrenheit."', '".$dht11HumidityValue."')";
        
            $sql_insert_data_result = (MySQLConnection::getConnection())->query($sql_insert_data);
            
            if(!$sql_insert_data_result) {
                $scriptStatus = "error_inserting_data_into_database";
            } else {
                $sql_rows_query = "select * from `".$today_table."`";
                $sql_rows_query_result = (MySQLConnection::getConnection())->query($sql_rows_query);
                $incoming_data_rows = $sql_rows_query_result->num_rows;
            }
            
            /*close MySQL connection*/
            MySQLConnection::close();
        }
    }
    print("<".$scriptStatus."|".$incoming_data_rows."rows>");
?>