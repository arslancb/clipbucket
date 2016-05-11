<?php 
/**
* SLog - For simple logging
* Created by : Sajjad Ashraf
*/
class SLog{

	private $logFile = false;
	private $fileHandle = false;
	private $logData = "";
	
	public function __construct($logFile = false){
		if($logFile){
			$this->logFile = $logFile;
		}
	}

	public function newSection($sectionName = false){
		if(!$sectionName) $sectionName = "New Section";
		$this->logData .= "\n\r==========================================\n\r";
		$this->logData .= "\t" . $sectionName;
		$this->logData .= "\n\r==========================================\n\r";
	}

	public function writeLine($title = false, $description = false, $writeNow = true, $append=false){
		if($title && $description){
			if(is_array($description)) $description = json_encode($description);
			$underline = "";
			$loop = strlen($title);
			for ($i = 0; $i < $loop; $i++) {
				$underline .= "-";
			}
			$underline .= "\n";
			$this->logData .= "\n{$title}\n{$underline}\t\t{$description}\n";
			if (!$append){
				if($writeNow) $this->writeLog();	
			}else{
				if($writeNow) $this->appendLog();
			}
			
		}
	}

	public function writeLog(){
		if(!$this->logFile) return;
		$this->fileHandle = fopen($this->logFile, "w+") or die("Unable to open file!");
		fwrite($this->fileHandle, $this->logData);
		fclose($this->fileHandle);
		return $this;
	}

	public function appendLog(){
		if(!$this->logFile) return;
		$TempData = file_get_contents($this->logFile);
		$this->logData = "\n{$TempData}\n{$this->logData}";
		file_put_contents($this->logFile, $this->logData);
		return $this;
	}

	public function setLogFile($logFile = false){
		if($logFile) $this->logFile = $logFile;
	}

	public function clean(){
		$this->logData = "";
	}
}
