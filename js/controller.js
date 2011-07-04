jQuery.fn.extend({ 
    disableSelection : function() { 
        this.each(function() { 
            this.onselectstart = function() { 
                	return false;
            	}; 
            this.unselectable = "on"; 
            jQuery(this).css('-moz-user-select', 'none'); 
        }); 
    },
	enableSelection : function(){
		this.each(function() { 
			this.onselectstart = function() {  
            	return true;
        	}; 
            this.unselectable = "off"; 
            jQuery(this).css('-moz-user-select', 'all'); 
        }); 
	}
});

/**
 * Gets a file tree from the server.
 * Appends each folder and file as a UL/LI element
 */
function getFileList(directory,ul){
	$ul = $(ul);
	if($ul.children('ul').length > 0){
		//hide children
		$ul.children('ul').remove();
	}else{
		//show children
		$.ajax({
				type:"GET",
				url:"phpFileTree/php_file_tree.php",
				data:{dir:directory,
						extensions:['mp3','ogg','MP3']
					},
				success:function(data){
					$ul.children('ul').remove();
					$ul.append(data);
				}
		});
	}
}

function MoveLi(node){
	//alert(node.className+" Selected");
	return true;
}

function DropLi(node){
	//alert(node.className+" Dropped");
	return true;
}

/*
 * elem is the anchor of the play button
 */
function playbuttonClick(elem){
	//remove the play button from other playing songs
	$(".playing td a:first-child img").attr('src','images/playbutton.png');
	//add play button to this song
	$(elem).children(':first-child').attr('src','images/playbutton_playing.png');
	//remove playing class from other songs
	$(elem).parent().parent().siblings().removeClass('playing');
	//add playing class to this song
	$(elem).parent().parent().addClass('playing');
	//play this song
	play($(elem).parent().parent().children('td:last-child').html());
	
	//set the last playing song in case the page is refreshed.
	setCookie("playing",$(elem).parent().parent().children('td:last-child').html(),365);
}

/** 
 * Gets the playlist for a user. 
 * The user must be logged in and the session created.
 */
function getPlaylist(){
	//TODO: If a song is currently playing then don't play the next song.
	// In other words, adding a song shouldn't replay the song.
	// Also, check into why adding a song takes so long and messes up the web server. 
	
	$.ajax({
		type:"GET",
		url:"playlist.php",
		dataType:"json",
		success:function(data){
			if(data['return']==0){
				$(".ui-layout-center").empty();
				$playlist = $("<table class=\"playlist\"></table>");
				headers = "<tr>";
				headers += "<th></th><th>Title</th><th>Artist</th><th>Album</th><th>Genre</th><th>Year</th><th>Bitrate</th><th>Duration</th><th style=\"display:none;\">Duration</th>";
				headers += "</tr>";
				$playlist.append(headers);
				for(i in data['result']){
					html = "<tr>";
					html += "<td class=\"playlist-buttons\">" +
								"<a href=\"javascript:void(0);\" onclick=\"playbuttonClick(this);\">" +
									"<img src=\"images/playbutton.png\" title=\"Play\" alt=\"Play\" />" +
								"</a>" +
								"<a href=\"javascript:void(0);\" onclick=\"remove($(this).parent().parent().children('td:last-child').html());$(this).parent().parent().remove();\">" +
									"<img src=\"images/removebutton.png\" title=\"Remove\" alt=\"Remove\" />" +
								"</a>"
							"</td>";
					html += "<td>"+data['result'][i]['Title']+"</td>";
					html += "<td>"+data['result'][i]['Artist']+"</td>";
					html += "<td>"+data['result'][i]['Album']+"</td>";
					html += "<td>"+data['result'][i]['Genre']+"</td>";
					html += "<td>"+data['result'][i]['Year']+"</td>";
					html += "<td>"+(Math.ceil(parseInt(data['result'][i]['Bitrate'])/1000))+"kbps</td>";
					secs = ((parseInt(data['result'][i]['Duration'])%60) < 10? "0"+(parseInt(data['result'][i]['Duration'])%60):(parseInt(data['result'][i]['Duration'])%60));
					html += "<td>"+(Math.floor(parseInt(data['result'][i]['Duration'])/60))+":"+secs+"</td>";
					html += "<td style=\"display:none;\">"+data['result'][i]['Filename']+"</td>";
					html += "</tr>";
					$playlist.append(html);
				}
				$(".ui-layout-center").append($playlist);
				
				//play the first song in the list. The song file is in the last column (hidden)
				if(getCookie("playing") != null){
					//set the last playing song in case the page is refreshed.
					var lastSong = getCookie("playing");
					elem = $(".playlist tr td:last-child").filter(function(index){
						return $(this).html() === lastSong;
					});
					playbuttonClick($(".playlist tr td:last-child").filter(function(index){
						return $(this).html() === lastSong;
					}).siblings(":first").children("a:first-child"));	
				}else{
					playbuttonClick($(".ui-layout-center table tr:eq(1) td:first-child a:first-child"));
				}
			}else{
				$(".ui-layout-center").empty().append(data['error']);
			}
		},
		error:function(xhr,ajaxOptions,thrownError){
			alert(thrownError);
	   }
	});
}

//TODO: Fix this
function play(file){
	var audio = document.createElement('audio');
	var alerted = 0;
	var duration = 0;
	var checkPlayInterval;
	var previousCurrentTime = 0;
	audio.setAttribute("id","audioPlayer");
	audio.setAttribute("controls","true");
	audio.autobuffer = true;
	audio.preload = "auto";
	audio.addEventListener( "canplay", function(){ 
			audio.play();
			checkPlayInterval = setInterval(function(){
				if(audio.currentTime >= previousCurrentTime + 1){
					audio.pause();
					clearInterval(checkPlayInterval);
					
					nextSong = getNextSong();
					$nextSongElement = nextSong.element;
					
					playbuttonClick($($nextSongElement).children(".playlist-buttons").children("a:first-child"));
				}else{ //debug check
					//$("#player_hold").html(previousCurrentTime+" - "+audio.currentTime+" - "+duration);
				}
			},1000);
	}, true);
	audio.addEventListener( "timeupdate", function(){ 
			previousCurrentTime = this.currentTime;
			$("#player_hold").html(previousCurrentTime+" - "+this.currentTime+" - "+duration);
		}, true);
	
	//Chrome still has issues with ogg.
	if($.browser.mozilla && audio.canPlayType("audio/ogg")){
		audio.src = "mediaservice.php?type=ogg&file="+escape(file);
		$.ajax({
			   type: "GET",
			   url: audio.src,
			   dataType: "script"
			 });
	}else{ //Chrome fix
		audio.src = "mediaservice.php?type=mp3&file="+escape(file);
	}
	
	$("#player").html(audio);
	audio.load();
	$.ajax({
	   type: "GET",
	   url: audio.src.split("?")[0],
	   data: "duration=true&file="+escape(file),
	   success: function(msg){
		   info = $.parseJSON(msg);
		   
		   duration = info['duration'];
		   
	   },
	   error:function(xhr,ajaxOptions,thrownError){
			alert(thrownError);
	   }
	 });
}

//remove a file from the playlist
function remove(file){
	$.ajax({
	   type: "POST",
	   url: "playlist.php",
	   data: {
		   action:'remove',
		   filename:file
	   },
	   dataType:"json",
	   success: function(msg){
		   //stop it from playing if it is?
	   },
	   error:function(xhr,ajaxOptions,thrownError){
			alert(thrownError);
	   }
	 });
}

//adds a file to the end of the playlist.
function queue(file){
	$.ajax({
		type:"POST",
		url:"playlist.php",
		data:{action:'queue',
			filename:file},
		dataType:"json",
		success:function(data){
			//add result to end of list
			getPlaylist();
		},
		error:function(xhr,ajaxOptions,thrownError){
			alert(xhr.status+" - "+thrownError);
		}
	});
}

//figures out which song is playing based on its background color
//then increments to the next one if the playmode is default
//or a random one if playMode is 'random'
function getNextSong(){
	if(playMode == 'default'){
		next = $(".ui-layout-center .playing").next();
		if(next.length == 0) next = $(".ui-layout-center .playing").siblings().first().next(); //first is a header
		return {file:next.children('td:last-child').html(),
				element:next
			};
	}else if(playMode == 'random'){
		siblings = $(".ui-layout-center .playing").siblings();
		rand = Math.floor(Math.random() * siblings.length);
		if(rand == 0) rand = 1;
		next = $(".ui-layout-center .playing").siblings().eq(rand);
		
		if(next.length == 0) next = $(".ui-layout-center .playing").siblings().first().next(); //first is a header
		return {file:next.children('td:last-child').html(),
				element:next
			};
	}
}

//uses the info from the login div to login
function doLogin(){
	$("#loginscreen div.error").hide();

	$.ajax({
		type:"POST",
		url:"login.php",
		data:{username:$("#loginform input[name=username]").val(),
			password:$("#loginform input[name=password]").val()},
		dataType:"json",
		success:function(data){
			//check for error in data
			if(data['return']==0){
				$("#loginscreen").hide();
				location.reload();
			}else{
				$("#loginscreen div.error").html(data['error']);
				$("#loginscreen div.error").show();
			}
		}
	});
	return false; //prevent form from submitting
}

//uses the info from the login div to login
function doLogout(){
	$.ajax({
		type:"GET",
		url:"logout.php",
		success:function(data){
			location.reload();
		}
	});
	return false; //prevent form from submitting
}

//uses the info from the register div to register
function doRegister(){
	$("#registerscreen div.error").hide();
	$("#registerscreen div.success").hide();
	username = $("#registerform input[name=username]").val();
	password = $("#registerform input[name=password]").val();
	confirm = $("#registerform input[name=confirmpassword]").val();
	email = $("#registerform input[name=email]").val();
	if(username == '' || password == '' || confirm == '' || email == ''){
		$("#registerscreen div.error").html("All fields are required.");
		$("#registerscreen div.error").show();
		return false;
	}
	if(username.length < 4){
		$("#registerscreen div.error").html("Username must be at least 4 characters.");
		$("#registerscreen div.error").show();
		return false;
	}
	if(password != confirm){
		$("#registerscreen div.error").html("Passwords do not match.");
		$("#registerscreen div.error").show();
		return false;
	}
	if(password.length < 4){
		$("#registerscreen div.error").html("Password must be at least 4 characters.");
		$("#registerscreen div.error").show();
		return false;
	}
	
	$.ajax({
		type:"POST",
		url:"register.php",
		data:{username:$("#registerform input[name=username]").val(),
			email:$("#registerform input[name=email]").val(),
			password:$("#registerform input[name=password]").val()},
		dataType:"json",
		success:function(data){
			//check for error in data
			if(data['return'] === 0){
				$("#registerscreen div.success").html(data['result']);
				$("#registerscreen div.success").show();
			}else{
				$("#registerscreen div.error").html(data['error']);
				$("#registerscreen div.error").show();
			}
		},
		error:function(xhr,ajaxOptions,thrownError){
			alert(xhr.status+" - "+thrownError);
		}
	});
	return false; //prevent form from submitting
}

//loads a webpage which refreshes the session.
//keeps a person logged in.
function sessionsaver(){
	$.ajax({
		type:"GET",
		url:"sessionsaver.php"
	});
}

function debug(obj, callback){
    out = "";
    for(i in obj){
        out += i+" - "+obj[i]+"\n";
    }
    callback(out);
}

/*
 * Cookie functions!
 */
function setCookie(c_name,value,exdays){
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name){
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++){
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name){
			return unescape(y);
		}
	}
}


/*$("#filetree").mousedown(function(e){
	alert("Hello");
	$(document).disableSelection();
	var isMouseDown = false;
	var action;
	var appendbefore;
	actionMover = $("#actionMover").empty();
	action = $(this).clone();
	action.appendTo("#actionMover");
	isMouseDown = true;
	actionMover.css("z-index",-999);
	actionMover.offsetLeft = e.pageX;
	actionMover.offsetTop = e.pageY;
	$("body").mousemove(function(e){
			if(isMouseDown){
				actionMover = $("#actionMover");
				$(this).css("cursor","move");
				actionMover.animate({
					left:e.pageX+"px",
					top:e.pageY+"px"},
					0);
				actionMover.show();
				actionMover.css("z-index",999);
				//border-top highlight on elements in the center div.
				centerdiv = $(".ui-layout-center");
				centerdivchildren = centerdiv.children();
				
				//add border for drop position
				for(i=0; i<centerdivchildren.length;i++){
					if(i>0){
						$("#actionMover2").html($(centerdivchildren[i]).offset().top+" - "+e.pageY);
						if(e.pageY < $(centerdivchildren[i]).offset().top 
								&& e.pageY > $(centerdivchildren[i-1]).offset().top){
							appendbefore = $(centerdivchildren[i]);
							$(centerdivchildren[i]).css("border-top","2px solid #000");
						}else{
							$(centerdivchildren[i]).css("border-top","");
						}
					}
				}
			}
		}
	).mouseup(function(e){
		if(isMouseDown){
			//check if the mouse is over the center div.
			centerdiv = $(".ui-layout-center");
			centerdivpos = centerdiv.position();
			if(e.pageX >= centerdivpos.left 
					&& e.pageY >= centerdivpos.top 
					&& e.pageX <= centerdivpos.left + centerdiv.width()
					&& e.pageY <= centerdivpos.top + centerdiv.height()){
				//append the new element
				if(centerdiv.children().length > 0 && appendbefore != null){
					action.insertBefore(appendbefore);
				}else{
					centerdiv.append(action);
				}
				
				actionMover.css({
					left:0,
					top:0	
				});
			}else{
				actionMover.fadeOut(200,function(){
					actionMover.css("z-index",-999);
					actionMover.css({
						left:0,
						top:0
					});
				});
			}
			//align the element between others.
		}
		//clear the border
		for(i=0; i<centerdivchildren.length;i++){
			$(centerdivchildren[i]).css("border-top","");
		}
		
		isMouseDown = false;
		$(document).enableSelection();
		$(this).css("cursor","auto");
		
		//check element drop position.
		//if over layout-center then add new row to layout center, append PHP code.
		//if not, then blink and disappear.
	});
});*/