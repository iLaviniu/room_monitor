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
        <base href="https://www.nestinbase.com/monitor/app/user_manager/remove_users/remove_users.php">
        
        <link rel="stylesheet" href="/../../generic_style/table_style.css">
        <link rel="stylesheet" href="remove_users_style.css">
        <title>Remove Users</title>
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
                            <a class="nav-link" href="https://www.nestinbase.com/monitor/app/user_manager/new_comers/new_comers_redirection.php" style="text-align: right;">New Comers</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link active" href="https://www.nestinbase.com/monitor/app/user_manager/remove_users/remove_users_redirection.php" style="text-align: right;">Remove Users</a>
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
        
        <div class="container-md">
            <div  class="bg-light" id="usersTableContainer">
                    
                <center>
        	        <?php  
                	    $usersListForRemoving = unserialize($_SESSION['usersListForRemoving']);
                	    
                	    if(count($usersListForRemoving) > 0) {
                	        
                	        /*list implementation for small screens*/
                            echo "<ul class='conversion-rate-list' id='remove_users_list'>";
                                $counter = 0;
                                foreach($usersListForRemoving as &$user) {
                                    if($counter > 0) {
                                        echo "<li class='item'>";
                                    } else {
                                        echo "<li class='first_item'>";
                                    }
                                        echo "<h2 class='platform'><input type='checkbox' id='".$user->getUsername()."'/></h2>";

                                        echo "<dl class='ad'>";

                                            echo "<dt class='name'>User Index</dt>";
                                            echo "<dd class='value'>".$user->getUserIndex()."</dd>";

                                            echo "<dt class='name'>Username</dt>";
                                            echo "<dd class='value'>".$user->getUsername()."</dd>";

                                            echo "<dt class='name'>Role</dt>";
                                            echo "<dd class='value'>".$user->getUserRole()."</dd>";

                                        echo "</dl>";
                                    
                                    $counter++;
                                }
                            echo "</ul>";
                	        
                    	    
                    	    /*table implementation for larger screens*/
                    	    echo "<table class='conversion-rate-table' id='remove_users_table'>";

                                echo "<thead class='header-section'>";
                                    echo "<tr class='headers'>";
                                        echo "<th class='header' scope='col'>Select</th>";
                                        echo "<th class='header' scope='col'>User Index</th>";
                                        echo "<th class='header' scope='col'>Username</th>";
                                        echo "<th class='header' scope='col'>Role</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                
                                foreach($usersListForRemoving as &$user) {

                                    echo "<tbody class='data-section'>";
                                        echo "<tr class='ad'>";
                                            echo "<td class='cell'><input type='checkbox' id='".$user->getUsername()."'/></td>";
                                            echo "<td class='cell'>".$user->getUserIndex()."</td>";
                                            echo "<td class='cell'>".$user->getUsername()."</td>";
                                            echo "<td class='cell'>".$user->getUserRole()."</td>";
                                        echo "</tr>";
                                    echo "</tbody>";
                    	        }
                    	    echo "</table>";
                	    } else {
                	        echo "there are no users!";
                	    }
            	    ?>
                </center>
        
            </div>
        </div>
        
        <?php
            if(count($usersListForRemoving) > 0) {
                $buttonVisibility = 'style="display: block;"';
            } else {
                $buttonVisibility = 'style="display: none;"';
            }
	    ?>
	    
        <div class="container-md" <?php print $buttonVisibility; ?>>
            <div  class="bg-light" id="removeUsersButtonContainer">
                <center>
	            	<?php
                	    if(count($usersListForRemoving) > 0) {
                	        echo '<button class="removeUsersButton" id="btn_remove_user" onclick="removeUsers()">r e m o v e &nbsp; u s e r s</button>';
                	    }
                	?>
                </center>
            </div>
        </div>
    </body>
    
    
    <script language="javascript">

        function isListDisplayed() {
            var isDisplayed = false;
            const listObject = document.getElementById("remove_users_list");
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
            var listObject = document.getElementById("remove_users_list");
            var items = listObject.getElementsByTagName("li");
            return items.length;
        }
        
        function getSelectedUsersFromList(listLength, stringBuilder) {
            var listObject = document.getElementById("remove_users_list");
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
            const tableObject = document.getElementById("remove_users_table");
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
            var tableObject = document.getElementById("remove_users_table");
            var nrOfRows = tableObject.rows.length;
            return nrOfRows;
        }
        
        function getSelectedUsersFromTable(tableLength, stringBuilder) {
            var selectionColumn = 0;
            var firstChild = 0;
            var tableObject = document.getElementById("remove_users_table");
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
            var listObject = document.getElementById("remove_users_list");
            /*clear old new comers list*/
            while(listObject.firstChild) {
                listObject.removeChild(listObject.firstChild);
            }
        }
        
        function insertItemIntoList(row) {
            var listObject = document.getElementById("remove_users_list");
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
            dtItem.appendChild(document.createTextNode("User Index"));
            dlItem.appendChild(dtItem);
            var ddItem = document.createElement("dd");
            ddItem.setAttribute("class", "value");
            ddItem.appendChild(document.createTextNode(row.userindex));
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
            dtItem.appendChild(document.createTextNode("Role"));
            dlItem.appendChild(dtItem);
            var ddItem = document.createElement("dd");
            ddItem.setAttribute("class", "value");
            ddItem.appendChild(document.createTextNode(row.role));
            dlItem.appendChild(ddItem);
            
            newLi.appendChild(dlItem);
            
            listObject.appendChild(newLi);
        }
        
        function clearTable() {
            var tableObject = document.getElementById("remove_users_table");
            /*clear old remaining users table*/
            while(tableObject.rows.length > 1) {
                tableObject.deleteRow(1);
            }
        }
        
        function insertRowIntoTable(row) {
            var tableObject = document.getElementById("remove_users_table");
            var rowRemainingUser = tableObject.insertRow(tableObject.rows.length);
            var cellSelect = rowRemainingUser.insertCell(0);
            var cellUserIndex = rowRemainingUser.insertCell(1);
            var cellUsername = rowRemainingUser.insertCell(2);
            var cellRole = rowRemainingUser.insertCell(3);
            
            cellSelect.innerHTML = "<input type='checkbox' id='"+row.username+"'/>";
            cellUserIndex.innerHTML = row.userindex;
            cellUsername.innerHTML = row.username;
            cellRole.innerHTML = row.role;
        }

        function removeUsers(){

            var stringBuilder = "[";
            var jsonResult = "";

            stringBuilder = getSelectedUsers(stringBuilder);
            stringBuilder += "]";
            jsonResult = stringBuilder.replace(",]", "]");

            if(jsonResult != "[]") {    
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.open("POST", "https://www.nestinbase.com/monitor/app/user_manager/remove_users/remove_selected_users_redirection.php", true);
                
                xmlHttp.onloadstart = function() {
                    var serverMessages = document.getElementById("serverMessagesContainer");
                    serverMessages.style.display = "block";
                    var list = document.getElementById("serverMessagesList");
                    var newLi = document.createElement("li");
                    newLi.appendChild(document.createTextNode("request in progress!"))
                    list.appendChild(newLi);
                }
                
                xmlHttp.onload = function() {
                    /*not implemented*/
                }
                
                xmlHttp.onloadend = function() {
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
                
                xmlHttp.onprogress = function() {
                    /*not implemented*/
                }
                
                xmlHttp.ontimeout = function() {
                    /*not implemented*/
                }
                
                xmlHttp.onerror = function() {
                    /*not implemented*/
                }

                xmlHttp.onabort = function() {
                    /*not implemented*/
                }
                
                xmlHttp.onreadystatechange = function() {
                    if(this.readyState == 4 && this.status == 200) {
                        
                        if(isListDisplayed()) {
                            clearList();
                        } else if(isTableDisplayed()) {
                            clearTable();
                        }

                        var jsonReminingUsersDataBaseTable = xmlHttp.responseText;
                        var jsonObject = JSON.parse(jsonReminingUsersDataBaseTable);

                        if(jsonObject.length > 0) {
                            for(var i = 0; i < jsonObject.length; i++) {
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
                                var listRemainingUsers = document.getElementById("remove_users_list");
                                if(listRemainingUsers) {
                                    listRemainingUsers.parentNode.removeChild(listRemainingUsers);
                                }
                            } else if(isTableDisplayed()) {
                                /*remove empty table*/
                                var tblRemainingUsers = document.getElementById("remove_users_table");
                                if(tblRemainingUsers) {
                                    tblRemainingUsers.parentNode.removeChild(tblRemainingUsers);
                                }
                            }

                            /*remove: remove user button*/
                            var btnRemoveUser = document.getElementById("btn_remove_user");
                            if(btnRemoveUser) {
                                btnRemoveUser.parentNode.removeChild(btnRemoveUser);
                            }

                            /*remove: remove user button div*/
                            const buttonContainer = document.getElementById('removeUsersButtonContainer');
                            buttonContainer.style.display = 'none';

                            /*add text to div : "no content to show!";*/
                            const tableContainer = document.getElementById('usersTableContainer');
                            const containerText = document.createTextNode('there are no users for removal!');
                            tableContainer.appendChild(containerText);
                        }
                    }
                }
                
                xmlHttp.setRequestHeader('Content-Type', 'text/plain');
                xmlHttp.send(jsonResult);
            } else {
                alert("no user selected!");
            }
        }
    </script>
</html>