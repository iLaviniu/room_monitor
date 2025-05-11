<?php
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    include $php_scripts_root.'utilities/constants.php';
    include $php_scripts_root.'utilities/types.php';
    include $php_scripts_root.'utilities/mysql_connection.php';

    ob_start(); //this should be first line of your page
    if((session_status() === PHP_SESSION_NONE) || (session_status() === PHP_SESSION_DISABLED)) { session_start(); }

    // Create connection
    MySQLConnection::getInstance();
    
    /*get all users*/
    $sql_users = "select * from users";
    $result_sql_users = (MySQLConnection::getConnection())->query($sql_users);
    
    if(!$result_sql_users){
        header( 'Location: https://www.nestinbase.com/monitor/app/errors/404_error_page.php' ); die;
    }

    MySQLConnection::close();
    
    $users = [];
    
    if ($result_sql_users->num_rows > 0) {
        
        // output data of each row
        $user_count = 0;
        while($row = $result_sql_users->fetch_assoc()) {
        	if($row["role"] == viewer) {
	            $user_count++;
	            $user = new UserAndRole($user_count, $row["username"], $row["role"]);
	            $users[] = $user;
            }
        }
    } else {
        echo "0 results";
    }
    
    $_SESSION['usersListForRemoving'] = serialize($users);
    
    header( 'Location: https://www.nestinbase.com/monitor/app/user_manager/remove_users/remove_users.php' );
?>