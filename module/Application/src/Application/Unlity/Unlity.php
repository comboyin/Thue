<?php
namespace Application\Unlity;

class Unlity
{

    /**
     * format d-m-Y to Y-m-d
     * và ngược lại
     *
     * @param string $stringDate            
     * @return string
     *
     */
    public static function stringDateToStringDate($stringDate)
    {
        $temp = explode("-", $stringDate);
        
        $kq = $temp[2] . "-" . $temp[1] . "-" . $temp[0];
        
        return $kq;
    }

    /**
     * Kiểm tra ngày hiện tại có nằm giữa 2 ngày không ?
     * format d-m-Y
     *
     * @param string $DateBegin            
     * @param string $DateEnd            
     * @return bool
     */
    public static function CheckTodayBetweenTowDay($DateBegin, $DateEnd)
    {
        $paymentDate = new \DateTime(); // Today
        $contractDateBegin = \DateTime::createFromFormat("d-m-Y", $DateBegin);
        $contractDateEnd = \DateTime::createFromFormat("d-m-Y", $DateEnd);
        
        if ($paymentDate->getTimestamp() >= $contractDateBegin->getTimestamp() && $paymentDate->getTimestamp() <= $contractDateEnd->getTimestamp()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Kiểm tra ngày chỉ định có nằm giữa và bằng 2 ngày không ?
     * format d-m-Y
     *
     * @param string $DateBegin            
     * @param string $DateCurrent            
     * @param string $DateEnd            
     * @return bool
     */
    public static function CheckDateBetweenTowDate($DateBegin, $DateEnd, $DateCurrent)
    {
        $paymentDate = \DateTime::createFromFormat("d-m-Y", $DateCurrent);
        $contractDateBegin = \DateTime::createFromFormat("d-m-Y", $DateBegin);
        $contractDateEnd = \DateTime::createFromFormat("d-m-Y", $DateEnd);
        
        if ($paymentDate->getTimestamp() >= $contractDateBegin->getTimestamp() && $paymentDate->getTimestamp() <= $contractDateEnd->getTimestamp()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Kiểm tra ngày hiện tại có lớn hơn hoặc bằng ngày truyền vào hay khog ?
     * format d-m-Y
     *
     * @param string $Date            
     * @return bool
     */
    public static function CheckTodayLonHonHoacBang($Date)
    {
        $paymentDate = new \DateTime(); // Today
        $contractDate = \DateTime::createFromFormat("d-m-Y", $Date);
        
        if ($paymentDate->getTimestamp() >= $contractDate->getTimestamp()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Conver từ format sang d-m-Y
     * format d-m-Y
     * example:
     * Unlity::ConverDate('Y-m-d', $NgayHachToan, 'm/Y');
     * 'Y-m-d' -> 'm/Y'
     *
     * @param string $Date            
     * @return string
     */
    public static function ConverDate($format, $dateString, $formatConver)
    {
        
        $date = \DateTime::createFromFormat($format, $dateString);
        
        return $date->format($formatConver);
    }
    
    public static function ConverPhpExcelToDateTimeObject($cell)
    {
        return date('Y-m-d', \PHPExcel_Shared_Date::ExcelToPHP($cell->getValue()));
    
    }
}
