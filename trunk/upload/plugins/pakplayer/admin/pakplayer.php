<?php


//Player Control settings
$pakconfigs = array
(
'bufferColor'			=> array("default"=>"#75dc18","type" => "textfield" , 'title' => 'Buffer Color','isColor'=>true),
'backgroundGradient'	=> array("default"=>"high","type" => "textfield" , 'title' => 'Background Gradient',),
'backgroundColor'		=> array("default"=>"#222222","type" => "textfield" , 'title' => 'Background Color','isColor'=>true),
'timeBgColor'			=> array("default"=>"#222222","type" => "textfield" , 'title' => 'Time bgcolor','isColor'=>true),
'autoHide'				=> array("default"=>"never","type" => "textfield" , 'title' => 'Auto hide'),
'volumeColor'			=> array("default"=>"#18dc73","type" => "textfield" , 'title' => 'Volume color','isColor'=>true),
'borderRadius'			=> array("default"=>"10","type" => "textfield" , 'title' => 'Border radius'),
'sliderColor'			=> array("default"=>"#e3bad0","type" => "textfield" , 'title' => 'Slider color','isColor'=>true),
'tooltipColor'			=> array("default"=>"#000000","type" => "textfield" , 'title' => 'Tooltip Color','isColor'=>true),
'timeColor'				=> array("default"=>"#ffffff","type" => "textfield" , 'title' => 'Time color','isColor'=>true),
'buttonOverColor'		=> array("default"=>"#e3bcba","type" => "textfield" , 'title' => 'Button over color','isColor'=>true),
'timeBorder'			=> array("default"=>"0px solid rgba(0, 0, 0, 0.3)","type" => "textfield" , 'title' => 'Time border','isGradient'=>true),
'progressColor'			=> array("default"=>"#babfe3","type" => "textfield" , 'title' => 'Progress color','isColor'=>true),
'buttonColor'			=> array("default"=>"#f6f0c6","type" => "textfield" , 'title' => 'Button color','isColor'=>true),
'sliderBorder'			=> array("default"=>"1px solid rgba(128, 128, 128, 0.7)","type" => "textfield" , 'title' => 'Slider Border','isGradient'=>true),
'timeSeparator'			=> array("default"=>" ","type" => "textfield" , 'title' => 'Time separator'),
'tooltipTextColor'		=> array("default"=>"#ffffff","type" => "textfield" , 'title' => 'Tooltip text color','isColor'=>true),
'sliderGradient'		=> array("default"=>"none","type" => "textfield" , 'title' => 'Slider Gradient'),
'bufferGradient'		=> array("default"=>"none","type" => "textfield" , 'title' => 'Buffer Gradient'),
'volumeBorder'			=> array("default"=>"1px solid rgba(128, 128, 128, 0.7)","type" => "textfield", 'title' => 'Volume Border' ,'isGradient'=>true),
'progressGradient'		=> array("default"=>"none","type" => "textfield" , 'title' => 'Progress Gradient'),
'volumeSliderGradient'	=> array("default"=>"none","type" => "textfield" , 'title' => 'Volume Slider Gradient'),
'volumeSliderColor'		=> array("default"=>"#f00023","type" => "textfield" , 'title' => 'Volume Slider Color','isColor'=>true),
'durationColor'			=> array("default"=>"#bae3d0","type" => "textfield" , 'title' => 'Duration Color','isColor'=>true),
);

assign('pakconfigs',$pakconfigs);
	  
	  
	  
if(isset($_POST['update']))
{
	$license = $_POST['pak_license'];
	$db->update(tbl("config"),array("value"),array($license)," name='pak_license'");
	e("Player license has been updated","m");
}
assign("configs",$Cbucket->get_configs());


$Cbucket->add_admin_header(PLUG_DIR.'/pakplayer/header.html');

template_files('pakplayer.html',PAKPLAYER_PLUG_DIR.'/admin/');

?>