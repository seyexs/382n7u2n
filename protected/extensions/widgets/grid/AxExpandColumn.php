<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AxExpandColumn
 *
 * @author ardha
 * @created on Dec 25, 2012
 */
Yii::import('zii.widgets.grid.CGridColumn');

class AxExpandColumn extends CGridColumn {

    /**
     * @var string the attribute name of the data model. The corresponding attribute value will be rendered
     * in each data cell as the checkbox value. Note that if {@link value} is specified, this property will be ignored.
     * @see value
     */
    public $name;

    /**
     * @var string a PHP expression that will be evaluated for every data cell and whose result will be rendered
     * in each data cell as the checkbox value. In this expression, the variable
     * <code>$row</code> the row number (zero-based); <code>$data</code> the data model for the row;
     * and <code>$this</code> the column object.
     */
    public $value;

    public $expanded;

    /**
     * @var array the HTML options for the data cell tags.
     */
    public $htmlOptions = array(
        'class' => 'x-grid-cell-special',
    );

    /**
     * @var array the HTML options for the header cell tag.
     */
    public $headerHtmlOptions = array('class' => 'v-grid-cell-special');

    /**
     * @var array the HTML options for the footer cell tag.
     */
    public $footerHtmlOptions = array('class' => 'v-grid-cell-special');

    /**
     * @var array the HTML options for the checkboxes.
     */
    public $expandHtmlOptions = array('class' => 'v-grid-row-expander v-grid-row-expanded','state' => 'collapse');

    public $expandContent = '';
    
    public $headerTemplate = '{item}';
    public $url;

    /**
     * Initializes the column.
     * This method registers necessary client script for the checkbox column.
     */
    public function init() {
        if (isset($this->expandHtmlOptions['name']))
            $name = $this->expandHtmlOptions['name'];
        else {
            $name = $this->id;
            if (substr($name, -2) !== '[]')
                $name.='[]';
            $this->expandHtmlOptions['name'] = $name;
        }
        $name = strtr($name, array('[' => "\\[", ']' => "\\]"));
    }


    /**
     * Renders the data cell content.
     * This method renders a checkbox in the data cell.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    protected function renderDataCellContent($row, $data) {
        if ($this->value !== null)
            $value = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
        else if ($this->name !== null)
            $value = CHtml::value($data, $this->name);
        $value = $value === null ? $this->grid->nullDisplay : $value;
        $options = $this->expandHtmlOptions;
        $options['id'] = $this->id.'-expand-'.$row;
        $data = array('id'=>$value);
        $data = CJavaScript::encode($data);
        $options['onclick'] = "expandRow('{$row}', '{$this->grid->id}','{$this->id}','{$dataId}','{$this->url}')";
        echo CHtml::openTag('div', $options);
        echo CHtml::closeTag('div');
    }
}
?>
