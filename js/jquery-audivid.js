(function( $ ){

	  var methods = {
  		init : function(){
  			$(this).bind( "timeupdate", function(){ 
  				var elem = this;
  				$(this).attr("stillPlaying",1);
				clearTimeout(stillPlayingTimeout);
				stillPlayingTimeout = setTimeout(function(){
					$(elem).attr("stillPlaying",0);
				},500);
  			});
  		},
		volume : function(options) { 
			if(typeof options != "undefined") $(this).attr('volume',options); 
			return $(this).attr('volume');
		},
		time : function(options) {
			if(typeof options != "undefined"){
				if (parseInt(options) < 0){ // If is negative, do that many seconds from the end
					$(this).attr('currentTime',($(this).attr('duration') + options));
				}else {
					$(this).attr('currentTime',options);
				}
			}
		  	return $(this).attr('currentTime');
		},
		isplaying : function( ) { 
			return $(this).attr("stillPlaying");
		},
		duration : function() { 
		  	return $(this).attr('duration');
		},
		src : function(){
			if(typeof options != "undefined") $(this).attr('src') = options;
			return $(this).attr('src');
		},
		stop : function(){
			this[0].pause();
			$(this).attr('currentTime',0);
			console.log("stopped");
		},
		pause : function(){
			this[0].pause();
			$(this).attr("isPaused",1);
			console.log("paused");
			//$(this).attr("stillPlaying",0);
		},
		play : function(){
			//console.log(this);
			this[0].play();
			$(this).attr("isPaused",0);
			//$(this).attr("stillPlaying",1);
			console.log("played");
		},
		playpause : function(){
			if ($(this).attr("stillPlaying") == 1){
				$(this).audivid("pause");
			}else{
				$(this).audivid("play");
			}
		},
		ispaused : function(){
			return $(this).attr("isPaused");
		}
	};

	$.fn.audivid = function( method ) {
		// Method calling logic
		if ( methods[method] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.audivid' );
		}    
  	};
})( jQuery );
