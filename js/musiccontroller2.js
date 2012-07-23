/* Setup */
function init() {
	startLoad();
	enableSet("sound", false);
	enableSet("instance", false);
}

/* Preload sounds. Will not work on iOS
	All Sounds are pre-populated into the library list with the (waiting) label
*/
function startLoad() {
	enableSet("load", false);
	var list = [
		{name:"Steal", src:["mediaservice.php?type=mp3&file="+escape(filename)","../assets/GU-StealDaisy.mp3"], instances:4},
		{name:"Humm (MP3)", src:"../assets/Humm.mp3"}, // NOTE: mp3-only
		{name:"Humm (OGG)", src:"../assets/Humm.ogg"}, // NOTE: ogg-only
		{name:"Damage1", src:["../assets/R-Damage.mp3","../assets/R-Damage.ogg"], instances:4},
		{name:"Damage2", src:["../assets/S-Damage.mp3","../assets/S-Damage.ogg"], instances:4},
		{name:"Thunder", src:["../assets/Thunder1.mp3","../assets/Thunder1.ogg"], instances:2},
		{name:"ToneWobble", src:["../assets/ToneWobble.mp3","../assets/ToneWobble.ogg"], instances:2},
		{name:"CabinBoy", src:["../assets/U-CabinBoy3.mp3","../assets/U-CabinBoy3.ogg"], instances:2},
		{name:"GameBG", src:["../assets/M-GameBG.mp3","../assets/M-GameBG.ogg"], instances:2},
		{name:"Machinae Supremacy", src:["../assets/18-machinae_supremacy-lord_krutors_dominion.mp3","../assets/18-machinae_supremacy-lord_krutors_dominion.ogg"], instances:2}
	];
	
	/* 
	// For later. iOS will not preload audio, so we must pre-populate the list so audio can be played
	var form = $("#playControls").get(0);
	form.library.remove(0);
	for (var i=0, l=list.length; i<l; i++) {
		form.library.options.add(new Option(list[i].name + " (waiting)", list[i].name));	
	}
	form.library.disabled = false;*/
	
	SoundJS.addBatch(list);
	
	SoundJS.onSoundLoadComplete = handleComplete;
	//SoundJS.onProgress = handleProgress;
	SoundJS.onLoadQueueComplete = handleAllComplete;
	SoundJS.onSoundEnded = handleSoundEnded;
}

/* Display the progress of the load */
function handleProgress(progress) {
	var progress = Math.round(1/progress*100) + "%"
}

/* When each sound completes, it is replaced in the list with an entry that shows the available instances */
function handleComplete(sound, name) {
	var form = $("#playControls").get(0);
	var library = form.library;
	
	if (library.options[0].value == "-1") {
		library.remove(0);
	}
	
	for (var i=0, l=library.options.length; i<l; i++) {
		var item = library.options[i];
		if (item.value == name) {
			form.library.remove(i);
			break;
		}
	}
	form.library.options.add(new Option(name + " ("+SoundJS.getNumInstances(name)+")", name));
	if (library.disabled) { library.disabled = false; }
}

function handleAllComplete() {}

/* A reference to the selected item */
var selectedItem;

/* When a library item is selected, the UI is enabled to play it */
function selectItem(select) {
	// Enable UI!
	var item = select.options[select.selectedIndex];
	if (item == null) {
		enableSet("sound", false);
		selectedItem = null;
	} else {
		enableSet("sound", true);
		selectedItem = item.value;
	}
}

/* Plays a sound using SoundJS.
	The sound is added to the "nowPlaying" list while it is playing */
function playSound() {
	var form = $("#playControls").get(0);
	var field = form.interrupt;
	var interrupt = field.options[field.selectedIndex];
	
	var result = SoundJS.play(selectedItem, field.value, 1, form.loop.checked);
	
	if (result >= 0) {
		// Remove Default
		form = $("#instanceControls").get(0);
		if (form.nowPlaying.options[0].value == "-1") {
			form.nowPlaying.remove(0);	
		}
		
		var id = selectedItem+"_"+result;
		removeSound(id, true);
		
		// Add latest. To add at beginning like I want we have to put in an IE hack, so forget that.
		form.nowPlaying.options.add(new Option(selectedItem + " ("+result+")", id));
		form.nowPlaying.disabled = false;
	}
}

/* Remove a sound from the "nowPlaying" list. */
function removeSound(id, resetItems) {
	var form = $("#instanceControls").get(0);
	// Remove existing if this is an override
	for (var i=0, l=form.nowPlaying.options.length; i<l; i++) {
		if (form.nowPlaying.options[i].value == id) { 
			form.nowPlaying.remove(i); 
			break;
		}
	}
		
	if (resetItems != true && form.nowPlaying.options.length == 0) {
		form.nowPlaying.options.add(new Option("-- No Audio Playing --","-1"));
		form.nowPlaying.disabled = true;
	}

}

/* An instance of a playing sound has been selected.
	The instance controls are enabled */
function selectInstance(select) {
	var form = $("#instanceControls").get(0);
	if (form.nowPlaying.selectedIndex > -1) {
		var item = form.nowPlaying.options[form.nowPlaying.selectedIndex];
		var parts = item.value.split("_");
		form.volume.value = SoundJS.getVolume(parts[0], parts[1]) * 100;
		enableSet("instance", true);
	} else {
		enableSet("instance", false);
	}
}

/* Stop all sounds, and clear the "nowPlaying" list. */
function stopAllSounds() {
	SoundJS.stop();
	
	// Update UI
	var form = $("#instanceControls").get(0);
	while (form.nowPlaying.options.length) { form.nowPlaying.remove(0); }
	form.nowPlaying.options.add(new Option("-- No Audio Playing --", "-1"));
	form.nowPlaying.disabled = true;
}

/* Stop playing a specific sound instance. */
function stopSound() {
	// Pull selected info, and stop it.
	var form = $("#instanceControls").get(0);
	if (form.nowPlaying.disabled) { return; }
	if (form.nowPlaying.selectedIndex == -1) { return; }
	var id = form.nowPlaying.options[form.nowPlaying.selectedIndex].value;
	var parts = id.split("_");
	
	SoundJS.stop(parts[0], parts[1]);
	removeSound(id);
	
	enableSet("instance", false);
}

/* A sound has completed. Remove it from the "nowPlaying" list */
function handleSoundEnded(sound, name, index) {
	removeSound(name + "_" + index);
}

/* Change the volume of a sound instance */
function changeVolume(slider) {
	var form = $("#instanceControls").get(0);
	var item = form.nowPlaying.options[form.nowPlaying.selectedIndex];
	if (item == null) { return; }
	var parts = item.value.split("_");
	SoundJS.setVolume(slider.value/100, parts[0], parts[1]);
}

/* Change the master volume of SoundJS */
function changeMasterVolume(slider) {
	SoundJS.setMasterVolume(slider.value/100);
}

/* Toggle the enabled property of a named set of components in a specific form */
function enableSet(name, enabled) {
	switch (name) {
		case "load":
			enableItems("loadForm", enabled, ["startLoad"]);
			break;
		case "sound":
			enableItems("playControls", enabled, ["playPause", "interrupt", "loop"]);
			break;
		case "instance":
			enableItems("instanceControls", enabled, ["volume", "stop"]);
			break;
	}
}

/* Toggle the enabled property of a number of named elements in a form */
function enableItems(formName, value, items) {
	var form = $("#" + formName).get(0);
	if (form == null) { return; }
	for (var i=0, l=items.length; i<l; i++) {
		var item = form[items[i]];
		if (item == null) { continue; }
		item.disabled = !value;	
	}
}
