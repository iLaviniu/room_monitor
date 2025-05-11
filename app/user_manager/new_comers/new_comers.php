<!DOCTYPE html>
<?php
    ob_start(); //this should be first line of your page
    session_start();
    
    $php_scripts_root = str_replace("public_html", "phpscripts/",$_SERVER['DOCUMENT_ROOT'] );
    
    include $php_scripts_root.'utilities/constants.php';
    include $php_scripts_root.'utilities/types.php';
    
    if(isset($_SESSION['userLoggedIn'])) {
        if(false == $_SESSION['userLoggedIn']) {
            header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
        } else if(!isset($_SESSION['userRole'])) {
            header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
        } else if(isset($_SESSION['userRole']) && ("viewer" == $_SESSION['userRole'])) {
            header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
        }
    } else if(!isset($_SESSION['userLoggedIn'])) {
        header("Location: https://www.nestinbase.com/monitor/app/login/login.php");
    }
    
?>

<html lang="en">
    <head>
        <base href="https://www.nestinbase.com/monitor/app/user_manager/new_comers/new_comers.php">
        
        <link rel="stylesheet" href="/../../generic_style/table_style.css">
        <link rel="stylesheet" href="new_comers_style.css">
        <title>New Comers</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    </head>
  
    <body>
        <div class="container-md">
            <nav class="navbar navbar-expand-lg navbar-light bg-light" style="border-radius: 25px 8px 8px 25px; margin-top: 10px;">
                
                
                
                <a class="navbar-brand" href="https://www.nestinbase.com/monitor/app/dashboard/dashboard.php"><img src="../../res/icon.png" width="30px" height="30px" class="d-inline-block align-top" alt="">Dashboard</a>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                
                    
                    <ul class="navbar-nav" style="margin-left: auto; margin-right: 0;">
                        
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.nestinbase.com/monitor/app/user_manager/users/users_redirection.php" style="text-align: right;">Users</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link active" href="https://www.nestinbase.com/monitor/app/user_manager/new_comers/new_comers_redirection.php" style="text-align: right;">New Comers</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="https://www.nestinbase.com/monitor/app/user_manager/remove_users/remove_users_redirection.php" style="text-align: right;">Remove Users</a>
                        </li>
                        
                    </ul>
                    
                </div>
            </nav>
        </div>
        
        <center>
            <div class="container-md">
                <div  class="bg-light" id="serverMessagesContainer" style="display: none;">
                    <ul id="serverMessagesList">
                        
                    </ul>
        	    </div>
    	    </div>
	    </center>
        
        <center>
            
            <div class="container-md">
                <div  class="bg-light" id="newComersTableContainer">
                    <center>
                	    <?php  
                    	    $newComersList = unserialize($_SESSION['newComersList']);
        
                    	    if(count($newComersList) > 0) {
                    	        
                    	        echo "<ul class='conversion-rate-list'id='new_comers_list'>";
                    	            $counter = 0;
                    	            foreach($newComersList as &$newComer) {
                    	                if($counter > 0) {
                    	                    echo "<li class='item'>";
                    	                } else {
                    	                    echo "<li class='first_item'>";
                    	                }
                    	                
                    	                    echo "<h2 class='platform'><input type='checkbox' id='".$newComer->getUsername()."'/></h2>";
                    	                    
                    	                    echo "<dl class='ad'>";
                    	                        
                    	                        echo "<dt class='name'>User Id</dt>";
                    	                        echo "<dd class='value'>".$newComer->getUserID()."</dd>";

                    	                        echo "<dt class='name'>Username</dt>";
                    	                        echo "<dd class='value'>".$newComer->getUsername()."</dd>";

                    	                        echo "<dt class='name'>Date-Time</dt>";
                    	                        echo "<dd class='value'>".$newComer->getDateTime()."</dd>";
                    	                        
                    	                    echo "</dl>";
                    	                
                    	                echo "</li>";
                    	                
                    	                $counter++;
                    	            }
                    	        
                    	        echo "</ul>";
                    	        
                        	    echo "<table  class='conversion-rate-table' id='new_comers_table'>";
                        	        echo "<thead class='header-section'>";
                                        echo "<tr class='headers'>";
                                            echo "<th class='header' scope='col'>Select</th>";
                                            echo "<th class='header' scope='col'>User Id</th>";
                                            echo "<th class='header' scope='col'>Username</th>";
                                            echo "<th class='header' scope='col'>Date-Time</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    foreach($newComersList as &$newComer) {
                                        echo "<tbody class='data-section'>";
                            	            echo "<tr class='ad'>";
                            	                echo "<td class='cell'><input type='checkbox' id='".$newComer->getUsername()."'/></td>";
                                                echo "<td class='cell'>".$newComer->getUserID()."</td>";
                                                echo "<td class='cell'>".$newComer->getUsername()."</td>";
                                                echo "<td class='cell'>".$newComer->getDateTime()."</td>";
                                            echo "</tr>";
                                        echo "</tbody>";
                        	        }
                        	    echo "</table>";
                    	    } else {
                    	        echo "there are no new comers!";
                    	    }
                	    ?>
                    </center>
                </div>
            </div>
            
            <?php
                if(count($newComersList) > 0) {
                    $buttonVisibility = 'style="display: block;"';
                } else {
                    $buttonVisibility = 'style="display: none;"';
                }
    	    ?>

            
            <div class="container-md" <?php print $buttonVisibility; ?>>
                <div  class="bg-light" id="newComersButtonContainer">
                    <center>
    	            	<?php
                    	    if(count($newComersList) > 0) {
                    	        echo '<button class="newComersButton" id="btn_accept_user" onclick="sendNewComers()">a c c e p t &nbsp; u s e r</button>';
                    	    }
                    	?>
                    </center>
                </div>
            </div>
            

        </center>
    </body>
    
    
    <script language="javascript">
        
        function isListDisplayed() {
            var isDisplayed = false;
            const listObject = document.getElementById("new_comers_list");
            var listHeight = listObject.offsetHeight;
            var listWidth = listObject.offsetWidth;
            
            if((listHeight == 0) && (listWidth == 0)) {
                isDisplayed = false;
            } else {
                isDisplayed = true;
            }
            return isDisplayed;
        }
        
        function getListLength() {
            var listObject = document.getElementById("new_comers_list");
            var items = listObject.getElementsByTagName("li");
            return items.length;
        }
        
        function getSelectedUsersFromList(listLength, stringBuilder) {
            var listObject = document.getElementById("new_comers_list");
            var items = listObject.getElementsByTagName("li");
            for(var index = 0; index < listLength; index++) {
                var item = items[index];
                var inputs = item.getElementsByTagName("input");
                var currentCheckBox = inputs[0];
                
                if(currentCheckBox.checked) {
                    stringBuilder += '"' + currentCheckBox.id + '"' + ",";
                }
            }
            return stringBuilder;
        }
        
        function isTableDisplayed() {
            var isDisplayed = false;
            const tableObject = document.getElementById("new_comers_table");
            var tableHeight = tableObject.offsetHeight;
            var tableWidth = tableObject.offsetWidth;
            
            if((tableHeight == 0) && (tableWidth == 0)) {
                isDisplayed = false;
            } else {
                isDisplayed = true;
            }
            return isDisplayed;
        }
        
        function getTableLength() {
            var tableObject = document.getElementById("new_comers_table");
            var nrOfRows = tableObject.rows.length;
            return nrOfRows;
        }
        
        function getSelectedUsersFromTable(tableLength, stringBuilder) {
            var selectionColumn = 0;
            var firstChild = 0;
            var tableObject = document.getElementById("new_comers_table");
            for (var rowIndex = 1; rowIndex < tableLength; rowIndex++) {
                currentCheckBox = tableObject.rows[rowIndex].cells[selectionColumn].children[firstChild];
                if(currentCheckBox.checked) {
                    stringBuilder += '"' + currentCheckBox.id + '"' + ",";
                }
            }
            return stringBuilder;
        }
        
        function getSelectedUsers(stringBuilder) {
            if(isListDisplayed()) {
                /*list is displayed*/
                var listLength = getListLength();
                stringBuilder = getSelectedUsersFromList(listLength, stringBuilder);
            } else if(isTableDisplayed()) {
                /*table is displayed*/
                var tableLength = getTableLength();
                stringBuilder = getSelectedUsersFromTable(tableLength, stringBuilder);
            }
            return stringBuilder;
        }
        
        function clearList() {
            var listObject = document.getElementById("new_comers_list");
            /*clear old new comers list*/
            while(listObject.firstChild) {
                listObject.removeChild(listObject.firstChild);
            }
        }
        
        function insertItemIntoList(row) {
            var listObject = document.getElementById("new_comers_list");
            var items = listObject.getElementsByTagName("li");
            var newLi = document.createElement("li");
            
            var itemClass = "";
            if(items.length == 0) {
                /*first item*/
                itemClass = "first_item";
            } else {
                itemClass = "item";
            }
            
            newLi.setAttribute("class", itemClass);
            
            var h2Item = document.createElement("h2");
            h2Item.setAttribute("class", "platform");
            var inputItem = document.createElement("input");
            inputItem.setAttribute("type", "checkbox");
            inputItem.setAttribute("id", row.username);
            h2Item.appendChild(inputItem);
            newLi.appendChild(h2Item);
            
            var dlItem = document.createElement("dl");
            dlItem.setAttribute("class", "ad");
            
            var dtItem = document.createElement("dt");
            dtItem.setAttribute("class", "name");
            dtItem.appendChild(document.createTextNode("User Id"));
            dlItem.appendChild(dtItem);
            var ddItem = document.createElement("dd");
            ddItem.setAttribute("class", "value");
            ddItem.appendChild(document.createTextNode(row.userid));
            dlItem.appendChild(ddItem);
            
            var dtItem = document.createElement("dt");
            dtItem.setAttribute("class", "name");
            dtItem.appendChild(document.createTextNode("Username"));
            dlItem.appendChild(dtItem);
            var ddItem = document.createElement("dd");
            ddItem.setAttribute("class", "value");
            ddItem.appendChild(document.createTextNode(row.username));
            dlItem.appendChild(ddItem);
            
            var dtItem = document.createElement("dt");
            dtItem.setAttribute("class", "name");
            dtItem.appendChild(document.createTextNode("Date-Time"));
            dlItem.appendChild(dtItem);
            var ddItem = document.createElement("dd");
            ddItem.setAttribute("class", "value");
            ddItem.appendChild(document.createTextNode(row.date_time));
            dlItem.appendChild(ddItem);
            
            newLi.appendChild(dlItem);
            
            listObject.appendChild(newLi);
        }
        
        function clearTable() {
            var tableObject = document.getElementById("new_comers_table");
            /*clear old new comers table*/
            while(tableObject.rows.length > 1) {
                tableObject.deleteRow(1);
            }
        }
        
        function insertRowIntoTable(row) {
            var tableObject = document.getElementById("new_comers_table");
            var rowNewComer = tableObject.insertRow(tableObject.rows.length);
            var cellSelect = rowNewComer.insertCell(0);
            var cellUserId = rowNewComer.insertCell(1);
            var cellUsername = rowNewComer.insertCell(2);
            var cellDateTime = rowNewComer.insertCell(3);
            
            cellSelect.innerHTML = "<input type='checkbox' id='"+row.username+"'/>";
            cellUserId.innerHTML = row.userid;
            cellUsername.innerHTML = row.username;
            cellDateTime.innerHTML = row.date_time;
        }
        
        function sendNewComers() {

            var stringBuilder = "[";
            var jsonResult = "";
            
            stringBuilder = getSelectedUsers(stringBuilder);

            stringBuilder += "]";
            jsonResult = stringBuilder.replace(",]", "]");
            
            if(jsonResult != "[]") {
                var xmlhttp = new XMLHttpRequest();
               
                xmlhttp.open("POST", "https://www.nestinbase.com/monitor/app/user_manager/new_comers/add_new_users_redirection.php", true);
                
                xmlhttp.onloadstart = function() {
                    var serverMessages = document.getElementById("serverMessagesContainer");
                    serverMessages.style.display = "block";
                    var list = document.getElementById("serverMessagesList");
                    var newLi = document.createElement("li");
                    newLi.appendChild(document.createTextNode("request in progress!"))
                    list.appendChild(newLi);
                }
                
                xmlhttp.onload = function() {
                    /*not implemented*/
                }
                
                xmlhttp.onloadend = function() {
                    var serverMessages = document.getElementById("serverMessagesContainer");
                    serverMessages.style.display = "block";
                    var list = document.getElementById("serverMessagesList");
                    var newLi = document.createElement("li");
                    newLi.appendChild(document.createTextNode("request successfully ended!"))
                    list.appendChild(newLi);
                    
                    setTimeout(
                        function(){
                            var serverMessages = document.getElementById("serverMessagesContainer");
                            var list = document.getElementById("serverMessagesList");
                            
                            while(list.firstChild) {
                                list.removeChild(list.firstChild);
                            }
                            
                            serverMessages.style.display = "none";
                        }, 5000);
                }
                
                xmlhttp.onprogress = function() {
                    /*not implemented*/
                }
                
                xmlhttp.ontimeout = function() {
                    /*not implemented*/
                }
                
                xmlhttp.onerror = function() {
                    /*not implemented*/
                }

                xmlhttp.onabort = function() {
                    /*not implemented*/
                }
            
                xmlhttp.onreadystatechange = function() {
                    if(this.readyState == 4 && this.status == 200) {
                        
                        if(isListDisplayed()) {
                            clearList();
                        } else if(isTableDisplayed()) {
                            clearTable();
                        }
                        
                        var jsonNewComersDataBaseTable = xmlhttp.responseText;
                        var jsonObject = JSON.parse(jsonNewComersDataBaseTable);
    
                        if(jsonObject.length > 0) {    
                            for (var i = 0; i < jsonObject.length; i++) {
                                var row = jsonObject[i];
                                
                                if(isListDisplayed()) {
                                    insertItemIntoList(row);
                                } else if(isTableDisplayed()) {
                                    insertRowIntoTable(row);
                                }
                            }
                        } else {
                            
                            if(isListDisplayed()) {
                                /*remove empty list*/
                                var listNewComers = document.getElementById("new_comers_list");
                                if(listNewComers) {
                                    listNewComers.parentNode.removeChild(listNewComers);
                                }
                            } else if(isTableDisplayed()) {
                                /*remove empty table*/
                                var tblNewComers = document.getElementById("new_comers_table");
                                if(tblNewComers) {
                                    tblNewComers.parentNode.removeChild(tblNewComers);
                                }
                            }
                            
                            /*remove add user button*/
                            var btnAcceptUser = document.getElementById("btn_accept_user");
                            if(btnAcceptUser) {
                                btnAcceptUser.parentNode.removeChild(btnAcceptUser);
                            }
                            
                            /*remove add user button div*/
                            const buttonContainer = document.getElementById('newComersButtonContainer');
                            buttonContainer.style.display = 'none';
                            
                            /*add text to div : "no content to show!";*/
                            const tableContainer = document.getElementById('newComersTableContainer');
                            const containerText = document.createTextNode('there are no new comers!');
                            tableContainer.appendChild(containerText);
                        }
                    }
                }
                
                xmlhttp.setRequestHeader('Content-Type', 'text/plain');
                 
                xmlhttp.send(jsonResult);
            } else {
                alert("no user selected!");
            }
        }
    </script>
    
</html>