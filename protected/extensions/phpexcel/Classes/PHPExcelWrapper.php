<?php

class WFRExcelExpression {

    public $field = array();
    public $loop = array();
    public $formula = array();

}

class WFRExcelRegex {

    public static function getFieldVar($exp) {
        $match = "";
        if (preg_match('/{field:(.+?)}/i', $exp, $match)) {
            return $match[1];
        }
    }

    public static function composeFieldVar($exp, $text) {
        $composite = preg_replace('/{field:(.+?)}/i', '?', $exp);

        $arrExp = explode('?', $composite);
        return $arrExp[0] . $text . $arrExp[1];
    }

    public static function getLoopVar($exp) {
        $match = "";
        if (preg_match('/{loop:(.+?)}/i', $exp, $match)) {
            if ($match[1] != "")
                return $match[1];
            else
                return $exp;
        }
    }

    public static function composeLoopVar($exp, $text) {
        $composite = preg_replace('/{field:(.+?)}/i', '?', $exp);
        $arrExp = explode('?', $composite);
        return $arrExp[0] . $text . $arrExp[1];
    }

    public static function getFormula($exp) {
        $match = "";
        if (preg_match('/{formula:(.+?)}/i', $exp, $match)) {
            return $match[1];
        }
    }

    public static function isFieldVar($text) {
        $match = "";
        if (preg_match('/{field:(.+?)}/i', $text, $match)) {
            if ($match[1] != "")
                return true;
        }
        return false;
    }

    public static function isLoopVar($exp) {
        $match = "";
        if (preg_match('/{loop:(.+?)}/i', $exp, $match)) {
            return $match[1];
        }
    }

    public static function isFormula($text) {
        $match = "";
        if (preg_match('/{formula:(.+?)}/i', $text, $match)) {
            if ($match[1] != "")
                return true;
        }
        return false;
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

    public static function addRangeLoopFormula($exp, $range) {
        $match = "";
        $newFormula = $exp;

        preg_match_all('/([a-z]+)([0-9]+):([a-z]+)([0-9]+)/i', $exp, $match);

        $fullMatch = $match[0];
        $tempFormula = preg_replace('/([a-z]+)([0-9]+):([a-z]+)([0-9]+)/i', '?', $exp);

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

    public static function addRange($exp, $range) {
        $match = "";
        $newFormula = $exp;
        if (preg_match('/=(.+?)\(([a-z]+)(?)(\d+):([a-z]+)(?)(\d+)\)/i', $exp, $match)) {
            $newFormula = "=" . $match[1] . "(" . $match[2] . $match[3] . ":" . $match[4] . (intval($match[5]) + $range) . ")";
        }

        return $newFormula;
    }

}

class WFRExcelWrapper {

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


        foreach ($objWorksheet->getRowIterator() as $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $haveLoop = false;
            foreach ($cellIterator as $cell) {
                $cellValue = $cell->getValue();
                if (WFRExcelRegex::isFieldVar($cellValue)) {
                    $fieldName = WFRExcelRegex::getFieldVar($cellValue);
                    $fieldValue = $expressionData->field[$fieldName];
                    $finalVal = WFRExcelRegex::composeFieldVar($cellValue, $fieldValue);
                    $cell->setValue($finalVal);
                }

                if (WFRExcelRegex::isLoopVar($cellValue)) {
                    $haveLoop = true;
                    $loopIndex = $row->getRowIndex();
                }

                if ($cell->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA) {
                    $formula = $cell->getValue();
                    if ($loopIndex + count($expressionData->loop) <= $row->getRowIndex()) {
                        $formula = WFRExcelRegex::addRangeLoopFormula($cell->getValue(), count($expressionData->loop) - 1);
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
                $dataCount = count($expressionData->loop);
                $objWorksheet->insertNewRowBefore($rowIndex + 1, $dataCount);

                for ($i = 0; $i < $dataCount; $i++) {
                    for ($j = 0; $j < count($rowCopy); $j++) {
                        $cellVal = $rowCopy[$j]->getValue();
                        if ($rowCopy[$j]->getDataType() == PHPExcel_Cell_DataType::TYPE_FORMULA) {
                            $cellVal = WFRExcelRegex::addRangeInlineFormula($rowCopy[$j]->getValue(), $i);
                        } else if (WFRExcelRegex::isLoopVar($cellVal)) {
                            $regexVal = WFRExcelRegex::getLoopVar($cellVal);
                            if ($regexVal == "iterate") {
                                $cellVal = $i + 1;
                            }
                            else
                                $cellVal = $expressionData->loop[$i]->$regexVal;
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