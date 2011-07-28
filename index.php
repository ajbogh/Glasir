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
	<link rel="stylesheet" type="text/css" href="css/player.css" />
	
	<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="phpFileTree/php_file_tree_jquery.js"></script>
	<script type="text/javascript" src="js/jquery.layout-latest.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.8.6.custom.min.js"></script>
	<script type="text/javascript" src="js/jQuery.jPlayer.2.0.0/jquery.jplayer.min.js"></script>
	<script type="text/javascript" src="js/jquery.dragsort-0.4.min.js"></script>
	<script type="text/javascript" src="js/jquery-audivid.js"></script>
	<script type="text/javascript" src="js/jquery.scrollintoview.min.js"></script>
	<script type="text/javascript" src="js/generalfunctions.js"></script>
	<script type="text/javascript" src="js/musiccontroller.js"></script>
	<script type="text/javascript">
		//var playMode = 'random'; //default, random

	
		$(document).ready(function(){
			//if(!$.browser.mozilla && ! $.browser.webkit && !$.browser.opera) $('body').html("<h1 style=\"color:black;\">This website only supports Firefox or Chrome. Chrome will be your faster choice.</h1>");
			//else{
				getFileList('<?php echo $appconf['mediaFolder']; ?>',$('#filetree'));
	
				$('body').layout({ 
						applyDefaultStyles: true
						,west__size:300
						,north__size:125
						,north__resizable:false
						,north__slidable:false
						,north__closable:false
						,north__spacing_open:1
						,south__size:40
					});
				theColor = "#000";
	
		    	$(".ui-layout-center").dragsort({dragBetween:true, scrollContainer:".ui-layout-center"});
		    	$(".ui-layout-center").css("background-color",theColor);
		    	$(".ui-layout-north").css("background-color",theColor);
		    	$(".ui-layout-south").css("background-color",theColor);
		    	//$(".ui-layout-south").css("display","none");
		    	$(".ui-layout-east").css("background-color",theColor);
		    	$(".ui-layout-west").css("background-color",theColor);
			//}
			
			if($.browser.mozilla){ //note: Can we make Firefox process the song in the background?
				$("#warning").html("Firefox may require a few seconds between songs. You may have a better experience with Chrome.");
				$("#warning").show();
				setTimeout(function() {
        			$("#warning").hide('slide', {}, 500);
    			}, 5000);
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
		<div id="warning"></div>
		<img src="images/logo.png" style="height:100px" />
		<img src="images/ideas/cw8lDg_1.png" style="height:50px;" /><!--<img src="images/Glasir.png" />-->
		<?php 
			if(!isset($_SESSION['id'])){ //show login
		?>
		
		<div id="loginregister" class="login">
			<a href="javascript:void(0);" onclick="showLogin(this);">
				Login
			</a>
			||
			<a href="javascript:void(0);" onclick="showRegister(this);">
				Register
			</a>
		</div>
		<?php 
			}else{ //user logged in
				//display playlist
		?>
		<div id="player-new">
			<span id="previousbutton"><img src="images/audio-previousbutton.png" onclick="playprevious();" /></span>
			<span id="playtoggle"><img src="images/audio-playbutton.png" onclick="playtoggle(this);" /></span>
			<span id="pausetoggle"><img src="images/audio-pausebutton.png" onclick="playtoggle(this);" /></span>
			<span id="nextbutton"><img src="images/audio-nextbutton.png" onclick="playnext();" /></span>
			<span id="gutter">
				<div id="positionbutton"><img src="images/audio-positionbutton.png" /></div>
				<span id="buffering">
					<span id="loading"></span>
				</span>
				<span id="handle" class="ui-slider-handle" />
			</span>
			<span id="timeleft" />
			<div class="clear"></div>
			<div id="currentsong"></div>
		</div>
		<div class="clear"></div>
		<div id="account" class="login">
			Welcome <?php echo $_SESSION['username']; ?>! || <a href="javascript:void(0);" onclick="doLogout()">Log Out</a>
		</div>
		<script type="text/javascript">
			//load playlist onready
			$(document).ready(function(){
				getPlaylist();
				
				if(getCookie("playMode") == 'random'){
					if(!$("#previousbutton img").hasClass("random")) $("#previousbutton img").addClass("random");
				}

				setInterval('sessionsaver()',300000);
				
				if(getCookie("playMode") != null){
					$("#playmode").addClass(getCookie("playMode"));
				}else{
					$("#playmode").addClass("default");
				}
				
			});
		</script>
		<?php
			}
		?>
		
	</div>
	<!--<div class="ui-layout-south">-->
		<div id="player"></div>
		<div id="player_hold">
			<!-- <audio id="audioPlayerHold" controls autobuffer="true" preload="auto"></audio> -->
		</div>
	<!--</div>--><!--  end ui-layout-south -->
	<div id="filetree" class="ui-layout-west php-file-tree">
	</div>
	<div id="actionMover"></div>
	<div id="actionMover2"></div>
	<div id="loginscreen">
		<img src="images/removebutton.png" style="width:20px;float:right;" alt="Close" title="Close" onclick="$('#loginscreen').hide();" />
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
		<img src="images/removebutton.png" style="width:20px;float:right;" alt="Close" title="Close" onclick="$('#registerscreen').hide();" />
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
	<div id="status" class="ui-layout-south" style="border:1px solid red;">
		<div id="playmode" class="playmode" onclick="cyclePlayMode();" title="Play mode: continuous/shuffle"></div>
		<div id="autoplay" class="playmode" onclick="cycleAutoplay();" title="Automatic Play"></div>
	</div>
</body>
</html> 
