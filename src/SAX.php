<?php

class Project {
	function __construct() {
		$this->title = "";
		$this->revision = "";
		$this->author = "";
		$this->date = "";
		$this->fileList = array();
	}
}

class File {
	function __construct() {
		$this->path = "";
		$this->size = "";
		$this->revisionList = array();		
	}
}

class Revision {
	function __construct() {
		$this->number = "";
		$this->author = "";
		$this->date = "";
	}
}

class Parser{
	
	function __construct() {
		$this->projectList = array();
		$this->tagType = "";
		$this->fileType = "";
		$this->currentProject = NULL;
		$this->currentFile = NULL;
		$this->logEntry = false;
		$this->revisionNum = "";
		$this->revisionAuthor = "";
		$this->revisionDate = "";
		$this->revisionInfo = "";
		$this->filesToEdit = array();
	}
	
	function tag_open($parser, $tag, $attribs) {
		$this->tagType = $tag;
		
		if ($tag == "ENTRY") {			
			$key = each($attribs);
			$entryKind = $attribs[$key[0]]; // Either "dir" or "file"
			$this->fileType = $entryKind;
		}
		else if ($tag == "COMMIT") {			
			if ($this->fileType == "dir") {
				$key = each($attribs);
				$revision = $attribs[$key[0]];
				$this->currentProject->number = $revision;
			}
		}
		else if ($tag == "LOGENTRY") {
			$this->logEntry = true;
			$key = each($attribs);
			$revisionNum = $attribs[$key[0]];
		}
	}

	function cdata($parser, $data) {
		if ($this->tagType == "NAME") {
			$this->handleNameData($data);
		}
		if ($this->tagType == "AUTHOR") {
			$this->handleAuthorData($data);
		}
		if ($this->tagType == "DATE") {
			$this->handleDateData($data);
		}
		if ($this->tagType == "SIZE") {
			$this->handleSizeData($data);
		}
		if ($this->tagType == "PATH") {
			$this->handlePathData($data);
		}
	}
	
	function handleNameData($data) {
		if ($this->fileType == "dir") {
			if (strpos($data, '/') === false) {
				$this->currentProject = new Project();
				$this->currentProject->title = $data;
			}
			else {
				$this->fileType = "subDirectory"; // Ignore sub-directories
			}
		}
		else if ($this->fileType == "file") {
			$this->currentFile = new File();
			$this->currentFile->path = $data;
		}
	}
	
	function handleAuthorData($data) {
		if ($this->logEntry == true) {
			$this->revisionAuthor = $data;
			return;
		}
		
		if ($this->fileType == "dir")
			$this->currentProject->author = $data;
	}
	
	function handleDateData($data) {
		if ($this->logEntry == true) {
			$this->revisionDate = $data;
			return;
		}
		
		if ($this->fileType == "dir")
			$this->currentProject->date = $data;
	}
	
	function handleSizeData($data) {
		$this->currentFile->size = $data;
	}
	
	function handlePathData($data) {
		$projectName = $this->getProjectName($data);
		$fileName = $this->getFileName($data);
	}
	
	function getProjectName($data) {
		return;
	}
	
	function getFileName($data) {
		return;
	}
	
	function tag_close($parser, $tag) {
		$this->tagType = "";
		
		if ($tag == "ENTRY") {
			if ($this->fileType == "dir") {
				$this->projectList[$this->currentProject->title] = $this->currentProject;		
			}		
			if ($this->fileType == "file")
				$this->currentProject->fileList[$this->currentFile->path] = $this->currentFile;
			$this->fileType = "";
		}
		else if ($tag == "LOGENTRY") {
			$this->logEntry = false;
		}
	}

	function xml()
	{
		$this->parser = xml_parser_create();
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, "tag_open", "tag_close");
		xml_set_character_data_handler($this->parser, "cdata");
		return $this->parser;
	}

	function parse($data)
	{
		xml_parse($this->parser, $data);
		return $this->projectList; //projects is the data structure we built up in the 
					        	   //parser object that holds all of our project data
	}
}

class OutputEngine {

	function outputXML($projectList) {
		$dom = new DOMDocument('1.0');
		$dom->formatOutput = true;
		
		$root = $dom->createElement('SVNDump');
		$dom->appendChild($root);
		
		$numProjects = count($projectList);
		foreach ($projectList as &$project) {
			$newProject = $dom->createElement('project');
			$root->appendChild($newProject);
			
			$title = $dom->createElement('title', $project->title);
			$newProject->appendChild($title);
			
			$author = $dom->createElement('author', $project->author);
			$newProject->appendChild($author);

			$date = $dom->createElement('date', $project->date);
			$newProject->appendChild($date);
			
			$files = $dom->createElement('files');
			$newProject->appendChild($files);
			
			foreach($project->fileList as &$fileObject) {
				$file = $dom->createElement('file');
				$files->appendChild($file);
				
				$path = $dom->createElement('path', $fileObject->path);
				$file->appendChild($path);
				
				$size = $dom->createElement('size', $fileObject->size);
				$file->appendChild($size);
			}
		}
		
		echo $dom->saveXML(); 

	} 
}

//enable debugging
ini_set('display_errors', true);
ini_set('error_reporting', E_ALL);

//get list data
$list_fp = fopen("http://barril1.projects.cs.illinois.edu/svn_list.xml","r")
or die("Error reading XML data.");
$raw_list_data = "";
while ($data = fread($list_fp, 4096))
	$raw_list_data = $raw_list_data . $data;

// parse data into project data structure
$xml_parser = new Parser();
$parser = $xml_parser->xml();
$projects = $xml_parser->parse($raw_list_data);

// output XML file representing data structure
$output_engine = new OutputEngine();
$output_engine->outputXML($projects);

// free parser memory; close list file
xml_parser_free($parser);
fclose($list_fp);

?>