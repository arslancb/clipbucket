<?php

/**
 * @Author : Arslan Hassan
 */
include('../includes/config.inc.php');



if (isset($_REQUEST['upload']))
    $mode = "upload";

if ($_POST['insertVideo'])
    $mode = "insert_video";
if ($_POST['getForm'])
    $mode = "get_form";
if ($_POST['updateVideo'] == 'yes')
    $mode = "update_video";

switch ($mode) {

    case "insert_video": {
            $title = getName($_POST['title']);
            $file_name = time().  RandomString(5);
            
            $file_directory = createDataFolders();
            
            $vidDetails = array
                (
                'title' => $title,
                'description' => $title,
                'tags' => genTags(str_replace(' ', ', ', $title)),
                'category' => array($cbvid->get_default_cid()),
                'file_name' => $file_name,
                'file_directory' => $file_directory,
                'userid' => userid(),
            );

            $vid = $Upload->submit_upload($vidDetails);

            echo json_encode(array('success' => 'yes', 
                'vid' => $vid,'file_directory'=>$file_directory,
                'file_name'=>$file_name));
        }
        break;

    case "get_form": {
            $title = getName($_POST['title']);
            if (!$title)
                $title = $_POST['title'];
            $desc = $_POST['desc'];
            $tags = $_POST['tags'];

            if (!$desc)
                $desc = $title;
            if (!$tags)
                $tags = $title;

            $vidDetails = array
                (
                'title' => $title,
                'description' => $desc,
                'tags' => $tags,
                'category' => array($cbvid->get_default_cid()),
            );

            assign("objId", $_POST['objId']);

            assign('input', $vidDetails);
            Template('blocks/upload/form.html');
        }
        break;

    case "upload": {
            
           

           

            // HTTP headers for no cache etc
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");

            pr($_REQUEST);
            $targetDir = TEMP_DIR;

            $cleanupTargetDir = true; // Remove old files
            $maxFileAge = 5 * 3600; // Temp file age in seconds
            @set_time_limit(5 * 60);

            $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
            $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
            $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

            // Clean the fileName for security reasons
            $fileName = preg_replace('/[^\w\._]+/', '_', $fileName);

            // Make sure the fileName is unique but only if chunking is disabled
            if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
                $ext = strrpos($fileName, '.');
                $fileName_a = substr($fileName, 0, $ext);
                $fileName_b = substr($fileName, $ext);

                $count = 1;
                while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                    $count++;

                $fileName = $fileName_a . '_' . $count . $fileName_b;
            }

            $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

            // Create target dir
            if (!file_exists($targetDir))
                @mkdir($targetDir);

            // Remove old temp files	
            if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
                while (($file = readdir($dir)) !== false) {
                    $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                    // Remove temp file if it is older than the max age and is not the current file
                    if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
                        @unlink($tmpfilePath);
                    }
                }

                closedir($dir);
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');


            // Look for the content type header
            if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
                $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

            if (isset($_SERVER["CONTENT_TYPE"]))
                $contentType = $_SERVER["CONTENT_TYPE"];

            // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
            if (strpos($contentType, "multipart") !== false) {
                if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                    // Open temp file
                    $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
                    if ($out) {
                        // Read binary input stream and append it to temp file
                        $in = fopen($_FILES['file']['tmp_name'], "rb");

                        if ($in) {
                            while ($buff = fread($in, 4096))
                                fwrite($out, $buff);
                        } else
                            die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                        fclose($in);
                        fclose($out);
                        @unlink($_FILES['file']['tmp_name']);
                    } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            } else {
                // Open temp file
                $out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = fopen("php://input", "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

                    fclose($in);
                    fclose($out);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            // Check if file has been uploaded
            if (!$chunks || $chunk == $chunks - 1) {
                // Strip the temp .part suffix off 
                rename("{$filePath}.part", $filePath);
            }


            // Return JSON-RPC response
            //die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');

            //move_uploaded_file($tempFile, $targetFile);
            $file_name = $_REQUEST['file_name'];
            $targetFileName = $file_name . '.' . getExt($filePath);
            $targetFile = TEMP_DIR . "/" . $targetFileName;
            
            rename($filePath,$targetFile);
            
            $fileDir = $_REQUEST['file_directory'];
            $Upload->add_conversion_queue($targetFileName,$fileDir);

            /* //exec(php_path()." -q ".BASEDIR."/actions/video_convert.php &> /dev/null &");
              if (stristr(PHP_OS, 'WIN')) {
              exec(php_path()." -q ".BASEDIR."/actions/video_convert.php $targetFileName");
              } else {
              exec(php_path()." -q ".BASEDIR."/actions/video_convert.php $targetFileName &> /dev/null &");
              }
             */
            echo json_encode(array("success" => "yes", "file_name" => $file_name));
        }
        break;

    case "update_video": {
            $Upload->validate_video_upload_form();
            $_POST['videoid'] = trim($_POST['videoid']);
            if (empty($eh->error_list)) {
                $cbvid->update_video();
            }
            if (error())
                echo json_encode(array('error' => error('single')));
            else
                echo json_encode(array('msg' => msg('single')));
        }
}


?>