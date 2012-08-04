<div id="searchPanel" class="panel">
	<img src="images/removebutton.png" style="width:20px;float:right;" class="closeButton" alt="Close" title="Close" onclick="$('#searchPanel').hide();" />
	<div class="error"></div>
	<div class="success"></div>
	<!-- TODO: make search function -->
	<div><input type="text" id="search" name="search" /> <button onclick="search($('#search').val(),$('#searchResults'));">Search</button></div>
	<div id="searchResults"></div>
</div>