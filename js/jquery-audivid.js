jQuery.fn.audivid = function(var1,var2,var3)
{
	if (typeof var1 == "string" ) { var1 = var1.toLowerCase(); }
	if (! var2) // Getters
	{
		if (var1 == "volume")
		{
			return $(this).attr("volume");
		} else if (var1 == "time")
		{
			return $(this).attr('currentTime');
		} else if (var1 == "isplaying")
		{
			t1 = $(this).attr('currentTime');
			//sleep for a millesecond
			var startTime = new Date().getTime();
			while (new Date().getTime() < startTime + 1);//curDate == date);
			t2 = $(this).attr('currentTime');
			if(t1 == t2){ //must be firefox
				return $(this).attr("isPlaying");
			}
			return (t1 != t2);
		} else if (var1 == "duration")
		{
			return $(this).attr('duration');
		} else if (var1 == "src")
		{
			return this.src;
		}
	}
	this.each(function()
	{
		if (var1 == "playpause")
		{
			if ($(this).audivid("isplaying") == 1){ // Playing
				this.pause();
				$(this).attr("isPlaying",0);
			}else{
				this.play();
				$(this).attr("isPlaying",1);
			}
		}
		
		else if (var1 == "volume")
		{
			this.volume = var2;
		}
		else if (var1 == "stop")
		{
			this.pause();
			$(this).attr("isPlaying",0);
			this.currentTime = 0;
		}
		else if (var1 == "pause")
		{
			this.pause();
			$(this).attr("isPlaying",1);
		}
		else if (var1 == "play")
		{
			this.play();
			$(this).attr("isPlaying",1);
		}
		else if (var1 == "time")
		{
			if (var2 < 0) // If is negative, do that many seconds from the end
			{
				this.currentTime = this.duration + var2;
			} else {
				this.currentTime = var2;
			}
		} else if (var1 == "src")
		{
			this.src = var2;
		}
	});
	return this;
};
