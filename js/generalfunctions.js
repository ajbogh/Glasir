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

Array.prototype.diff = function(a) {
    return this.filter(function(i) {return !(a.indexOf(i) > -1);});
};

function showLogin(parent){
	$('#registerscreen').hide();
	$('#loginscreen').css('left',$(parent).offset().left-($('#loginscreen').width()-$(parent).width()+15)).css('top',$(parent).offset().top+$(parent).height()).show();
	
	$('#loginscreen #username').focus();
}

function showRegister(parent){
	$('#loginscreen').hide();
	$('#registerscreen').css('left',$(parent).offset().left-($('#registerscreen').width()-$(parent).width()+15)).css('top',$(parent).offset().top+$(parent).height()).show();
	
	$('#registerscreen #username').focus();
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

/**
 * Used for dragging and dropping nodes.
 * Not currently implemented
 */
function MoveLi(node){
	//alert(node.className+" Selected");
	return true;
}

function DropLi(node){
	//alert(node.className+" Dropped");
	return true;
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
    if(typeof callback === "undefined"){
    	alert(out)
    }else{
    	callback(out);
    }
}





