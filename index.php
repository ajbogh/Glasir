<?php
	require_once('includes/config.inc.php');
	require_once('phpFileTree/php_file_tree.php');
	
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta charset="utf-8" />
	
	<title><?=$lang['title']?></title>
	
	<link rel="stylesheet" type="text/css" href="phpFileTree/styles/default/default.css" />
	<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.6.custom.css" />
	<link rel="stylesheet" type="text/css" href="css/jplayer.blue.monday.css" />
	<link rel="stylesheet" type="text/css" href="css/ui-layout.css" /> <!-- TODO: get rid of this -->
	<link rel="stylesheet" type="text/css" href="css/stylesheet.css" />
	<link rel="stylesheet" type="text/css" href="css/player.css" />
	
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="phpFileTree/php_file_tree_jquery.js"></script>
	<!--<script type="text/javascript" src="js/jquery.layout-latest.js"></script>-->
	<script type="text/javascript" src="js/jquery-ui-1.8.6.custom.min.js"></script>
	<!--<script type="text/javascript" src="js/jQuery.jPlayer.2.0.0/jquery.jplayer.min.js"></script>-->
	<!--<script type="text/javascript" src="js/jquery.dragsort-0.4.min.js"></script>-->
	<script type="text/javascript" src="js/jquery-audivid.js"></script> <!-- old sound controller -->
	<!--<script language="javascript" src="js/soundjs/SoundJS.js"></script>-->  <!-- new sound controller -->
	<script type="text/javascript" src="js/jquery.scrollintoview.min.js"></script>
	<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
	<script type="text/javascript" src="js/generalfunctions.js"></script>
	<script type="text/javascript" src="js/musiccontroller.js"></script>
	<?php
		echo '<script type="text/javascript">
			var mediaFolder = "'.$appconf['mediaFolder'].'";
			</script>';
	?> 
	<script type="text/javascript">
		//var playMode = 'random'; //default, random
		var found;
		var mousePosY = 0;

	
		$(document).ready(function(){
			$("#left").css({"height":$(window).height()-$("#header").height()-$("#footer").height()-$("#leftbuttons").height()-35}); //35 for the padding
			$("#right").css({"width":$(window).width()-$("#leftcontainer").width()-0});
			$("#right").css({"height":$(window).height()-$("#header").height()-$("#footer").height()-25});
			$("#lefthidebar").css({"height":$(window).height()-$("#header").height()-$("#footer").height()-25});
			$("#leftscrollbar").css({"left":($("#lefthidebar").offset().left)+"px","top":($("#lefthidebar").offset().top+$("#leftbuttons").height()+10)+"px" });

			getFileList('<?php echo $appconf['mediaFolder']; ?>',$('#left'));
			
			$('#left').mousewheel(function(event, delta){
				this.scrollTop -= (delta*30);
				positionScrollbar();
				event.preventDefault();
			});

			var originalList = null;
			$("#filter").keyup(function(event){
				if(originalList == null){ originalList = $("#left").clone(); }
				list = originalList.clone();

				if($("#filter").val() != ""){
					$(list).find("li").each(function(){
						found = false;
						recursiveFilter(this,$("#filter").val());
						//if($($(this).children()[0]).html().toLowerCase().indexOf($("#filter").val().toLowerCase()) < 0){ $(this).remove(); }
					});
					$("#left").html($(list));
				}else{
					$("#left").html(originalList.html());
				}
			});
			
			if($.browser.mozilla){ //TODO: Can we make Firefox process the song in the background?
				$("#warning").html("Firefox may require a few seconds between songs. You may have a better experience with Chrome.");
				$("#warning").show();
				setTimeout(function() {
					$("#warning").hide('slide', {}, 205);
	    			}, 5000);
			}

			//get the mouse position
			$(document).mousemove(function(e){
				mousePosY = e.pageY;
			});
			
		});
		var resizeTimeout = null;
		$(window).resize(function(){
			$("#right").hide();
			$("#left").css({"height":$(window).height()-$("#header").height()-$("#footer").height()-$("#leftbuttons").height()-35}); //35 for the padding
			$("#right").css({"width":$(window).width()-$("#leftcontainer").width()-0});
			$("#right").css({"height":$(window).height()-$("#header").height()-$("#footer").height()-25});
			$("#lefthidebar").css({"height":$(window).height()-$("#header").height()-$("#footer").height()-25});
			$("#leftscrollbar").css({"height":($("#left").height()*($("#left").height()/$("#left>ul").height()))+"px" });
			$("#leftscrollbar").css({"left":($("#lefthidebar").offset().left)+"px","top":($("#lefthidebar").offset().top+$("#leftbuttons").height()+10)+"px" });
			positionScrollbar();			
			if(resizeTimeout != null) clearTimeout(resizeTimeout);			
			resizeTimeout = setTimeout(function(){$("#right").show();},100);
		});

		$("#leftscrollbar").delegate("li", "mousedown", function(event) {
			$(this).draggable({
				helper: "clone",
				cursorAt: { left: 5, top: -5 },
				cursor: "move",
				stop: function() {
				        $(this).draggable("destroy");
				}
			});
		});

		/*returns true if the children contain the filter*/
		function recursiveFilter(parent, filter){
			$(parent).find("li").each(function(){ recursiveFilter(this,filter); });

			if(!found){
				if($($(parent).children()[0]).html().toLowerCase().indexOf(filter.toLowerCase()) < 0){ 
					$(parent).remove(); 
					found = true;
				}
			}
		}

		function positionScrollbar(){
			//TODO: don't use delta for scroll position, it's wrong.
			sbh = $("#leftscrollbar").height();
			lhbh = $("#lefthidebar").height();
			lbh = $("#leftbuttons").height();
			conth = $("#left>ul").height();
			st = $('#left').scrollTop();
			//alert(st);
			scrollAreaHeight = lhbh-lbh;
			ratio = st/conth;
			startpos = $("#lefthidebar").offset().top+$("#leftbuttons").height()+10;
			
			//alert(lhbh+" - "+lbh+" - "+scrollAreaHeight)
			$("#leftscrollbar").css({
				"top":(startpos+((scrollAreaHeight-(sbh/4)-2)*ratio))+"px"
			});
		}
	</script>


</head>
<body>
	
	<div id="header">
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
			<span id="volumebutton"><img src="images/audio-volumebutton.png" onclick="showVolume(this);"/></span>
			<span id="gutter">
				<div id="positionbutton"><img src="images/audio-positionbutton.png" /></div>
				<span id="buffering">
					<span id="loading"></span>
				</span>
				<span id="handle" class="ui-slider-handle" />
			</span>
			
			<div class="clear"></div>
			<div id="currentsong"></div>
			<span id="timeleft" />
			<div id="volume">
			</div>
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
	<div id="player"></div>
	<div id="player_hold">
		<!-- <audio id="audioPlayerHold" controls autobuffer="true" preload="auto"></audio> -->
	</div>
	<!-- contains the left and right containers 
		(files and playlist) -->
	<div id="container">
		<!-- left contains the file list -->
		<div id="leftcontainer">
			<div id="leftsubcontainer"> <!-- float left -->
				<div id="leftbuttons">
					<label for="filter">Filter:</label><input type="text" id="filter" />
				</div>
				<div id="left" class="php-file-tree"></div>
				<div id="leftscrollbar"></div>
			</div>
			<div id="lefthidebar"></div> <!-- 100% high -->
			<div class="clear"></div>
		</div>
		<!-- right contains the playlist and other cool stuff -->
		<div id="right">
			<?php if(!isset($_SESSION['id'])) echo "<div id=\"loginNotice\">You are not logged in.</div>"; ?>
		</div>
	</div>
	<div class="clear"></div>
	<!-- footer -->
	<div id="footer">
		<div id="playmode" class="playmode" onclick="cyclePlayMode();" title="Play mode: continuous/shuffle"></div>
		<div id="autoplay" class="playmode" onclick="cycleAutoplay();" title="Automatic Play"></div>
	</div>



	<div class="clear"></div>



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
</body>
</html> 
