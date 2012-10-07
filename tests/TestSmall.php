<?php

require_once 'Parser.php';
require_once 'OutputEngine.php';

//enable debugging
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);

//load svnlist.xml and svnlog.xml
$svnlist = simplexml_load_file('http://barril1.projects.cs.illinois.edu/testList.xml')
or die('unable to load svn list');

$svnlog = simplexml_load_file('http://barril1.projects.cs.illinois.edu/testLog.xml')
or die('unable to load svn log');

//parse svnlist and svnlog; create data structure
$parser = new Parser();
$projects = $parser->parse($svnlist, $svnlog);

//output data structure in XML form
$output_engine = new OutputEngine();
$output_engine->outputXML($projects);

?>