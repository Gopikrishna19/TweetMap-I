TweetMap Project

Name:   Gopikrishna Sathyamurthy
NetID:  gs1922@nyu.edu
NID:    N14794536

Host:   http://tweetmap-cc.elasticbeanstalk.com

The file structure is organized in the following way

TweetMap
 |--ajax                    <-- it has the php code for the front end to query
 |  |--common.php           <-- contains database configuration
 |  |--data.php             <-- produces json containing list of keywords and their occurances
 |  |--parse.php            <-- finds the keys in a tweet for data.php
 |  |--pins.php             <-- produces json containing list of tweets along with latitutes and longitudes
 |--css                     <-- contains css styles for the website
 |  |--bs.css               <-- basic Bootstrap css file (http://getbootstrap.com)
 |  |--bs-theme.css         <-- theme file for Bootstrap css (http://getbootstrap.com)
 |  |--style.css            <-- local css file
 |--fonts                   <-- contains glyphs used by Bootstrap css (http://getbootstrap.com)
 |--js                      <-- conatins all javascript codes for the website
 |  |--d3.js                <-- javascript library for generating charts (http://d3js.org)
 |  |--jq.js                <-- javascript library for DOM manipulation (http://jquery.com)
 |  |--map.js               <-- contains code to get pins from the pins.php and populate in Google Maps
 |  |--populate.js          <-- query data.php and update the chart with new data
 |--service                 <-- contains PHP Twitter Stream API (https://github.com/fennb/phirehose)
 |  |--OauthPhirehose.php   <-- Phirehose OAuth module (https://github.com/fennb/phirehose)
 |  |--Phirehose.php        <-- Main Phirehose module (https://github.com/fennb/phirehose)
 |  |--TweetStream.php      <-- Command line background php to stream tweets into database
 |  |--UserStreamPhirehose  <-- Optional module from Phirehose (https://github.com/fennb/phirehose)
 .gitingore                 <-- eb git file
 index.html                 <-- main front end file to show map and chart
 README.txt                 <-- the file you are reading
 Web.config                 <-- alternative to .htaccess for iis (ignore it)