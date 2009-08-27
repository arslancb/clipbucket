<?php
/*
Simple FLV Player Plugin
@ Authoer :  Arslan
*/

class FlvPlayerObj{

	//Function Used To Get Player Skins
	function GetPlayerSkins($playerfile){
		$query = mysql_query("SELECT * FROM player_skins WHERE player_file='".$playerfile."'");
		$details = mysql_fetch_array($query);
		return $details;
	}

	//Function Used To Get Player Configs
	function GetPlayerConfig($player_file=NULL){
		if($player_file)
			$player_query = " WHERE player_file ='".$player_file."' ";
			
		$query = mysql_query("SELECT * FROM player_configs $player_query ");
			while($row = mysql_fetch_array($query)){
				$name = $row['player_config_name'];
				$data[$name] = $row['player_config_value'];
			}
		return $data;
	}

	//FUNCTION USED TO UPLOAD PLAYER CONFIGS
	function SetPlayerConfig($name,$value){
	mysql_query("UPDATE player_configs SET player_config_value = '".$value."' WHERE player_config_name ='".$name."'");
	}
}

$FlvPlayerObj = new FlvPlayerObj();

//Getting Plugin Config Details
$query = mysql_query("SELECT * FROM players WHERE player_type='plugin'");
if(!$AdminArea){
 if($query)
   {
	while($data = mysql_fetch_array($query)){
		if(!empty($data['player_include_file']))
		include(PLUG_DIR.'/flv_players/'.$data['player_include_file']);
	}
   }
}else{
   if($query)
   {
	while($data = mysql_fetch_array($query)){
	$player_list[] = $data;
	}
	Assign('total_installed_players',mysql_num_rows($query));
	Assign('player_list',$player_list);
	Assign('player_config',$FlvPlayerObj->GetPlayerConfig());
   }
}

?>