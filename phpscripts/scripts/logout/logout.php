<?php
    /*MySQL connection is not required to be closed on Logout php page, 
    because it's closing at the end of each php script. 
    The connection is not kept alive 
    when the browser navigates from a php script to another php script.
    Fore more information, please go to: 
    https://www.php.net/manual/en/features.persistent-connections.php*/

    if((session_status() === PHP_SESSION_NONE) || (session_status() === PHP_SESSION_DISABLED)) { session_start(); }
    unset($_SESSION);
    session_destroy();
    session_write_close();
    header('Location: https://www.nestinbase.com/monitor/app/login/login.php');
?>