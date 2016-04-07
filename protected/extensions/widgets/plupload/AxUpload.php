<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AxUpload extends CWidget {
    const DEFAULT_RUNTIMES = 'html5,gears,flash,silverlight,browserplus';

    public $validateBeforeSubmit = true;
    public $htmlOptions = array();
    public $options = array();
    public $browserPlus = 'browsePlus.js';
    public $fullJs = 'plupload.full.js';
    public $queue = 'jquery.plupload.queue/jquery.plupload.queue.js';
    public $queueCss = 'jquery.plupload.queue/css/jquery.plupload.queue.css';
    public $flash = 'plupload.flash.swf';
    public $silverlight = 'plupload.silverlight.xap';
    public $maxFilesUpload = 10;

    public function init() {
        $this->initiateScript();
        $this->registerScript();
    }

    public function initiateScript() {
        $path = CHtml::asset(__DIR__ . DIRECTORY_SEPARATOR . 'assets');
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');

        $cs->registerScriptFile($path . '/js/' . $this->browserPlus);
        if ($this->fullJs)
            $cs->registerScriptFile($path . '/js/' . $this->fullJs);
        if ($this->queue)
            $cs->registerScriptFile($path . '/js/' . $this->queue);
        if ($this->queueCss)
            $cs->registerCssFile($path . '/js/' . $this->queueCss);

        if (!isset($this->options['flash_swf_url'])) {
            $flashUrl = $path . '/js/' . $this->flash;
            $this->options['flash_swf_url'] = $flashUrl;
        }
        if (!isset($this->options['silverlight_xap_url'])) {
            $silverLightUrl = $path . '/js/' . $this->silverlight;
            $this->options['silverlight_xap_url'] = $silverLightUrl;
        }
        if (!isset($this->options['runtimes'])) {
            $this->options['runtimes'] = self::DEFAULT_RUNTIMES;
        }
        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->id;
        }else
            $this->id = $this->htmlOptions['id'];
        if (isset($this->maxFilesUpload)) {
            if (!isset($this->options['init'])) {
                $this->options['init'] = array();
            }
            if (!isset($this->options['init']['FilesAdded'])) {
                $this->options['init']['FilesAdded'] = $this->filesAdded;
            }
        }
    }

    public function getFilesAdded() {
        return 'js:function(up, files) {
                // Check if the size of the queue is bigger than queueMaxima 
                if(up.files.length > ' . $this->maxFilesUpload . '){
                    // Removing the extra files
                    while(up.files.length > ' . $this->maxFilesUpload . ')
                        if(up.files.length > ' . $this->maxFilesUpload . ')
                            up.removeFile(up.files[' . $this->maxFilesUpload . ']);
                    
                    alert(\"Max ' . $this->maxFilesUpload . ' files.\");
                }
            }';
    }

    public function registerScript() {
        $cs = Yii::app()->clientScript;

        $jsConfig = CJavaScript::encode($this->options);
        $jqueryScript = "jQuery('#{$this->htmlOptions['id']}').pluploadQueue({$jsConfig});";

        $jqueryScript .= $this->validateSubmit();

        $uniqueId = 'Yii.' . __CLASS__ . '#' . $this->id;
        $cs->registerScript($uniqueId, stripcslashes($jqueryScript), CClientScript::POS_READY);
    }

    public function validatesubmit() {
        if ($this->validateBeforeSubmit) {
            $jqueryScript = "
            var {$this->htmlOptions['id']} = $('#{$this->htmlOptions['id']}').pluploadQueue();
            var form = $('#{$this->htmlOptions['id']}').parents('form');
            var button = null;     
            $(form).find('input[type=submit]').click(function(){
                button = $(this);
            });
            $(form).submit(function(e) {
                if ({$this->htmlOptions['id']}.files.length > 0 && {$this->htmlOptions['id']}.files.length > ({$this->htmlOptions['id']}.total.uploaded + {$this->htmlOptions['id']}.total.failed)) {
                    {$this->htmlOptions['id']}.bind('StateChanged', function() {
                        if ({$this->htmlOptions['id']}.files.length === ({$this->htmlOptions['id']}.total.uploaded + {$this->htmlOptions['id']}.total.failed)) {
                            $(button).click();
                        }
                    });
                    {$this->htmlOptions['id']}.start();
                } else {
                    return true;
                }
                return false;
            })";
            return $jqueryScript;
        }
        return "";
    }

    public function run() {
        //if ($this->maxFilesUpload > 0)
        echo CHtml::tag('div', $this->htmlOptions, '');
    }

}

?>
