<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
  
  <head>
    <script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>

    <script type='text/javascript'>
      google.load('visualization', '1', {packages:['table']});
      google.setOnLoadCallback(drawProjectTable);
      
      function drawProjectTable(projectTable, version, author, date, summary) {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Version');
        data.addColumn('string', 'Author');
        data.addColumn('string', 'Date');
        data.addColumn('string', 'Summary');
        data.addRows(1);
	data.setCell(0, 0, version);
	data.setCell(0, 1, author);
	data.setCell(0, 2, date);
	data.setCell(0, 3, summary);
	
        var table = new google.visualization.Table(document.getElementById(projectTable));
        table.draw(data);
      }
    </script>
    
    <script language="javascript">
      function toggleDiscussion(discID) {
        var ref = document.getElementById(discID);
        $(ref).slideToggle(400);
     } 
    </script>
    
    <script language="javascript">       
      function toggleProject(projectID) {
        var ref = document.getElementById(projectID);
        $(ref).slideToggle(400);
      } 
    </script>
    
    <script language="javascript"> 
      function toggleFiles(fileButtonID, filesID) {
        var files = document.getElementById(filesID);
        $(files).slideToggle(400);
     } 
    </script>
    
    <script language="javascript">
      function loadPath(frameID, url) {
        jumpToHash(frameID);
        window.frames[frameID].location.href = url;
      }
    </script>
    
    <script language="javascript">
      function jumpToHash(hashTag) {
	  window.location = "#" + hashTag;
      }
    </script>
  	
    <style type="text/css">
      .pj-button { 
        outline: 0; 
        margin:0 4px 0 0; 
        padding: .3em .5em; 
        text-decoration:none !important; 
        cursor:pointer; 
        position: relative; 
        text-align: center; 
        zoom: 1; 
        color: #0033FF;
     }
     
      .mn-button { 
        outline: 0; 
        margin:0 4px 0 0; 
        padding: .3em .5em; 
        cursor:pointer; 
        position: relative; 
        text-align: center; 
        zoom: 1; 
        color: #CC6633;
     }
   
      .ui-button-text { font-size: inherit !important; } 
      h1 {text-align:center;}
      h1,h2,h3{font-family:Helvetica,Arial,'DejaVu Sans','Liberation Sans',Freesans,sans-serif;}
      body {background-color:#D8D8D8}  
      table,th,td{border: 1px solid black;}
      a:link {color:"blue";}    /* unvisited link */
      a:visited {color:"purple";} /* visited link */
      a:hover {color:#FF69B4;}   /* mouse over link */
      a:active {color:#348017;}  /* selected link */
    </style>
  </head>

  <body>

  <h1>
    <img src="uiuclogo.jpg" alt="ERROR" width="30" height="39" />
    <font color="firebrick">  Ryan Barril - <a href="https://wiki.engr.illinois.edu/display/cs242fa11/Home">CS 242</a> Portfolio  </font>
    <img src="uiuclogo.jpg" alt="ERROR" width="30" height="39" />
  </h1>

  <xsl:for-each select="SVNDump/project">
  <h2>
    <font color="darkblue">
    <a id='{title}ProjectButton' href="javascript:toggleProject('{title}Project')">
      <button class="pj-button ui-state-default ui-corner-all ui-button-text">PROJECT <xsl:value-of select="title"/></button>
    </a>
    </font>
  </h2>
  
  <div id='{title}Project' style='display:none'>

  <div id='{title}Table'></div>
  <script type="text/javascript">
    drawProjectTable('<xsl:value-of select="title"/>Table', '<xsl:value-of select="version"/>', '<xsl:value-of select="author"/>', '<xsl:value-of select="date"/>', '<xsl:value-of select="msg"/>');
  </script> 
  
  <h3>
  <a id='{title}FileButton' href="javascript:toggleFiles('{title}FileButton','{title}Files')">
  <button class="mn-button ui-state-default ui-corner-all ui-button-text">
  Files
  </button></a></h3>
		
  <div id='{title}Files' style='display:none'>

    <xsl:for-each select="files/file">
    
    <table border="1">
    <tr>
      <td bgcolor="whitesmoke">Path</td>
      <td>
        <a href="javascript:loadPath('{../../title}Frame','{url}')">
          <xsl:value-of select="path"/>
        </a>
      </td>
      <td bgcolor="whitesmoke">Size</td>
      <td><xsl:value-of select="size"/></td>
    </tr>
    </table>
    
    <table border="1">
      <xsl:for-each select="revisions/revision">
        <tr>
          <td bgcolor="whitesmoke">Revision</td>
          <td><xsl:value-of select="number"/></td>
          <td bgcolor="whitesmoke">Author</td>
          <td><a href="http://www.facebook.com/ryan.barril"><xsl:value-of select="author"/></a></td>
          <td bgcolor="whitesmoke">Date</td>
          <td><xsl:value-of select="date"/></td>
          <td bgcolor="whitesmoke">Comment</td>
          <td><xsl:value-of select="info"/></td>
        </tr>
      </xsl:for-each>
    </table>
    <BR/>
    
    </xsl:for-each>
    <iframe name='{title}Frame' id='{title}Frame' width="50%" height="300">
    </iframe>
  </div>

  <h3>
  <a href="javascript:toggleDiscussion('{title}Discussion')">
  <button class="mn-button ui-state-default ui-corner-all ui-button-text">
  Discussion</button></a></h3>
  <div id='{title}Discussion' style='display:none'>
    <embed src='comments.php?id={title}' width="600" height="400"></embed>
  </div>
  
  </div>
  </xsl:for-each>
  
  </body>
  </html>
</xsl:template>

</xsl:stylesheet>