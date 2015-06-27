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
     * @param string $DateBegin       
     * @param string $DateEnd            
     * @return bool
     */
    public static function CheckTodayBetweenTowDay($DateBegin,$DateEnd)
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
     * Kiểm tra ngày hiện tại có lớn hơn hoặc bằng ngày truyền vào hay khog ?
     * format d-m-Y
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
}
