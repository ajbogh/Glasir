<?php
	require_once('includes/config.inc.php');
	require_once('phpFileTree/php_file_tree.php');
	
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8" />
	
	<title>Glasir - The Golden Tree of Music</title>
	
	<link rel="stylesheet" type="text/css" href="phpFileTree/styles/default/default.css" />
	<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.6.custom.css" />
	<link rel="stylesheet" type="text/css" href="css/jplayer.blue.monday.css" />
	<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
	
	<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="phpFileTree/php_file_tree_jquery.js"></script>
	<script type="text/javascript" src="js/jquery.layout-latest.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.6.custom.min.js"></script>
	<script type="text/javascript" src="js/jQuery.jPlayer.2.0.0/jquery.jplayer.min.js"></script>
	<script type="text/javascript" src="js/jquery.dragsort-0.4.min.js"></script>
	<script type="text/javascript" src="js/controller.js"></script>
	<script type="text/javascript">
		var playMode = 'random'; //default, random

	
		$(document).ready(function(){
			if(!$.browser.mozilla && ! $.browser.webkit) $('body').html("<h1>This website only supports Firefox or Chrome. Chrome will be your faster choice.</h1>");
			else{
				getFileList('<?php echo $appconf['mediaFolder']; ?>',$('#filetree'));
	
				$('body').layout({ 
						applyDefaultStyles: true,
						west__size:300,
						north__size:125,
						north__resizable:false,
						north__slidable:false,
						north__closable:false,
						north__spacing_open:1,
						south__size:100
					});
				theColor = "#000";
	
		    	$(".ui-layout-center").dragsort({dragBetween:true, scrollContainer:".ui-layout-center"});
		    	$(".ui-layout-center").css("background-color",theColor);
		    	$(".ui-layout-north").css("background-color",theColor);
		    	$(".ui-layout-south").css("background-color",theColor);
		    	$(".ui-layout-east").css("background-color",theColor);
		    	$(".ui-layout-west").css("background-color",theColor);
			}
		});
	

	</script>


</head>
<body>
	<div class="ui-layout-center">
		<?php 
			if(!isset($_SESSION['id'])) echo "<div id=\"loginNotice\">You are not logged in.</div>";
		?>
	</div>
	<div class="ui-layout-north">
		<img src="images/logo.png" style="height:100px" />
		<img src="images/Glasir.png" />
		<?php 
			if(!isset($_SESSION['id'])){ //show login
		?>
		<div class="login">
			<a href="javascript:void(0);" onclick="$('#loginscreen').css('left',$(this).offset().left-($('#loginscreen').width()-$(this).width()+15)).css('top',$(this).offset().top+$(this).height()).show();">
				Login
			</a>
			||
			<a href="javascript:void(0);" onclick="$('#registerscreen').css('left',$(this).offset().left-($('#registerscreen').width()-$(this).width()+15)).css('top',$(this).offset().top+$(this).height()).show();">
				Register
			</a>
		</div>
		<?php 
			}else{ //user logged in
				//display playlist
		?>
		<div id="account" class="login">
			Welcome <?php echo $_SESSION['username']; ?>! || <a href="javascript:void(0);" onclick="doLogout()">Log Out</a>
		</div>
		<script type="text/javascript">
			//load playlist onready
			$(document).ready(function(){
				getPlaylist();

				setInterval('sessionsaver()',300000);
			});
		</script>
		<?php
			}
		?>
		
	</div>
	<div class="ui-layout-south">
		<div id="player">
			<!-- <audio id="audioPlayer" controls autobuffer="true" preload="auto" autoplay="autoplay">
	    		<source src="mediaservice.php?type=ogg&file=/media/music/Aqueduct - I Sold Gold/Aqueduct - I Sold Gold - 01 - The Suggestion Box.mp3" type="audio/ogg" />
	    		<source src="mediaservice.php?type=mp3&file=/media/music/Aqueduct - I Sold Gold/Aqueduct - I Sold Gold - 01 - The Suggestion Box.mp3" />
			</audio>  -->
		</div>
		<div id="player_hold">
			<!-- <audio id="audioPlayerHold" controls autobuffer="true" preload="auto"></audio> -->
		</div>
		<div id="player-new">
			<p class="player">
			  <span id="playtoggle"><img src="images/audio-playbutton.png" style="width:30px;height:30px;" /></span>
			  <span id="gutter">
			    <span id="loading" />
			    <span id="handle" class="ui-slider-handle" />
			  </span>
			  <span id="timeleft" />
			  hello
			</p>
		</div>
	</div><!--  end ui-layout-south -->
	<div id="filetree" class="ui-layout-west php-file-tree">
	</div>
	<div id="actionMover"></div>
	<div id="actionMover2"></div>
	<div id="loginscreen">
		<div class="error"></div>
		<form id="loginform" action="" method="post" onsubmit="return doLogin()">
			<table>
				<tr><td><label for="username">Username:</label></td><td><input id="username" name="username" type="text" /></td></tr>
				<tr><td><label for="password">Password:</label></td><td><input id="password" name="password" type="password" /></td></tr>
			</table>
			<input type="submit" name="submit" value="Login" />
			<input type="button" name="cancel" value="Cancel" onclick="$('#loginscreen').hide();$('#loginscreen div.error').hide();" />
		</form>
	</div>
	<div id="registerscreen">
		<div class="error"></div>
		<div class="success"></div>
		<div>If you've registered before with the same email address, your username will be changed.</div>
		<form id="registerform" action="" method="post" onsubmit="return doRegister();">
			<table>
				<tr><td><label for="username">Username:</label></td><td><input id="username" name="username" type="text" /></td></tr>
				<tr><td><label for="email">Email:</label></td><td><input id="email" name="email" type="text" /></td></tr>
				<tr><td><label for="password">Password:</label></td><td><input id="password" name="password" type="password" /></td></tr>
				<tr><td><label for="confirmpassword">Confirm Password:</label></td><td><input id="confirmpassword" name="confirmpassword" type="password" /></td></tr>
			</table>
			<input type="submit" name="submit" value="Register" />
			<input type="button" name="cancel" value="Cancel" onclick="$('#registerscreen').hide();$('#registerscreen div.error').hide();$('#registerscreen div.success').hide();" />
		</form>
	</div>
</body>
</html> 
