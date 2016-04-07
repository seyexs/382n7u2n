<?php

class WFRExcelExpressionAdv {

    public $field = array();
    public $loop = array();
    public $formula = array();

}

class WFRExcelRegexAdv {
    /*
     *  check text is field var or not
     */

    static function isFieldVar($text) {
        $match = "";
        if (preg_match('/{f:(.+?)}/i', $text, $match)) {
            if ($match[1] != "")
                return true;
        }
        return false;
    }

    /**
     *
     * @param String $text
     * @param Array $expressionData
     * @return String newString
     */
    static function changeFieldVar($text, $expressionData) {
        $match = "";
        preg_match_all('/{f:(.+?)}/i', $text, $match);

        $fullMatch = $match[0];
        $tempExp = preg_replace('/{f:(.+?)}/i', '?', $text);

        $arrExp = explode('?', $tempExp);

        $newExp = "";
        $i = 0;
        for ($i = 0; $i < count($fullMatch); $i++) {
            $varName = "";
            preg_match('/{f:(.+?)}/i', $fullMatch[$i], $varName);
            $varName = $varName[1];
            $newExp .= $arrExp[$i] . $expressionData->field[$varName];
        }
        $newExp .= $arrExp[$i];
        return $newExp;
    }

    /*
     *  check text is loop var or not
     */

    static function isLoopVar($text) {
        $match = "";
        if (preg_match('/{l\[(.+?)\]:(.+?)}/i', $text, $match)) {
            if ($match[1] != "")
                return true;
        }
        return false;
    }

    static function isStaticFormula($text) {
        $match = "";
        if (preg_match('/{fx:(.+?)}/i', $text, $match)) {
            if ($match[1] != "")
                return true;
        }
        return false;
    }

    static function getStatisticFormula($text, $range) {

        $match = "";
        $formula = "";
        if (preg_match('/{fx:(.+?)}/i', $text, $match)) {
            $formula = $match[1];
        }

        $newFormula = $formula;

        preg_match_all('/([a-z]+)([0-9]+):([a-z]+)([0-9]+)/i', $formula, $match);

        $fullMatch = $match[0];
        $tempFormula = preg_replace('/([a-z]+)([0-9]+):([a-z]+)([0-9]+)/i', '?', $formula);

        $arrFormula = explode('?', $tempFormula);

        $newFormula = "";
        $i = 0;
        for ($i = 0; $i < count($fullMatch); $i++) {
            $splitter = array();
            preg_match('/([a-z]+)([0-9]+):([a-z]+)([0-9]+)/i', $fullMatch[$i], $splitter);

            $column = $splitter[1];
            $number = $splitter[2] + $range - 1;

            $column2 = $splitter[3];
            $number2 = $splitter[4] + $range - 1;

            $newFormula .= $arrFormula[$i] . $column . $number . ":" . $column2 . $number2;
        }
        $newFormula .= $arrFormula[$i];
        return $newFormula;
    }

    static function getLoopIndexVar($text) {
        $match = "";
        if (preg_match('/{l\[(.+?)\]:(.+?)}/i', $text, $match)) {
            if ($match[1] != "")
                return $match[1];
        }
        return false;
    }

    static function changeLoopVar($text, $expressionData, $index) {
        $match = "";
        preg_match_all('/{l\[(.+?)\]:(.+?)}/i', $text, $match);

        $fullMatch = $match[0];
        $tempExp = preg_replace('/{l\[(.+?)\]:(.+?)}/i', '?', $text);

        $arrExp = explode('?', $tempExp);

        $newExp = "";
        $i = 0;
        for ($i = 0; $i < count($fullMatch); $i++) {
            $varName = "";
            preg_match('/{l\[(.+?)\]:(.+?)}/i', $fullMatch[$i], $varName);
            $varName = $varName[2];

            if ($varName == "iterate") {
                $newExp .= $arrExp[$i] . ($index + 1);
                continue;
            }
            $newExp .= $arrExp[$i] . $expressionData[$index]->$varName;
        }
        $newExp .= $arrExp[$i];

        return $newExp;
    }

    public static function addRangeLoopFormula($text, $range) {
        $match = "";
        $newFormula = $text;

        preg_match_all('/([a-z]+)([0-9]+):([a-z]+)([0-9]+)/i', $text, $match);

        $fullMatch = $match[0];
        $tempFormula = preg_replace('/([a-z]+)([0-9]+):([a-z]+)([0-9]+)/i', '?', $text);

        $arrFormula = explode('?', $tempFormula);

        $newFormula = "";
        $i = 0;
        for ($i = 0; $i < count($fullMatch); $i++) {
            $splitter = array();
            preg_match('/([a-z]+)([0-9]+):([a-z]+)([0-9]+)/i', $fullMatch[$i], $splitter);

            $column = $splitter[1];
            $number = $splitter[2];

            $column2 = $splitter[3];
            $number2 = $splitter[4] + $range;

            $newFormula .= $arrFormula[$i] . $column . $number . ":" . $column2 . $number2;
        }
        $newFormula .= $arrFormula[$i];

        return $newFormula;
    }

    public static function addRangeInlineFormula($exp, $range) {
        $match = "";
        $newFormula = $exp;

        preg_match_all('/([a-z]+)([0-9]+)/i', $exp, $match);

        $fullMatch = $match[0];
        $tempFormula = preg_replace('/([a-z]+)([0-9]+)/i', '?', $exp);

        $arrFormula = explode('?', $tempFormula);

        $newFormula = "";
        $i = 0;
        for ($i = 0; $i < count($fullMatch); $i++) {
            $splitter = array();
            preg_match('/([a-z]+)([0-9]+)/i', $fullMatch[$i], $splitter);

            $column = $splitter[1];
            $number = $splitter[2] + $range;

            $newFormula .= $arrFormula[$i] . $column . $number;
        }
        $newFormula .= $arrFormula[$i];

        return $newFormula;
    }

}

class WFRExcelWrapperAdv {

    /**
     * PHP Object
     *
     * @var PHPExcel
     */
    var $phpExcelObject = null;

    /**
     * Inizialization
     *
     * @param 	PHPExcel 		$phpExcelObject
     */
    public function __construct($phpExcelObject) {
        $this->phpExcelObject = $phpExcelObject;
    }

    /**
     * Replace Expression with data
     *
     * @param 	WFRExcelExpression 	$expressionData
     */
    function replaceExpression($expressionData) {
        $objWorksheet = $this->phpExcelObject->getActiveSheet();

        $rowCopy = array();
        $loopIndex = -1;
        $currentRowCount = 0;

        foreach ($objWorksheet->getRowIterator() as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $haveLoop = false;
            foreach ($cellIterator as $cell) {
                $cellValue = $cell->getValue();
                if (WFRExcelRegexAdv::isFieldVar($cellValue)) {
                    $finalValue = WFRExcelRegexAdv::changeFieldVar($cellValue, $expressionData);
                    $cell->setValue($finalValue);
                }

                if (WFRExcelRegexAdv::isLoopVar($cellValue)) {
                    $haveLoop = true;
                    $loopArrayIndex = WFRExcelRegexAdv::getLoopIndexVar($cellValue);
                    $loopIndex = $row->getRowIndex();
                }

                if(WFRExcelRegexAdv::isStaticFormula($cellValue))
                {
                    $finalValue = WFRExcelRegexAdv::getStatisticFormula($cellValue, $currentRowCount);
                    $cell->setValueExplicit($finalValue, PHPExcel_Cell_DataType::TYPE_FORMULA);
                    continue;
                }

                if ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA) {
                    $formula = $cell->getValue();
                    if ($loopIndex + count($expressionData->loop[$loopArrayIndex]) <= $row->getRowIndex()) {
                        $formula = WFRExcelRegex::addRangeLoopFormula($cell->getValue(), count($expressionData->loop[$loopArrayIndex]) - 1);
                    }

                    $cell->setValueExplicit($formula, PHPExcel_Cell_DataType::TYPE_FORMULA);
                }
            }

            if ($haveLoop) {
                $rowCopy = array();
                $cellLoop = 0;

                //COPY ROW VAL
                foreach ($cellIterator as $cell) {
                    $rowCopy[$cellLoop] = clone $cell;
                    $cellLoop++;
                }

                $rowIndex = $row->getRowIndex();
                $data = $expressionData->loop[$loopArrayIndex];
                $dataCount = count($data);
                $currentRowCount += $dataCount;
                $objWorksheet->insertNewRowBefore($rowIndex + 1, $dataCount);

                for ($i = 0; $i < $dataCount; $i++) {
                    for ($j = 0; $j < count($rowCopy); $j++) {
                        $cellVal = $rowCopy[$j]->getValue();
                        $cellVal = WFRExcelRegexAdv::changeLoopVar($cellVal, $data, $i);
                        if ($rowCopy[$j]->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA) {
                            $cellVal = WFRExcelRegex::addRangeInlineFormula($rowCopy[$j]->getValue(), $i);
                        }
                        if ($rowCopy[$j]->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA) {
                            $objWorksheet->setCellValueExplicitByColumnAndRow($j, $rowIndex + $i + 1, $cellVal, PHPExcel_Cell_DataType::TYPE_STRING);
                        } else {
                            $objWorksheet->setCellValueByColumnAndRow($j, $rowIndex + $i + 1, $cellVal);
                        }
                    }
                }
                $objWorksheet->removeRow($row->getRowIndex());
                $haveLoop = false;
            }
        }
    }

}

?>