<?php
/**
 * Description of AxImage
 *
 * @author ardha
 * @created on Oct 24, 2012
 */
class AxImage {

    /**
     * source image
     *
     * @var string|array
     */
    private $source;

    /**
     * temporay image
     *
     * @var file
     */
    private $image;

    /**
     * erros
     *
     * @var array
     */
    private $error;

    /**
     * construct
     *
     * @param string|array $source
     */
    public function __construct($source = NULL) {
        if ($source != NULL) {
            $this->source($source);
        }
    }

    /**
     * set the source image
     *
     * @param string|array $source
     */
    public function source($source) {
        if (!is_array($source)) {
            $this->source["name"] = $source;
            $this->source["tmp_name"] = $source;
            $type = NULL;
            $ext = explode(".", $source);
            $ext = strtolower(end($ext));
            switch ($ext) {
                case "jpg" :
                case "jpeg" : $type = "image/jpeg";
                    break;
                case "gif" : $type = "image/gif";
                    break;
                case "png" : $type = "image/png";
                    break;
            }
            $this->source["type"] = $type;
        } else {
            $this->source = $source;
        }
        $this->destination = $this->source["name"];
    }

    /**
     * resize the image
     *
     * @param int $width
     * @param int $height
     */
    public function resize($width = NULL, $height = NULL) {
        ini_set('max_execution_time', 120);
        if (isset($this->source["tmp_name"]) && file_exists($this->source["tmp_name"])) {
            list($source_width, $source_height) = getimagesize($this->source["tmp_name"]);
            if (($width == NULL) && ($height != NULL)) {
                $width = ($source_width * $height) / $source_height;
            }
            if (($width != NULL) && ($height == NULL)) {
                $height = ($source_height * $width) / $source_width;
            }
            if (($width == NULL) && ($height == NULL)) {
                $width = $source_width;
                $height = $source_height;
            }
            if (($width != NULL) && ($height != NULL)) {
                if ($width > $source_width) {
                    $width = $source_width;
                    $height = ($source_height * $width) / $source_width;
                } else if ($height > $source_height) {
                    $height = $source_height;
                    $width = ($source_width * $height) / $source_height;
                }
                else{
                    if($source_width >=  $source_width){
                        $height = ($source_height * $width) / $source_width;
                    }
                    else{
                        $width = ($source_width * $height) / $source_height;
                    }
                }
            }
            switch ($this->source["type"]) {
                case "image/jpeg" : $created = imagecreatefromjpeg($this->source["tmp_name"]);
                    break;
                case "image/gif" : $created = imagecreatefromgif($this->source["tmp_name"]);
                    break;
                case "image/png" : $created = imagecreatefrompng($this->source["tmp_name"]);
                    break;
            }
            $this->image = imagecreatetruecolor($width, $height);
            imagecopyresampled($this->image, $created, 0, 0, 0, 0, $width, $height, $source_width, $source_height);
            imagedestroy($created);
        }
    }

    static function getImageUrl($img, $type="thumb"){
        $arrImg = explode('.', $img);
        $ext = array_pop($arrImg);
        $retImg = implode('', $arrImg);
        switch($type){
            case "thumb":
                $retImg .= "_T.{$ext}";
                break;
            case "medium":
                $retImg .= "_M.{$ext}";
                break;
            default:
                $retImg .= ".{$ext}";
                break;
        }
        return Yii::app()->baseUrl.$retImg;
    }

    /**
     * crop the image
     *
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     */
    public function crop($x, $y, $width, $height) {
        if (isset($this->source["tmp_name"]) && file_exists($this->source["tmp_name"]) && ($width > 10) && ($height > 10)) {
            switch ($this->source["type"]) {
                case "image/jpeg" : $created = imagecreatefromjpeg($this->source["tmp_name"]);
                    break;
                case "image/gif" : $created = imagecreatefromgif($this->source["tmp_name"]);
                    break;
                case "image/png" : $created = imagecreatefrompng($this->source["tmp_name"]);
                    break;
            }
            $this->image = imagecreatetruecolor($width, $height);
            imagecopy($this->image, $created, 0, 0, $x, $y, $width, $height);
            imagedestroy($created);
        }
    }

    /**
     * create final image file 
     *
     * @param string $destination
     * @param int $quality
     */
    public function create($destination, $quality = 100) {
        if ($this->image != "") {
            $extension = substr($destination, -3, 3);

            switch ($extension) {
                case "gif" :
                    imagegif($this->image, $destination, $quality);
                    break;
                case "png" :
                    $quality = ceil($quality / 10) - 1;
                    imagepng($this->image, $destination, $quality);
                    break;
                default :
                    imagejpeg($this->image, $destination, $quality);
                    break;
            }
            imagedestroy($this->image);
        }
    }

    /**
     * check if extension is valid
     *
     */
    public function validate_extension() {
        if (isset($this->source["tmp_name"]) && file_exists($this->source["tmp_name"])) {
            $exts = array("image/jpeg", "image/gif", "image/png");
            $ext = $this->source["type"];
            $valid = 0;
            foreach ($exts as $current) {
                if ($current == $ext) {
                    $valid = 1;
                }
            }
            if ($valid != 1) {
                $this->error .= "extension";
            }
        } else {
            $this->error .= "source";
        }
    }

    /**
     * check if the size is correct
     *
     * @param int $max
     */
    public function validate_size($max) {
        if (isset($this->source["tmp_name"]) && file_exists($this->source["tmp_name"])) {
            $max = $max * 1024;
            if ($this->source["size"] >= $max) {
                $this->error .= "size";
            }
        } else {
            $this->error .= "source";
        }
    }

    /**
     * check if the dimension is correct
     *
     * @param int $limit_width
     * @param int $limit_height
     */
    public function validate_dimension($limit_width, $limit_height) {
        if (isset($this->source["tmp_name"]) && file_exists($this->source["tmp_name"])) {
            list($source_width, $source_height) = getimagesize($this->source["tmp_name"]);
            if (($source_width > $limit_width) || ($source_height > $limit_height)) {
                $this->error .= "dimension";
            }
        } else {
            $this->error .= "source";
        }
    }

    /**
     * get the found errors
     *
     */
    public function error() {
        $error = NULL;
        if (stristr($this->error, "source"))
            $error[] = "no selected file";
        if (stristr($this->error, "dimension"))
            $error[] = "dimensions too large";
        if (stristr($this->error, "extension"))
            $error[] = "invalid extension";
        if (stristr($this->error, "size"))
            $error[] = "size too large";
        return $error;
    }

}