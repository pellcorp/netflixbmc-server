#!/usr/bin/env python
from BaseHTTPServer import HTTPServer
from BaseHTTPServer import BaseHTTPRequestHandler
import json
import subprocess
import urlparse
import argparse

PORT = 8080

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='A simple json-rpc server for netflixbmc only.')
    parser.add_argument('-p', '--port', type=int, dest="PORT",
                       help='the port to run the server on; defaults to 8080')

    args = parser.parse_args()

    if args.PORT:
        PORT = args.PORT

class JSONRequestHandler (BaseHTTPRequestHandler):
    def do_GET(self):
        self.send_response(200)

    def do_POST(self):
        try:
            data = json.loads(self.rfile.read(int(self.headers['Content-Length'])))
            if data['method'] == 'Addons.GetAddons':
                self.send_response(200)
                self.wfile.write('Content-Type: application/json\n')
                self.end_headers()
                self.wfile.write("{\"jsonrpc\":\"2.0\",\"id\":\"1\",\"result\":{\"addons\":[{\"addonid\":\"plugin.video.netflixbmc\",\"type\":\"xbmc.python.pluginsource\"}]}}");
            elif data['method'] == 'Player.Open':
                self.send_response(200)
                self.wfile.write('Content-Type: application/json\n')
                self.end_headers()
                file = data['params']['item']['file']
                parsed_url = urlparse.urlparse(file)._asdict()
                query = urlparse.parse_qs(parsed_url['query'])
                movieId = query['url'][0]
                subprocess.Popen(["./browser.sh",movieId,"&"])
                self.wfile.write("{\"jsonrpc\":\"2.0\",\"id\":\"1\",\"result\":{}}");
            else:
                self.send_response(500)
                self.end_headers()
        except Exception as e:
            self.send_response(500)
            self.wfile.write('Content-Type: application/json\n')
            self.end_headers()
            self.wfile.write(e)

server = HTTPServer(("localhost", PORT), JSONRequestHandler)
server.serve_forever()
