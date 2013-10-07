<?php
/**
 **************************************************************************************************
  Name : Calculate Date
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
**/

class CalcDate
{
    function age($age){
    list($year,$day,$month) = explode("-",$age);
    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;
    if ($day_diff < 0 || $month_diff < 0)
      $year_diff--;
    return $year_diff;
  }
  
  function DateFormat($date,$format){
	$date = strtotime($date);
	$newdate = date($format,$date);
	return $newdate;
	}
}
?>