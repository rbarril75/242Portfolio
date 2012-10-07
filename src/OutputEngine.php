<?php

require_once 'SVNClasses.php';

class OutputEngine {

	// Build a DOMDocument given a list of projects.
	// Each project has a set of files; Each file has a set of revisions;
	function outputXML($projectList) {
		$dom = new DOMDocument('1.0');
		$dom->formatOutput = true;
		//header("Content-Type: text/plain");
		
		$root = $dom->createElement('SVNDump');
		$dom->appendChild($root);

		foreach ($projectList as &$project) {
			$this->addProjectNode($dom, $root, $project);
		}
		
		$xsl = new DOMDocument('1.0');
		$xsl->load("cs242_portfolio.xsl");
		$proc = new XsltProcessor();
		$xsl = $proc->importStylesheet($xsl);
		$newdom = $proc->transformToDoc($dom);
		echo $newdom->saveHTML();
		
		//echo $dom->saveXML();	
	}

	// Add a project node to the root of the DOM
	function addProjectNode(&$dom, &$root, $project) {
		$newProject = $dom->createElement('project');
		$root->appendChild($newProject);

		$title = $dom->createElement('title', $project->title);
		$newProject->appendChild($title);

		$author = $dom->createElement('author', $project->author);
		$newProject->appendChild($author);

		$date = $dom->createElement('date', $project->date);
		$newProject->appendChild($date);
		
		$version = $dom->createElement('version', $project->version);
		$newProject->appendChild($version);
		
		/* Hide revision information for now
		$revision = $dom->createElement('revision', $project->revision);
		$newProject->appendChild($revision);
		*/
		
		$msg = $dom->createElement('msg', $project->msg);
		$newProject->appendChild($msg);

		$files = $dom->createElement('files');
		$newProject->appendChild($files);

		foreach($project->fileList as &$fileObject) {
			$this->addFileNode($dom, $files, $fileObject);
		}
	}

	// Add a file node to the 'files' child of a project node
	function addFileNode(&$dom, &$files, $fileObject) {
		$file = $dom->createElement('file');
		$files->appendChild($file);

		$path = $dom->createElement('path', $fileObject->path);
		$file->appendChild($path);
		
		$url = $dom->createElement('url', $fileObject->url);
		$file->appendChild($url);

		$size = $dom->createElement('size', $fileObject->size);
		$file->appendChild($size);

		$revisions = $dom->createElement('revisions');
		$file->appendChild($revisions);

		foreach($fileObject->revisionList as &$revisionObject) {
			$this->addVersionNode($dom, $revisions, $revisionObject);
		}
	}

	// Add a revision node to the 'revisions' child of a file node
	function addVersionNode(&$dom, &$revisions, $revisionObject) {
		$revision = $dom->createElement('revision');
		$revisions->appendChild($revision);

		$revisionNumber = $dom->createElement('number', $revisionObject->number);
		$revision->appendChild($revisionNumber);

		$revisionAuthor = $dom->createElement('author', $revisionObject->author);
		$revision->appendChild($revisionAuthor);

		$revisionDate = $dom->createElement('date', $revisionObject->date);
		$revision->appendChild($revisionDate);

		$revisionInfo = $dom->createElement('info', $revisionObject->info);
		$revision->appendChild($revisionInfo);
	}
}

?>