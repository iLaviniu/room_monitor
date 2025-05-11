<!DOCTYPE html>

<?php
    ob_start(); /*turn ON output buffering*/
    session_start();
?>


<html lang="en">
<style>
.center {
	display: flex;
	justify-content: center;
}

.padding {
	padding: 50px 35px 0px 35px;
}

input {
	width: 100%;
	padding: 12px 20px;
	margin: 8px 0;
	box-sizing: border-box;
	border: none;
	border-bottom: 2px solid red;
	background-color: #f2f2f2;
	text-align: center;
}

.button {
	width: 100%;
	background-color: red;
	border: none;
	color: white;
	padding: 20px;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	font-size: 24px;
	margin: 4px 2px;
	cursor: pointer;
}
.button_connect {border-radius: 12px;}

</style>

<head>
	<title>404</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="https://www.nestinbase.com/monitor/app/res/icon.png">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body style="background-color: #f2f2f2;">
	<div class="container padding" style="border:0px solid black;">
		<h1 class="center">404 Ooops!</h1>
	</div>
	
	<div class="container padding" style="border:0px solid black;">
		<h1 class="center">server connection error</h1>
	</div>
	
	<div class="container" style="border:0px solid black;">
	    <a class="center" href="https://www.nestinbase.com">return to login</a>
	</div>
	
</body>
</html>