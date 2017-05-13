<?php
require_once CHAPTER_DIR.'/chapter_class.php';

global $chapter;
$video=$_POST["data"]["video"];
Assign('video', $video);

$output=$chapter->generateHTMLChapters($video);
Assign ('storedChapters',$output);

/**
 *	/!\ Important to use Expand Video Manager
 *
 *	Do not display the template, just compute and assign to a variable
 */
$var = $cbtpl->fetch(PLUG_DIR.'/chapters/admin/set_chapters.html');
/**
 *	Display the variable
 */
echo $var;