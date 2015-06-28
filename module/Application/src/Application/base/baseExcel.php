<?php
namespace Application\base;

class baseExcel
{

    public function __construct()
    {
        require './vendor/phpoffice/PHPExcel-1.8/Classes/PHPExcel.php';
    }

    protected function cellColor($cells, $color, &$objPHPExcel)
    {
        $objPHPExcel->getActiveSheet()
            ->getStyle($cells)
            ->applyFromArray(array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => $color
                )
            )
        ));
    }
}

?>