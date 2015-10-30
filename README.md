A simple Python Server (originall based on https://github.com/mathisonian/simple-testing-server) 
which emulates enough of the Kodi JSON-RPC interface to allow calling the playVideo from
the netflixbmc Android client

https://github.com/pellcorp/netflixbmc

Start the build in Web server by changing into netflixbmc-server directory and typing:

python jsonrpc.py -p 8080

This will start a server on http://localhost:8080, you can then point your Android client at:

http://localhost:8080


