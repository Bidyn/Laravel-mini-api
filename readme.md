## Mini Laravel Api example

Laravel API for handling geo IP requests with 3 endpoints:
- /api/v1/json/ip - returns resposne in JSON
- /api/v1/soap/ip - returns response in Soap
- /api/v1/jsonp/ip - returns response in JSONP

Endpoint takes ip as an argument or takes current ip. 

There are Api call limits:
- with token max number of requests per day (10000) and per minute (100)
- without API token - 1000 requests per day, 10 per minute


## Installing
Project comes with base docker-compose and preseeded sqlite db with 1 user with set api_token.
In project dir run command
<pre><code>docker-compose up -d</code></pre>

And project should be available ion browser at http://localhost

## Usage

jsonp endpoint require callback parameter

<pre> /api/v1/jsonp/ip?callback=someFunction</pre>

For authorized call set token via header
<pre>Authorization: Bearer secret</pre>
or parameter
<pre>/api/v1/soap/ip?api_token=secret</pre>
