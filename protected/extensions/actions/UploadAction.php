<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class UploadAction extends CAction {

    protected $_fileName;
    public $targetDir;
    public $cleanupTargetDir = true; // Remove old files
    public $maxFileAge = 18000; // Temp file age in seconds
    public $timeLimit = 300; // Time limit in seconds

    //put your code here

    public function run() {
        // HTTP headers for no cache etc
        $this->headers();

        // Settings
        if (!isset($this->targetDir)) {
            $this->targetDir = self::defaultPath();
        }
        //execution time 
        @set_time_limit($this->timeLimit);

        $this->checkUniqueFile();
        $filePath = $this->targetDir . DIRECTORY_SEPARATOR . $this->fileName;

        // Create target dir
        if (!file_exists($this->targetDir))
            @mkdir($this->targetDir);

        $this->removeOldTempFiles($filePath);
        $this->handleMultipart($filePath);

        // Check if file has been uploaded
        if (!$this->chunks || $this->chunk == $this->chunks - 1) {
            // Strip the temp .part suffix off 
            rename("{$filePath}.part", $filePath);
        }
        // Return JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }

    protected function headers() {
        // HTTP headers for no cache etc
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

    protected function checkUniqueFile() {
        // Make sure the fileName is unique but only if chunking is disabled
        if ($this->chunks < 2 && file_exists($this->targetDir . DIRECTORY_SEPARATOR . $this->fileName)) {
            $ext = strrpos($this->fileName, '.');
            $fileName_a = substr($this->fileName, 0, $ext);
            $fileName_b = substr($this->fileName, $ext);

            $count = 1;
            while (file_exists($this->targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                $count++;

            $this->fileName = $fileName_a . '_' . $count . $fileName_b;
        }
    }

    protected function removeOldTempFiles($filePath) {
        // Remove old temp files	
        if ($this->cleanupTargetDir && is_dir($this->targetDir) && ($dir = opendir($this->targetDir))) {
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $this->targetDir . DIRECTORY_SEPARATOR . $file;

                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $this->maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
                    @unlink($tmpfilePath);
                }
            }

            closedir($dir);
        } else
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
    }

    protected function handleMultipart($filePath) {
        // Look for the content type header
        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file
                $out = fopen("{$filePath}.part", $this->chunk == 0 ? "wb" : "ab");
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
            $out = fopen("{$filePath}.part", $this->chunk == 0 ? "wb" : "ab");
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
    }

    public static function defaultPath() {
        return Yii::getPathOfAlias('webroot') . Yii::app()->params['dirFotoTemp'];
    }

    public function getChunk() {
        return isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
    }

    public function getChunks() {
        return isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
    }

    public function getFileName() {
        $this->_fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';
        $this->_fileName = preg_replace('/[^\w\._]+/', '_', $this->_fileName);
        return $this->_fileName;
    }

    public function setFileName($val) {
        $this->_fileName = $val;
    }

}

?>
