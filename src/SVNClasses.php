<?php

// Simple data classes representing SVN entities

class Project {
	function __construct() {
		$this->title = "";
		$this->author = "";
		$this->date = "";
		$this->version = 1.0;
		$this->revision = "";
		$this->msg = "";
		$this->fileList = array();
	}
}

class File {
	function __construct() {
		$this->path = "";
		$this->url = "";
		$this->size = "";
		$this->revisionList = array();
	}
}

class Revision {
	function __construct() {
		$this->number = "";
		$this->author = "";
		$this->date = "";
		$this->info = "";
	}
}

?>