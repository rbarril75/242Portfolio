README for CS 242 Assignment3.4
NETID: barril1

/* Please include a short README.txt file along with your submission this week 
that instructs your moderator how to access your portfolio on cPanel. This README 
should also have the SQL statement(s) used to generate your schema along with 
comments describing what each of the fields are used for. */

A moderator may access my cPanel portfolio page by visiting the URL:
http://barril1.projects.cs.illinois.edu/Main.php
(Index page http://barril1.projects.cs.illinois.edu/ is reserved for another class)

My database schema was as simple as it gets. It has for each project, a table
with two attributes, name and comment. Each tuple/row of the table is associated 
with a single comment and its author. The following SQL command creates the table:

CREATE TABLE CommentTable (Name VARCHAR(25), Comment VARCHAR(255))

(I did not implement my comments structure in a more complex way, such as a tree,
in the interest of time, and also because many popular sites with comment threads 
such as Youtube and Twitter (tweets instead of comments) display their comments/tweets
in the same simple way as I do.)

