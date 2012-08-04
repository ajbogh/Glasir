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