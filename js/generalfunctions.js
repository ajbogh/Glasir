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