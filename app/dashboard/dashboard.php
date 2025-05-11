<!DOCTYPE html>
<?php
    ob_start(); /*this should be first line of your page*/
    if(session_status() != PHP_SESSION_ACTIVE) session_start();
    
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    
    include $php_scripts_root.'utilities/constants.php';

    if(isset($_SESSION['userLoggedIn'])) {
        if(false == $_SESSION['userLoggedIn']) {
            header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
        }
    } else if(!isset($_SESSION['userLoggedIn'])) {
        header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
    }
    
?>
<html lang="en">
    <head>
        <base href="https://www.nestinbase.com/monitor/app/dashboard/dashboard.php">
        
        
        <link rel="stylesheet" href="dashboard_style.css">
        <title>Dashboard</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    </head>
  
    <body>
        <div class="container-md">
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="border-radius: 25px 8px 8px 25px; margin-top: 10px;">
                
                <a class="navbar-brand" href="https://www.nestinbase.com/monitor/app/dashboard/dashboard.php"><img src="../res/icon.png" width="30px" height="30px" class="d-inline-block align-top" alt="">Dashboard</a>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                
                
                    
                    <ul class="navbar-nav" style="margin-left: auto; margin-right: 0;">
                        
                        <?php
                            $visibilityUserManagerMenu = 'style="display: block;"';
                            if((isset($_SESSION['userRole'])) && ($_SESSION['userRole'] == admin)) {
                                $visibilityUserManagerMenu = 'style="display: block;"';
                            } else if((isset($_SESSION['userRole'])) && ($_SESSION['userRole'] == viewer)){
                                $visibilityUserManagerMenu = 'style="display: none;"';
                            }
                        ?>
                        <li class="nav-item" <?php print $visibilityUserManagerMenu; ?>>
                            <a class="nav-link" href="https://www.nestinbase.com/monitor/app/user_manager/users/users_redirection.php" style="text-align: right;">User Manager</a>
                        </li>
                        
                             
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.nestinbase.com/monitor/app/history/history.php" style="text-align: right;">History</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.nestinbase.com/monitor/app/logout/logout_redirection.php" style="text-align: right;">Logout</a>
                        </li> 
                        
                    </ul>
                    
                </div>
            </nav>
        </div>
        
        <center>
            <div class="container-md">
                <div  class="bg-transparent" id="statusAndUserNameContainer">
                    <table  border='0' style="width:100%; height: 100%;">
                        <tr>
                            <td width="50%">
                                <div  class="bg-light" id="arduinoStatusContainer">
                                    <center>
                                        <a class="navbar-brand"><img src="../res/loading.png" width="12px" height="12px" class="d-inline-block align-center" id="statusIcon"></a>
                                        <a class="navbar-brand" id="statusText" style="font-size: 18px; padding-top: 7px;">loading</a>
                                    </center>
                                </div>
                            </td>
                            <td width="50%">
                                <div  class="bg-light" id="userNameContainer">
                                    <center>
                                        <a class="navbar-brand" style="font-size: 18px; padding-top: 7px;"><?php if(isset($_SESSION['userNamePrefix'])) {print $_SESSION['userNamePrefix'];} ?></a>
                                    </center>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="container-md">
                <div  class="bg-light" id="temperatureContainer">
                    <table  border='0' style="width:100%; height: 100%;">
                        <tr>
                            <td width="20%">
                                <center>
                                    <img src="../res/temperature_icon.png" style="width:75px; height:70px;">
                                    <p class="text-primary" id="temperatureField" style="color: black; padding-top: 20px;"></p>
                                </center>
                            </td>
                            <td width="80%">
                                <canvas id="temperatureChart" style="width:100%; height: 100px;"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="container-md">
                <div  class="bg-light" id="humidityContainer">
                    <table  border='0' style="width:100%; height: 100%;">
                        <tr>
                            <td width="20%">
                                <center>
                                    <img src="../res/humidity_icon.png" style="width:75px; height:70px;">
                                    <p class="text-primary" id="humidityField" style="color: black; padding-top: 20px;"></p>
                                </center>
                            </td>
                            <td width="80%">
                                <canvas id="humidityChart" style="width:100%; height: 100px;"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="container-md">
                <div  class="bg-light" id="atmosphericPressureContainer">
                    <table  border='0' style="width:100%; height: 100%;">
                        <tr>
                            <td width="20%">
                                <center>
                                    <img src="../res/atmo_pressure_icon.png" style="width:75px; height:70px;">
                                    <p class="text-primary" id="atmoPresField" style="color: black; padding-top: 20px;"></p>
                                </center>
                            </td>
                            <td width="80%">
                                <canvas id="atmosphericPressureChart" style="width:100%; height: 100px;"/>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="container-md">
                <div  class="bg-light" id="dateTimeContainer">
                    <center>
                        <a class="d-inline-block align-center" id="DateTime" style="font-size: 18px; padding-top: 7px;"></a>
                    </center>
                </div>
            </div>
        </center>
        
        <script type="text/javascript">
            
            /*check for browser support*/
            if(typeof(EventSource)!=="undefined") {
                
            	/*create an object, passing it the name and location of the server side script*/
            	var lastValuesEvent = new EventSource("last_values_updater_redirection.php");
            	
            	lastValuesEvent.onopen = function() {
            	    /*no implementation needed*/
                };
            	
            	/*detect message receipt*/
            	lastValuesEvent.onmessage = function(event) {
            	    const obj = JSON.parse(event.data);
            	    
            	   /*update status*/
            		if(obj.ARDUINO_STATUS == "online") {
        		        document.getElementById("statusIcon").src="../res/online.png";
        		        document.getElementById("statusText").innerHTML = obj.ARDUINO_STATUS;
        		        
        		        document.getElementById("temperatureField").innerHTML = obj.TEMP_C + " °C";
            	        document.getElementById("humidityField").innerHTML    = obj.HUMIDITY + " %";
            	        document.getElementById("atmoPresField").innerHTML    = obj.ATMO_HPA + " hPa";
            		} else if(obj.ARDUINO_STATUS == "offline"){
            		    document.getElementById("statusIcon").src="../res/offline.png";
            		    document.getElementById("statusText").innerHTML = obj.ARDUINO_STATUS + " " + obj.OFFLINE_MINUTES + " m";
            		    
        		        document.getElementById("temperatureField").innerHTML = "-.-" + " °C";
            	        document.getElementById("humidityField").innerHTML    = "-.-" + " %";
            	        document.getElementById("atmoPresField").innerHTML    = "-.-" + " hPa";
            		}
            	    
            	    /*date time debug*/
            	    document.getElementById("DateTime").innerHTML = "UTC: " + obj.DATE_TIME_LAST_STATUS;
            		
            	};
            	
                var todayGraphicsEvent = new EventSource("today_graphics_updater.php");
                
                todayGraphicsEvent.onopen = function() {
            	    /*no implementation needed*/
                };
            	
            	/*detect message receipt*/
            	todayGraphicsEvent.onmessage = function(event) {
            	    const todayGraphicsObj = JSON.parse(event.data);
            	    const nr_of_values_per_day = ( parseInt(60 / parseInt(todayGraphicsObj.PERIODICITY_MINUTES)) * 24);
            	    const values_per_day = Array.from({ length: nr_of_values_per_day }, (value, index) => index);
            	    
            	    let temp_c_list_as_str = todayGraphicsObj.TEMP_C_LIST;
                    let temp_c_list_as_int = [];
                    temp_c_list_as_str.forEach(str => {
                        temp_c_list_as_int.push(parseFloat(str));
                    });
                    
                    let humidity_list_as_str = todayGraphicsObj.HUMIDITY_LIST;
                    let humidity_list_as_int = [];
                    humidity_list_as_str.forEach(str => {
                        humidity_list_as_int.push(parseFloat(str));
                    });
            	    
            	    let atmo_hpa_list_as_str = todayGraphicsObj.ATMO_HPA_LIST;
                    let atmo_hpa_list_as_int = [];
                    atmo_hpa_list_as_str.forEach(str => {
                        atmo_hpa_list_as_int.push(parseFloat(str));
                    });
            	    
            	    
            	    new Chart("temperatureChart", {
            	        interactivityEnabled: false,
                        type: "line",
                        data: {
                            labels: values_per_day,
                            datasets: [{
                                fill: false,
                                lineTension: 0,
                                backgroundColor: "rgb(75, 75, 102)",
                                borderColor: "rgb(75, 192, 192)",
                                data: temp_c_list_as_int,
                                pointRadius: (context) => {
                              	    const pointsLength = context.chart.data.datasets[0].data.length - 1;
                                    const pointsArray = [];
                                
                                    for(let i = 0; i <= pointsLength; i++) {
                                	    if(i === pointsLength) {
                                    	    pointsArray.push(5);
                                        } else {
                                    	    pointsArray.push(0);
                                        }
                                    }
                                    return pointsArray;
                                },
                            }]
                        },
                        options: {
                            layout: {padding: {top: 6, bottom: 6, left: 6, right: 6}},
                            animation: {duration: 0},
                            legend: {display: false},
                            scales: {
                                yAxes: [{display: false}],
                                xAxes: [{display: false}],
                            }
                        }
                    });
            	    
            		new Chart("humidityChart", {
            		    interactivityEnabled: false,
                        type: "line",
                        data: {
                            labels: values_per_day,
                            datasets: [{
                                fill: false,
                                lineTension: 0,
                                backgroundColor: "rgb(75, 75, 102)",
                                borderColor: "rgb(75, 192, 192)",
                                data: humidity_list_as_int,
                                pointRadius: (context) => {
                              	    const pointsLength = context.chart.data.datasets[0].data.length - 1;
                                    const pointsArray = [];
                                
                                    for(let i = 0; i <= pointsLength; i++) {
                                	    if(i === pointsLength) {
                                    	    pointsArray.push(5);
                                        } else {
                                    	    pointsArray.push(0);
                                        }
                                    }
                                    return pointsArray;
                                },
                            }]
                        },
                        options: {
                            layout: {padding: {top: 6, bottom: 6, left: 6, right: 6}},
                            animation: {duration: 0},
                            legend: {display: false},
                            scales: {
                                yAxes: [{display: false}],
                                xAxes: [{display: false}],
                            }
                        }
                    });
            
            
                    new Chart("atmosphericPressureChart", {
                        interactivityEnabled: false,
                        type: "line",
                        data: {
                            labels: values_per_day,
                            datasets: [{
                                fill: false,
                                lineTension: 0,
                                backgroundColor: "rgb(75, 75, 102)",
                                borderColor: "rgb(75, 192, 192)",
                                data: atmo_hpa_list_as_int,
                                pointRadius: (context) => {
                              	    const pointsLength = context.chart.data.datasets[0].data.length - 1;
                                    const pointsArray = [];
                                
                                    for(let i = 0; i <= pointsLength; i++) {
                                	    if(i === pointsLength) {
                                    	    pointsArray.push(5);
                                        } else {
                                    	    pointsArray.push(0);
                                        }
                                    }
                                    return pointsArray;
                                },
                            }]
                        },
                        options: {
                            layout: {padding: {top: 6, bottom: 6, left: 6, right: 6}}, 
                            animation: {duration: 0},
                            legend: {display: false},
                            scales: {
                                yAxes: [{display: false}],
                                xAxes: [{display: false}],
                            }
                        }
                    });
            	};
            }
            else {
                /*no implementation needed*/
            }
            
        </script>
    </body>
</html>