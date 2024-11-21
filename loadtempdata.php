<!DOCTYPE html> <!-- Example 03: setup.php -->
<html>
  <head>
    <title>Setting up database</title>
  </head>
  <body>
    <h3>Setting up...</h3>

<?php
  require_once 'functions.php';

  // Insert temporary data
  queryMysql("INSERT INTO members (user, pass) VALUES 
              ('alice', 'password123'),
              ('bob', 'securepass'),
              ('charlie', 'pass456'),
              ('dave', 'mypassword'),
              ('eve', 'qwerty123')");

  queryMysql("INSERT INTO messages (auth, recip, pm, time, message) VALUES 
              ('alice', 'bob', 'P', 1616176612, 'Hey Bob! How are you?'),
              ('bob', 'alice', 'P', 1616176655, 'Hi Alice! I am good, thanks!'),
              ('charlie', 'dave', 'P', 1616176699, 'Hey Dave, long time no see!'),
              ('dave', 'charlie', 'P', 1616176750, 'Hi Charlie! Yes, it has been a while.'),
              ('eve', 'alice', 'P', 1616176800, 'Hello Alice, are you free to chat?')");

  queryMysql("INSERT INTO friends (user, friend) VALUES 
              ('alice', 'bob'),
              ('alice', 'charlie'),
              ('bob', 'dave'),
              ('charlie', 'eve'),
              ('dave', 'alice')");

  queryMysql("INSERT INTO profiles (user, text) VALUES 
              ('alice', 'Hi, I am Alice! I love coding and coffee.'),
              ('bob', 'Bob here. Just a regular guy who likes tech and travel.'),
              ('charlie', 'Charlie is my name, exploring is my game!'),
              ('dave', 'Dave here. Big fan of open-source projects and music.'),
              ('eve', 'Hey there! I am Eve. I enjoy art and photography.')");

  echo "<p>Data inserted successfully into all tables.</p>";

?>

    <br>...done.
  </body>
</html>