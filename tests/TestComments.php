<?php	

require_once("commentsClass.php");

class TestSuite {
	
	function __construct() {	  
	  $this->comments = new Comments("TestProjectName");
	}
	
	function testAddComment() {
	  $query = "DELETE FROM TestProjectName";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $this->comments->addComment('Steve Jobs', 'Apple > Microsoft');
	  
	  $query = "SELECT * FROM TestProjectName WHERE Name = 'Steve Jobs'";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $stmt->bind_result($name, $comment);
	  
	  $numRows = 0;
	  while ($stmt->fetch()) {
	    $numRows = $numRows + 1;
	    if ($name != "Steve Jobs") {
	      echo "Test Add Comment = Failure<br />";
	      return;
	    }
	    if ($comment != "\"Apple > Microsoft\"") {
	      echo "Test Add Comment = Failure<br />";
	      return;
	    }
	  }
	      	  
	  if ($numRows == 1)
	    echo "Test Add Comment = Success<br />";
	  else {
	    echo "Test Add Comment = Failure<br />";
	  }
	}
	
	function testContentFilter() {
	  $query = "DELETE FROM TestProjectName";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $this->comments->addComment('CommentGuy', 'This is off-topic, but Dick Butkus was a great player.');

	  
	  $query = "SELECT * FROM TestProjectName WHERE Name = 'CommentGuy'";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $stmt->bind_result($name, $comment);
	  
	  $stmt->fetch();
	  if ($comment == "\"This is off-topic, but **** Butkus was a great player.\"")
	    echo "Test Content Filter = Success<br />";
	  else
	    echo "Test Content Filter = Failure<br />";
	}
	
	function testContentFilter2() {
	  $query = "DELETE FROM TestProjectName";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $this->comments->addComment('CommentGuy', 'Gregor Fucka is an Italian basketball player');

	  
	  $query = "SELECT * FROM TestProjectName WHERE Name = 'CommentGuy'";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $stmt->bind_result($name, $comment);
	  
	  $stmt->fetch();
	  if ($comment == "\"Gregor **** is an Italian basketball player\"")
	    echo "Test Content Filter 2 = Success<br />";
	  else
	    echo "Test Content Filter 2 = Failure<br />";
	}
	
	function testContentFilter3() {
	  $query = "DELETE FROM TestProjectName";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $this->comments->addComment('CommentGuy', 'Bitch used to solely mean female dog');

	  
	  $query = "SELECT * FROM TestProjectName WHERE Name = 'CommentGuy'";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $stmt->bind_result($name, $comment);
	  
	  $stmt->fetch();
	  if ($comment == "\"**** used to solely mean female dog\"")
	    echo "Test Content Filter 3 = Success<br />";
	  else
	    echo "Test Content Filter 3 = Failure<br />";
	}
	
	function testSQLInjection() {
	  $query = "DELETE FROM TestProjectName";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $this->comments->addComment('God', 'Great project!');
	  
	  // SQL Injection Attack
	  $query = "'; DELETE FROM TestProjectName WHERE 1 OR Name = '"; 
	  $stmt = $this->comments->DB->prepare($query);
	  if ($stmt != false)
  	    $stmt->execute();
	  //
	  
	  $query = "SELECT * FROM TestProjectName WHERE Name = 'God'";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $stmt->bind_result($name, $comment);
	  
	  $numRows = 0;
	  while ($stmt->fetch()) {
	    $numRows = $numRows + 1;
	    if ($name != "God") {
	      echo "Test Prevent Injection = Failure<br />";
	      return;
	    }
	    if ($comment != "\"Great project!\"") {
	      echo "Test Prevent Injection = Failure<br />";
	      return;
	    }
	  }
	      	  
	  if ($numRows == 1)
	    echo "Test Prevent Injection = Success<br />";
	  else {
	    echo "Test Prevent Injection = Failure<br />";
	  }
	  
	}
	
	function testSQLInjection2() {
	  $query = "DELETE FROM TestProjectName";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $this->comments->addComment('Hater', 'Terrible project!');
	  
	  // SQL Injection Attack
	  $query = "' OR 1'"; 
	  $stmt = $this->comments->DB->prepare($query);
	  if ($stmt != false)
  	    $stmt->execute();
	  //
	  
	  $query = "SELECT * FROM TestProjectName WHERE Name = 'Hater'";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $stmt->bind_result($name, $comment);
	  
	  $numRows = 0;
	  while ($stmt->fetch()) {
	    $numRows = $numRows + 1;
	    if ($name != "Hater") {
	      echo "Test Prevent Injection 2 = Failure<br />";
	      return;
	    }
	    if ($comment != "\"Terrible project!\"") {
	      echo "Test Prevent Injection 2 = Failure<br />";
	      return;
	    }
	  }
	      	  
	  if ($numRows == 1)
	    echo "Test Prevent Injection 2 = Success<br />";
	  else {
	    echo "Test Prevent Injection 2 = Failure<br />";
	  }
	  
	}
	
	function testSQLInjection3() {
	  $query = "DELETE FROM TestProjectName";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $this->comments->addComment('MrT', 'I pity the foo()');
	  
	  // SQL Injection Attack
	  $query = "' OR 1'"; 
	  $stmt = $this->comments->DB->prepare($query);
	  if ($stmt != false)
  	    $stmt->execute();
	  //
	  
	  $query = "SELECT * FROM TestProjectName WHERE Name = 'MrT'";
	  $stmt = $this->comments->DB->prepare($query);
	  $stmt->execute();
	  
	  $stmt->bind_result($name, $comment);
	  
	  $numRows = 0;
	  while ($stmt->fetch()) {
	    $numRows = $numRows + 1;
	    if ($name != "MrT") {
	      echo "Test Prevent Injection 3 = Failure<br />";
	      return;
	    }
	    if ($comment != "\"I pity the foo()\"") {
	      echo "Test Prevent Injection 3 = Failure<br />";
	      return;
	    }
	  }
	      	  
	  if ($numRows == 1)
	    echo "Test Prevent Injection 3 = Success<br />";
	  else {
	    echo "Test Prevent Injection 3 = Failure<br />";
	  }
	  
	}
}

$tests = new TestSuite();

$tests->testAddComment();
$tests->testContentFilter();
$tests->testContentFilter2();
$tests->testContentFilter3();
$tests->testSQLInjection();	
$tests->testSQLInjection2();		
$tests->testSQLInjection3();		
	
		
?>	