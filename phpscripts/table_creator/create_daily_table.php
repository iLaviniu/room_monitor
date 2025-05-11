<?php
    include "/home/ilaviniu/phpscripts/utilities/mysql_connection.php";
    
    /*Create connection*/
    MySQLConnection::getInstance();
    
    $sql_all_tables = "show tables";
    $sql_all_tables_result = (MySQLConnection::getConnection())->query($sql_all_tables);
    
    $isTable = false;
    $tomorrow = date('Y-m-d',strtotime(date('Y-m-d') . ' +1 day'));
    while($row = $sql_all_tables_result->fetch_assoc()) {
        $table_name = $row["Tables_in_ilaviniu_database"];
        if($tomorrow === $table_name) {
            $isTable = true;
        }
    }
    
    if($isTable == true) {
        /*$tomorrow table exists*/
    } else {
        /*$tomorrow table does not exists*/
        
        $sql_create_tomorrow_table = "CREATE TABLE `".$tomorrow."` (date_time DATETIME, data_status VARCHAR(10), periodicity_minutes VARCHAR(10), bmp_280_temp_c VARCHAR(20), bmp_280_temp_f VARCHAR(20), bmp_280_atmo_pa VARCHAR(20), bmp_280_atmo_hpa VARCHAR(20), dht_11_temp_c VARCHAR(20), dht_11_temp_f VARCHAR(20), dht_11_humidity VARCHAR(20), PRIMARY KEY (date_time))";
        $sql_create_tomorrow_table_result = (MySQLConnection::getConnection())->query($sql_create_tomorrow_table);
    }

    /*Close MySQL connection*/
    MySQLConnection::close();
?>