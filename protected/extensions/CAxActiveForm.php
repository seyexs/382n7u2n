<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class CAxActiveForm extends CActiveForm {
    private $jsCurrencyFile = 'jquery.formatCurrency-1.4.0.min.js';
    public function labelEx($model, $attribute, $htmlOptions = array()) {
        $label = $model->getAttributeLabel($attribute) . ':';
        if (!isset($htmlOptions['label'])) {
            $htmlOptions['label'] = $label;
        }
        return parent::labelEx($model, $attribute, $htmlOptions);
    }

    public function checkBoxList($model, $attribute, $data, $htmlOptions=array()) {
        return CAxHtml::activeCheckBoxList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders a radio button list for a model attribute.
     * This method is a wrapper of {@link CHtml::activeRadioButtonList}.
     * Please check {@link CHtml::activeRadioButtonList} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data value-label pairs used to generate the radio button list.
     * @param array $htmlOptions addtional HTML options.
     * @return string the generated radio button list
     */
    public function radioButtonList($model, $attribute, $data, $htmlOptions=array()) {
        return CAxHtml::activeRadioButtonList($model, $attribute, $data, $htmlOptions);
    }
    public function currencyField($model, $attribute, $htmlOptions=array()){
        CHtml::resolveNameID($model, $attribute, $htmlOptions);
        $this->regScriptCurrency($htmlOptions['id']);
        return CHtml::activeNumberField($model,$attribute,$htmlOptions);
        
    }
    private function regScriptCurrency($fieldId){
        $assets = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'activeform/js';
        $baseUrl = Yii::app()->getAssetManager()->publish($assets);

        Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerScriptFile($baseUrl . '/' . $this->jsCurrencyFile, CClientScript::POS_HEAD);
        $js = <<<EOS
$('#{$fieldId}').blur(function() {
    $('#{$fieldId}').html(null);
    $(this).formatCurrency({
        colorize: true, 
        negativeFormat: '-%s%n', 
        roundToDecimalPlace: -1,
        symbol: ''
    });
})
.keyup(function(e) {
    var e = window.event || e;
    var keyUnicode = e.charCode || e.keyCode;
    if (e !== undefined) {
        switch (keyUnicode) {
            case 16:
                break; // Shift
            case 17:
                break; // Ctrl
            case 18:
                break; // Alt
            case 27:
                this.value = '';
                break; // Esc: clear entry
            case 35:
                break; // End
            case 36:
                break; // Home
            case 37:
                break; // cursor left
            case 38:
                break; // cursor up
            case 39:
                break; // cursor right
            case 40:
                break; // cursor down
            //case 78:
            //    break; // N (Opera 9.63+ maps the "." from the number key section to the "N" key too!) (See: http://unixpapa.com/js/key.html search for ". Del")
            //case 110:
            //    break; // . number block (Opera 9.63+ maps the "." from the number block to the "N" key (78) !!!)
            case 190:
                break; // .
            default:
                $(this).formatCurrency({
                colorize: true, 
                negativeFormat: '-%s%n', 
                roundToDecimalPlace: -1, 
                eventOnDecimalsEntered: true,
                symbol: ''
    
            });
        }
    }
})
.keydown(function(event) {  
        // Allow: backspace, delete, tab and escape
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 
            // Allow: Ctrl+A
        (event.keyCode == 65 && event.ctrlKey === true) || 
            // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if ( event.shiftKey|| (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 ) ) 
            {
                event.preventDefault(); 
            }
        }
    })    
.bind('decimalsEntered', function(e, cents) {
    if (String(cents).length > 2) {
        var errorMsg = 'Please do not enter any cents (0.' + cents + ')';
        $('#{$fieldId}').html(errorMsg);
        log('Event on decimals entered: ' + errorMsg);
    }
});       
EOS;
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $this->id, $js, CClientScript::POS_READY);
    }
    

}

?>