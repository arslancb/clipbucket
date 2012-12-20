<?php


if(!function_exists('mob_description'))
{
    function mob_description($description)
    {
            global $Cbucket;

            $description = str_replace('ÿþ', '', $description);
            $description = str_replace('?','MY_QUE_MARK',$description);
            $description = utf8_decode($description);
            $description = str_replace('?','',$description);
            $description = str_replace('MY_QUE_MARK','?',$description);

            return $description;
    }
}

?>