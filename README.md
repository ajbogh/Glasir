## Requirements

1. Linux (Ubuntu, Fedora, Arch, anything)
2. Mysql Server
3. Mysql user configured
4. Blank Mysql database created
5. mpg321 for mp3 to ogg conversion (Firefox browser support)
    
    $ sudo apt-get install mpg321

## Installation

1. Copy config.inc.php.default to config.inc.php
2. Add your own configuration data.
3. Add the Apache user group to the media folder
    
    $ sudo chown ajbogh:www-data media
    
## How Glasir Works

Glasir uses a symbolic link to your media folder to help it browse and process the music files. 
Without the symbolic link within the website directory PHP wouldn't be able to scan through your files.
A temp folder symbolic link is also created to help PHP maintain a limited cache. 
The temp folder is cleared when the system is rebooted* (note, this may change).

Music information is stored in a MySQL database. This includes the duration and how many times the song was played.
In the future this information will go towards choosing a suitable playlist for you.

Glasir performs a file scan to obtain as much music information as possible before a song is played, however
some functions take a bit longer, such as song duration, so a secondary asynchronous process grabs the duration and updates 
the song information in the database. The first time the song is played the duration will say 0, 
but the next time it'll display properly. 

If you're using Firefox then Glasir will automatically choose ogg file formats. This requires a conversion from mp3 to ogg format
which may take a few seconds. This one-time conversion is then stored in the temp folder for future plays.

File browsing is handled one directory at a time, which increases folder navigation and allows Glasir to run on 
limited system specs. Glasir will scan your music folder for all files of certain types and folders and display a list. This
list is generated in Javascript from a JSON output from the server. As you navigate the file tree you will send requests 
back to the server and obtain a new JSON output for the next folder level. Eventually you will reach the songs you want.
New features may include a search algorithm that can scan your entire directory structure for the song you're looking for.
This will try the database first to see if you've already played that song.

Another upcoming feature will be pure randomization. We all like certain songs and we tend to play only about 20 songs, 
even with a list of thousands. Why not take the human factor out of the equation and play all of your songs randomly?
You can then like or unlike songs, maybe even rate them so that the system can automatically choose a playlist for you.