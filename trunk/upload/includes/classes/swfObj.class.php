<?php
/*
This Class Is Used To Create Player Code
@ Author : ArslanHassan
@ License : CBLA
@ Created : Nov 10 2008
@ version : 1.0
*/

class SWFObject
{

//var Code 
var $objName = 'FlashObject';
var $DivId = 'videoplayer';
var $code = '';
var $playerFile = '';
var $width = '450';
var $height = '378';
var $PlayerVer = '7';
var $bgcolor = '#FFFFFF';
var $playerVar = 'player';

	//Function Used TO Create Player
	function CreatePlayer(){
		$this->SelectPlayer();
		$this->WritePlayer();
		$this->code = $this->ClearTabs($this->code);
	}
	
	
	//Function Used To Construct Player OBJ
	function FlashObj(){
		$this->code = "var ".$this->playerVar." = new ".$this->objName."(\"";
		//$this->code .= BASEURL;
		$this->code .= $this->playerFile.'"';
		$this->code .= ',"base","'.$this->width.'","'.$this->height.'","'.$this->PlayerVer.'","'.$this->bgcolor.'");';
	}
	
	//Function Used To Check Which Player Has Been Selected
	function SelectPlayer()
	{
		global $row;
		
		//Player
		if(empty($this->playerFile))
		$this->playerFile = $row['player_file'];
	}
	
	
	
	//Function Used To Add Param
	function addParam($name,$value,$remove_quotes = false){
		if($remove_quotes == false){
		$this->code .= "
		".$this->playerVar.".addParam('$name', '$value');";
		}else{
		$this->code .= "
		".$this->playerVar.".addParam('$name', $value);";
		}
	}
	
	//Function Used To Add Variables
	function addVar($name,$value,$remove_quotes = false){
		if($remove_quotes == false){
		$this->code .= "
		".$this->playerVar.".addVariable('$name', '$value');";
		}else{
		$this->code .= "
		".$this->playerVar.".addVariable('$name', $value);";
		}
	}
	
	//Function Write Player
	function WritePlayer()
	{
	$this->code .= "
	".$this->playerVar.'.write("'.$this->DivId.'");';
	}
	
	//function used to remove tabs
	function ClearTabs($text,$str=false){
		if($str==false){
			return preg_replace('/\t/','',$text);
		}else{
			return str_replace('/\t/','',$text);
		}
	}
	
	//function for Embed Code
	function EmbedCode($code){
		$this->code = 'var EmbedCode="'.addslashes($code).'";'."\r\n";
		$this->code .= "innerHtmlDiv('".$this->DivId."',EmbedCode)";
	}
	
}
?>