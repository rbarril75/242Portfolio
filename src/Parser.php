<?php

require_once 'SVNClasses.php';

class Parser {

	function __construct() {
		$this->projects = array();
	}

	// Given XML data, populate project list with project objects
	function parse($svnlist, $svnlog) {
		$directories = $svnlist->xpath("//entry[@kind = 'dir']");

		foreach ($directories as $directory) {
			if (strpos($directory->name, '/') === false) {
				// Ignore sub-directories
				$project = $this->createProject($svnlist, $svnlog, $directory);
				$this->projects[$project->title] = $project; // Add project to list
			}
		}
		
		return $this->projects;
	}

	// Given an XML object representing a directory, build a project object
	function createProject($svnlist, $svnlog, $directory) {
		$project = new Project();
		$project->title = (string) $directory->name;
		$project->author = (string) $directory->commit->author;
		$project->date = (string) $directory->commit->date;		
				
		// Find all commits of project from svnlog, add information
		$versions = $svnlog->xpath("//path[contains(., '$project->title')]/../..");
		$project->version += (count($versions) - 1) * 0.1;
		$project->revision = $versions[0]['revision'];
		$project->msg = $versions[0]->msg;
		
		$this->addFiles($svnlist, $svnlog, $project);
		
		return $project;
	}

	// Find files of a given project from the XML data and build a file object
	function addFiles($svnlist, $svnlog, $project) {
		// Find all files under project directory
		$files = $svnlist->xpath("//entry[@kind='file']/name[starts-with(., '$project->title')]/..");

		foreach($files as $fileObject) {
			$file = new File();
			$file->path = (string) $fileObject->name;
			$file->url = "https://subversion.ews.illinois.edu/svn/fa11-cs242/barril1/" . ((string) $fileObject->name);
			$file->size = (string) $fileObject->size;
			$this->addRevisions($svnlog, $file);
				
			$project->fileList[$file->path] = $file;
		}
	}

	// Find revisions of a given file from the XML data and build a revision object
	function addRevisions($svnlog, $file) {
		// Find all revisions of file
		$revisions = $svnlog->xpath("//path[contains(., '$file->path')]/../..");
			
		if ($revisions == NULL)
			return;
			
		foreach($revisions as $revisionObject) {
			$revision = new Revision();
			$revision->number = (string) $revisionObject['revision'];
			$revision->author = (string) $revisionObject->author;
			$revision->date = (string) $revisionObject->date;
			$revision->info = (string) $revisionObject->msg;

			$file->revisionList[$revision->number] = $revision;
		}
	}
		
}

?>