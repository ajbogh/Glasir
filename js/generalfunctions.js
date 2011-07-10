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
