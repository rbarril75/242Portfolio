<script language="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script language="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
<script language="javascript" type="text/javascript">
	function prependName(name) {
	  document.getElementById('comment').value = '@'+name+' ';
	}
</script>
	
<?php

	require_once("commentsClass.php");

	echo "<html><body bgcolor=#D8D8D8>";
	
	// Replace periods with underscores (SQL doesn't allow periods in table names)
	$comments = new Comments(strtr($_GET['id'], ".", "_"));
	echo "<h2>Discussion for PROJECT $comments->projectID</h2>";
        
        /* Onclick event of 'Add Comment' button. Blocks empty comments and replaces
           empty names with "Anonymous" */  
        if (isset($_POST['submit'])) {
  	  $name = $_POST['name'];
  	  $comment = $_POST['comment'];
	  
	  if ($name == "")
	    $name = "Anonymous";
	    
	  if ($comment != "")	    
            $comments->addComment($name, $comment);
        }
        
        /* Fetch comments from table and display them all */
        $stmt = $comments->DB->prepare("SELECT * FROM $comments->projectID");
	$stmt->execute();  
	$stmt->bind_result($name, $comment);
	
	while ($stmt->fetch()) { 
	  echo $name . " says: ";
	  echo $comment;
	  printf("<button onclick=prependName('%s');>Reply</button>", $name);
	  echo "<hr style='width:20%;' align='left'/>";
	}
?>

	<? /* Comment form */ ?>
	<form action='' method='post'>
 	Name: <input type='text' name='name'/><br />
 	Comment:<br />
 	<textarea name='comment' id='comment' rows='4' cols='50'></textarea><br />
 	<input type='submit' name='submit' value='Add Comment' />
	</form>
	
	</body></html>