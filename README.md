A simple PHP Server which emulates enough of the Kodi JSON-RPC interface to allow calling the playVideo from
the netflixbmc Android client

https://github.com/pellcorp/netflixbmc

Start the build in PHP Web server by changing into netflixbmc-server directory and typing:

php -S localhost:8080 jsonrpc.php &

This will start a server on http://localhost:8080, you can then point your Android client at:

http://localhost:8080


