<?php	
class Comments {
	
	/* The Comments class represents a database of comments for a particular project.
	   The database is connected to and associated with a project ID. */
	function __construct($projectID) {	  
	  $this->projectID = $projectID;

	  $this->DB = $this->connect();
	  
	  // Not sure why I wrote this. But I don't want to remove it just in case.
	  $stmt = $this->DB->prepare("SELECT * FROM $this->projectID");
	  $stmt->execute();
		
	  $commentCount = 0;
	  while ($stmt->fetch()) {
	    $commentCount = $commentCount + 1;
	  }
			
	}

	/* Connect to the database and if a table for this project doesn't exist,
	   create one. */
	function connect() {
	  $host = 'localhost';
	  $user = 'barril1_MyUser';
	  $password = 'illini';
	  $dbName = 'barril1_ProjectComments';
		
	  $dbconn = new mysqli($host, $user, $password, $dbName);
	  
	  $tableExists = $dbconn->prepare("SELECT * FROM $this->projectID");
	  
	  if (!$tableExists) {
	    $query = "CREATE TABLE $this->projectID(Name VARCHAR(25), Comment VARCHAR(255))";
	    $stmt = $dbconn->prepare($query);
	    $stmt->execute();
	  }

	  return $dbconn;		
	}

	/* Sanitizes the name and comment for profanity, then inserts them into table." */
	function addComment($name, $comment) {
	  $name = $this->sanitizeText($name);
	  $comment = $this->sanitizeText($comment);
	  
	  $comment = "\"" . $comment . "\"";
	  $query = "INSERT INTO $this->projectID VALUES('$name', '$comment')";

	  $stmt = $this->DB->prepare($query);
	  $stmt->execute();
	}
	
	/* Censor red flag words. */
	function sanitizeText($text) {
	  $sanitizedText = $text;
	  $sanitizedText = preg_replace('/[^\s]*shit[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*fuck[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*piss[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*cunt[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*cock[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*tits[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*dick[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*gay[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*retarded[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*bitch[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*douche[^\s]*/i', '****', $sanitizedText);
	  $sanitizedText = preg_replace('/[^\s]*asshole[^\s]*/i', '****', $sanitizedText);

	  return $sanitizedText;	  
	}
}
	
?>