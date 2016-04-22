<?php
class files
{
    function get_file_list($directory, $type = "img_creation", $print_list = false)
    {
        // Checks the dir
        if(!is_dir($directory))
        {
            $this->_error("Invalid Directory: " . $diretorio, E_USER_ERROR);
        }
        
        // File types regex
        Switch($type)
        {
            Case "img_creation":
                $types_regex = "jpeg|jpg|png";
            break;

            Case "img":
                $types_regex = "gif|jpeg|jpg|png|bmp";
            break;

            Case "pag":
                $types_regex = "txt|htm|html|php|asp|aspx";
            break;

            Case "vid":
                $types_regex = "avi|swf|mpg|mpeg|wmv|asx|mov";
            break;

            Case "doc":
                $types_regex = "txt|doc|rtf|xsl";
            break;

            Default:
                $types_regex = false;
        }
        
        // Open dir handle
        if(!$dir_handle = @opendir($directory))
        {
            $this->_error("I couldn't open the dir: " . $directory, E_USER_ERROR);
        }
        
        // Initilization of the list array
        $file_list = array();
        
        // Starts dir navigation
        while (false !== ($file = @readdir($dir_handle)))
        { 
            if ($file == "." || $file == "..")
            { 
                continue;
            }
            
            // The list will be generate with specific types, according to the regex
            if($types_regex)
            {
                if(eregi( "\.(" . $types_regex . ")$", $file))
                {
                    $file_list[] = $file;
                }
            }

            // The list will be generate with all dir's files
            else
            {
                // Add only files to the list
                if(is_file($directory . $file))
                {
                    $file_list[] = $file;
                }
            }
        }
        
        // Close dir handle
        @closedir($dir_handle);
        
        // Has no files in the dir
        if(!sizeof($file_list))
        {
            $this->_error("The directory: " . $directory . " is empty!", E_USER_NOTICE);
        }

        // If debugging...
        if($print_list)
        {
            echo "<pre>";
            print_r($file_list);
            echo "</pre>";
        }
        
        // Returns file list
        return $file_list;
    }

	function _error($mensagem, $tipo)
	{
        if($tipo == E_USER_ERROR)
        {
            $topo = "Error!";
        }
        else
        {
            $topo = "Notification";
        }

        echo "<span style=\"background-color: #FFD7D7\"><font face=verdana size=2><font color=red><b>" . $topo . "</b></font>: " . $mensagem . "</font></span><br><br>";

        if($tipo == E_USER_ERROR)
        {
            exit;
        }
	}
}
?>