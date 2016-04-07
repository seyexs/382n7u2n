<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CAxHtml
 *
 * @author ardha
 * @created on Nov 7, 2012
 */
class CAxHtml extends CHtml {

    /**
     * Generates a check box list.
     * A check box list allows multiple selection, like {@link listBox}.
     * As a result, the corresponding POST value is an array.
     * @param string $name name of the check box list. You can use this name to retrieve
     * the selected value(s) once the form is submitted.
     * @param mixed $select selection of the check boxes. This can be either a string
     * for single selection or an array for multiple selections.
     * @param array $data value-label pairs used to generate the check box list.
     * Note, the values will be automatically HTML-encoded, while the labels will not.
     * @param array $htmlOptions addtional HTML options. The options will be applied to
     * each checkbox input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each checkbox is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * check box input tag while "{label}" be replaced by the corresponding check box label.</li>
     * <li>separator: string, specifies the string that separates the generated check boxes.</li>
     * <li>checkAll: string, specifies the label for the "check all" checkbox.
     * If this option is specified, a 'check all' checkbox will be displayed. Clicking on
     * this checkbox will cause all checkboxes checked or unchecked.</li>
     * <li>checkAllLast: boolean, specifies whether the 'check all' checkbox should be
     * displayed at the end of the checkbox list. If this option is not set (default)
     * or is false, the 'check all' checkbox will be displayed at the beginning of
     * the checkbox list.</li>
     * <li>labelOptions: array, specifies the additional HTML attributes to be rendered
     * for every label tag in the list.</li>
     * <li>container: string, specifies the checkboxes enclosing tag. Defaults to 'span'.
     * If the value is an empty string, no enclosing tag will be generated</li>
     * </ul>
     * @return string the generated check box list
     */
    public static function checkBoxList($name, $select, $data, $htmlOptions=array()) {
        $template = isset($htmlOptions['template']) ? $htmlOptions['template'] : '{input} {label}';
        $separator = isset($htmlOptions['separator']) ? $htmlOptions['separator'] : "<br/>\n";
        $container = isset($htmlOptions['container']) ? $htmlOptions['container'] : 'span';
        unset($htmlOptions['template'], $htmlOptions['separator'], $htmlOptions['container']);

        if (substr($name, -2) !== '[]')
            $name.='[]';

        if (isset($htmlOptions['checkAll'])) {
            $checkAllLabel = $htmlOptions['checkAll'];
            $checkAllLast = isset($htmlOptions['checkAllLast']) && $htmlOptions['checkAllLast'];
        }
        unset($htmlOptions['checkAll'], $htmlOptions['checkAllLast']);

        $labelOptions = isset($htmlOptions['labelOptions']) ? $htmlOptions['labelOptions'] : array();
        unset($htmlOptions['labelOptions']);

        $items = array();
        $baseID = self::getIdByName($name);
        $id = 0;
        $checkAll = true;

        foreach ($data as $value => $label) {
            $checked = !is_array($select) && !strcmp($value, $select) || is_array($select) && in_array($value, $select);
            $checkAll = $checkAll && $checked;
            $htmlOptions['value'] = $value;
            $htmlOptions['id'] = $baseID . '_' . $id++;
            $option = self::checkBox($name, $checked, $htmlOptions);
            //$label = self::label($label, $htmlOptions['id'], $labelOptions);
            $items[] = strtr($template, array('{input}' => $option, '{label}' => $label));
        }

        if (isset($checkAllLabel)) {
            $htmlOptions['value'] = 1;
            $htmlOptions['id'] = $id = $baseID . '_all';
            $option = self::checkBox($id, $checkAll, $htmlOptions);
            //$label = self::label($checkAllLabel, $id, $labelOptions);
            $item = strtr($template, array('{input}' => $option, '{label}' => $label));
            if ($checkAllLast)
                $items[] = $item;
            else
                array_unshift($items, $item);
            $name = strtr($name, array('[' => '\\[', ']' => '\\]'));
            $js = <<<EOD
$('#$id').click(function() {
	$("input[name='$name']").prop('checked', this.checked);
});
$("input[name='$name']").click(function() {
	$('#$id').prop('checked', !$("input[name='$name']:not(:checked)").length);
});
$('#$id').prop('checked', !$("input[name='$name']:not(:checked)").length);
EOD;
            $cs = Yii::app()->getClientScript();
            $cs->registerCoreScript('jquery');
            $cs->registerScript($id, $js);
        }
        $strfld = '';
        foreach ($items as $item) {
            $strfld .= '<li>' . $item . '</li>';
        }

        //if (empty($container))
        //    return implode($separator, $items);
        //else
            return self::tag('ul', array('id' => $baseID, 'class' => 'srlfield'), $strfld);
    }

    /**
     * Generates a radio button list.
     * A radio button list is like a {@link checkBoxList check box list}, except that
     * it only allows single selection.
     * @param string $name name of the radio button list. You can use this name to retrieve
     * the selected value(s) once the form is submitted.
     * @param string $select selection of the radio buttons.
     * @param array $data value-label pairs used to generate the radio button list.
     * Note, the values will be automatically HTML-encoded, while the labels will not.
     * @param array $htmlOptions addtional HTML options. The options will be applied to
     * each radio button input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each radio button is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * radio button input tag while "{label}" will be replaced by the corresponding radio button label.</li>
     * <li>separator: string, specifies the string that separates the generated radio buttons. Defaults to new line (<br/>).</li>
     * <li>labelOptions: array, specifies the additional HTML attributes to be rendered
     * for every label tag in the list.</li>
     * <li>container: string, specifies the radio buttons enclosing tag. Defaults to 'span'.
     * If the value is an empty string, no enclosing tag will be generated</li>
     * </ul>
     * @return string the generated radio button list
     */
    public static function radioButtonList($name, $select, $data, $htmlOptions=array()) {
        $template = isset($htmlOptions['template']) ? $htmlOptions['template'] : '{input} {label}';
        $separator = isset($htmlOptions['separator']) ? $htmlOptions['separator'] : "<br/>\n";
        $container = isset($htmlOptions['container']) ? $htmlOptions['container'] : 'span';
        unset($htmlOptions['template'], $htmlOptions['separator'], $htmlOptions['container']);

        $labelOptions = isset($htmlOptions['labelOptions']) ? $htmlOptions['labelOptions'] : array();
        unset($htmlOptions['labelOptions']);

        $items = array();
        $baseID = self::getIdByName($name);
        $id = 0;
        foreach ($data as $value => $label) {
            $checked = !strcmp($value, $select);
            $htmlOptions['value'] = $value;
            $htmlOptions['id'] = $baseID . '_' . $id++;
            $option = self::radioButton($name, $checked, $htmlOptions);
            //$label = self::label($label, $htmlOptions['id'], $labelOptions);
            $items[] = strtr($template, array('{input}' => $option, '{label}' => $label));
        }
        $strfld = '';
        foreach ($items as $item) {
            $strfld .= '<li>' . $item . '</li>';
        }
//        if (empty($container))
//            return $strfld;
//        else
        return self::tag('ul', array('id' => $baseID, 'class' => 'srlfield'), $strfld);
    }

    /**
     * Generates a check box list for a model attribute.
     * The model attribute value is used as the selection.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * Note that a check box list allows multiple selection, like {@link listBox}.
     * As a result, the corresponding POST value is an array. In case no selection
     * is made, the corresponding POST value is an empty string.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data value-label pairs used to generate the check box list.
     * Note, the values will be automatically HTML-encoded, while the labels will not.
     * @param array $htmlOptions addtional HTML options. The options will be applied to
     * each checkbox input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each checkbox is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * check box input tag while "{label}" will be replaced by the corresponding check box label.</li>
     * <li>separator: string, specifies the string that separates the generated check boxes.</li>
     * <li>checkAll: string, specifies the label for the "check all" checkbox.
     * If this option is specified, a 'check all' checkbox will be displayed. Clicking on
     * this checkbox will cause all checkboxes checked or unchecked.</li>
     * <li>checkAllLast: boolean, specifies whether the 'check all' checkbox should be
     * displayed at the end of the checkbox list. If this option is not set (default)
     * or is false, the 'check all' checkbox will be displayed at the beginning of
     * the checkbox list.</li>
     * <li>encode: boolean, specifies whether to encode HTML-encode tag attributes and values. Defaults to true.</li>
     * </ul>
     * Since 1.1.7, a special option named 'uncheckValue' is available. It can be used to set the value
     * that will be returned when the checkbox is not checked. By default, this value is ''.
     * Internally, a hidden field is rendered so when the checkbox is not checked, we can still
     * obtain the value. If 'uncheckValue' is set to NULL, there will be no hidden field rendered.
     * @return string the generated check box list
     * @see checkBoxList
     */
    public static function activeCheckBoxList($model, $attribute, $data, $htmlOptions=array()) {
        self::resolveNameID($model, $attribute, $htmlOptions);
        $selection = self::resolveValue($model, $attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($htmlOptions);
        $name = $htmlOptions['name'];
        unset($htmlOptions['name']);

        if (array_key_exists('uncheckValue', $htmlOptions)) {
            $uncheck = $htmlOptions['uncheckValue'];
            unset($htmlOptions['uncheckValue']);
        }
        else
            $uncheck = '';

        $hiddenOptions = isset($htmlOptions['id']) ? array('id' => self::ID_PREFIX . $htmlOptions['id']) : array('id' => false);
        $hidden = $uncheck !== null ? self::hiddenField($name, $uncheck, $hiddenOptions) : '';

        return $hidden . self::checkBoxList($name, $selection, $data, $htmlOptions);
    }

    /**
     * Generates a radio button list for a model attribute.
     * The model attribute value is used as the selection.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data value-label pairs used to generate the radio button list.
     * Note, the values will be automatically HTML-encoded, while the labels will not.
     * @param array $htmlOptions addtional HTML options. The options will be applied to
     * each radio button input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each radio button is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * radio button input tag while "{label}" will be replaced by the corresponding radio button label.</li>
     * <li>separator: string, specifies the string that separates the generated radio buttons. Defaults to new line (<br/>).</li>
     * <li>encode: boolean, specifies whether to encode HTML-encode tag attributes and values. Defaults to true.</li>
     * </ul>
     * Since version 1.1.7, a special option named 'uncheckValue' is available that can be used to specify the value
     * returned when the radio button is not checked. By default, this value is ''. Internally, a hidden field is
     * rendered so that when the radio button is not checked, we can still obtain the posted uncheck value.
     * If 'uncheckValue' is set as NULL, the hidden field will not be rendered.
     * @return string the generated radio button list
     * @see radioButtonList
     */
    public static function activeRadioButtonList($model, $attribute, $data, $htmlOptions=array()) {
        self::resolveNameID($model, $attribute, $htmlOptions);
        $selection = self::resolveValue($model, $attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($htmlOptions);
        $name = $htmlOptions['name'];
        unset($htmlOptions['name']);

        if (array_key_exists('uncheckValue', $htmlOptions)) {
            $uncheck = $htmlOptions['uncheckValue'];
            unset($htmlOptions['uncheckValue']);
        }
        else
            $uncheck = '';

        $hiddenOptions = isset($htmlOptions['id']) ? array('id' => self::ID_PREFIX . $htmlOptions['id']) : array('id' => false);
        $hidden = $uncheck !== null ? self::hiddenField($name, $uncheck, $hiddenOptions) : '';

        return $hidden . self::radioButtonList($name, $selection, $data, $htmlOptions);
    }

}

?>
