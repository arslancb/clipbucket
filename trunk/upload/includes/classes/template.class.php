<?php

class DoTemplate {

   function DoTemplate() {
        global $Smarty;
        if (!isset($Smarty)) {
            $Smarty = new Smarty;
        }
    }

    function create() {
        global $Smarty;
        $Smarty = new Smarty();
        $Smarty->compile_check = true;
        $Smarty->debugging = false;
        $Smarty->template_dir = BASEDIR."/styles";
        $Smarty->compile_dir  = BASEDIR."/cache";

        return true;
    }
    
    function setCompileDir($dir_name) {
        global $Smarty;
        if (!isset($Smarty)) {
            DoTemplate::create();
        }
        $Smarty->compile_dir = $dir_name;
    }

    function setType($type) {
        global $Smarty;
        if (!isset($Smarty)) {
            DoTemplate::create();
        }
        $Smarty->type = $type;
    }

    function assign($var, $value) {
        global $Smarty;
        if (!isset($Smarty)) {
            DoTemplate::create();
        }
        $Smarty->assign($var, $value);
    }

    function setTplDir($dir_name = null) {
        global $Smarty;
        if (!isset($Smarty)) {
            DoTemplate::create();
        }
        if (!$dir_name) {
            $Smarty->template_dir = BASEDIR."/styles/clipbucketblue";
        } else {
            $Smarty->template_dir = $dir_name;
        }
    }

    function setModule($module) {
        global $Smarty;
        if (!isset($Smarty)) {
            DoTemplate::create();
        }
        $Smarty->theme = $module;
        $Smarty->type  = "module";
    }

    function setTheme($theme) {
        global $Smarty;
        if (!isset($Smarty)) {
            DoTemplate::create();
        }
        $Smarty->template_dir = BASEDIR."/styles/" . $theme;
        $Smarty->compile_dir  = BASEDIR."/styles/" . $theme;
        $Smarty->theme        = $theme;
        $Smarty->type         = "theme";
    }

    function getTplDir() {
        global $Smarty;
        if (!isset($Smarty)) {
            DoTemplate::create();
        }
        return $Smarty->template_dir;
    }

    function display($filename) {
        global $Smarty;
        if (!isset($Smarty)) {
            DoTemplate::create();
        }
        $Smarty->display($filename);
    }

    function fetch($filename) {
        global $Smarty;
        if (!isset($Smarty)) {
            DoTemplate::create();
        }
        return $Smarty->fetch($filename);
    }
    
    function getVars() {
        global $Smarty;
        if (!isset($Smarty)) {
            DoTemplate::create();
        }
        return $Smarty->get_template_vars();
    }
}
?>