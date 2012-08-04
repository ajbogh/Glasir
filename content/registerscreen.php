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