<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AxHelpers {

    public static function getTahunAjaran(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            if (isset($row[$col]))
                $sort_col[$key] = $row[$col];
            else
                $sort_col[$key] = 0;
        }
        array_multisort($sort_col, $dir, $arr);
    }

    public static function sendEmail($to, $subject, $content, $attachmentpath = null, $attachmentname = '', $attachmenttype = 'text/xml') {
        $mailer = Yii::createComponent('application.extensions.mailer.EMailer');
        $mailer->IsSMTP();
        $mailer->IsHTML(true); // send as HTML
        $mailer->SMTPDebug = 0;
        $mailer->SMTPAuth = true;
        $mailer->SMTPSecure = "ssl";
        $mailer->Host = Yii::app()->params['emailer']['mailserver'];
        $mailer->Port = Yii::app()->params['emailer']['port'];
        $mailer->Username = Yii::app()->params['emailer']['username'];
        $mailer->Password = Yii::app()->params['emailer']['password'];
        $mailer->From = Yii::app()->params['emailer']['username'];
        $mailer->AddAddress($to);
        $mailer->FromName = Yii::app()->params['emailer']['from'];
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = $subject;
        $mailer->Body = $content;
        $encoding = 'base64';
        if ($attachmentpath !== null)
            $mailer->AddAttachment($attachmentpath, $attachmentname, $encoding, $attachmenttype);
        return $mailer->Send();
    }

    /**
     * Convert number of seconds into hours, minutes and seconds
     * and return an array containing those values
     *
     * @param integer $seconds Number of seconds to parse
     * @return array
     */
    public static function secondsToTime($seconds) {
        // extract hours
        $hours = floor($seconds / (60 * 60));

        // extract minutes
        $divisor_for_minutes = $seconds % (60 * 60);
        $minutes = floor($divisor_for_minutes / 60);

        // extract the remaining seconds
        $divisor_for_seconds = $divisor_for_minutes % 60;
        $seconds = ceil($divisor_for_seconds);

        // return the final array
        $obj = array(
            "h" => $hours < 10 ? '0' . (string) $hours : $hours,
            "m" => $minutes < 10 ? '0' . (string) $minutes : $minutes,
            "s" => $seconds < 10 ? '0' . (string) $seconds : $seconds,
        );
        return $obj;
    }

    function secondsToTime2($inputSeconds) {

        $secondsInAMinute = 60;
        $secondsInAnHour = 60 * $secondsInAMinute;
        $secondsInADay = 24 * $secondsInAnHour;

        // extract days
        $days = floor($inputSeconds / $secondsInADay);

        // extract hours
        $hourSeconds = $inputSeconds % $secondsInADay;
        $hours = floor($hourSeconds / $secondsInAnHour);

        // extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        // extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds = ceil($remainingSeconds);

        // return the final array
        $obj = array(
            'd' => (int) $days,
            'h' => (int) $hours,
            'm' => (int) $minutes,
            's' => (int) $seconds,
        );
        return $obj;
    }

    public static function createLastDateOfMonthYear($month, $year) {
        return date("Y-m-d", strtotime("+1 month -1 second", strtotime(date("Y-m-1", mktime(0, 0, 0, intval($month), 1, intval($year))))));
    }

    public static function sfPrint($str = '') {
        if (!empty($str))
            return $str;
        else
            return '';
    }

    public static function yiiparam($name, $default = null) {
        if (isset(Yii::app()->params[$name]))
            return Yii::app()->params[$name];
        else
            return $default;
    }

    public static function toPDF($view, $params = array()) {
        $controller = Yii::app()->getController();
        $html2pdf = Yii::app()->ePdf->HTML2PDF('L', 'A4', 'en', true, 'UTF-8', array(10, 12, 7, 7));
        $html2pdf->WriteHTML($controller->renderPartial($view, $params, true));
        $html2pdf->Output();
    }

    public static function toExcel($view, $params = array(), $filename = '') {
        $controller = Yii::app()->getController();
        Yii::app()->request->sendFile($filename, $controller->renderPartial($view, $params, true));
    }

    public static function toView($view, $params = array()) {
        $controller = Yii::app()->getController();
        $controller->render($view, $params);
    }

    Public static function isint($mixed) {
        return (preg_match('/^\d*$/', $mixed) == 1 );
    }

}

?>
