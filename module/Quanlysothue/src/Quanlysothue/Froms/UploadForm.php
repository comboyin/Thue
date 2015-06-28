<?php
namespace Quanlysothue\Froms;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class UploadForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('dukientruythu-file');
        $file->setAttribute('class', "default")
            ->setAttribute("accept", ".xls,.xlsx")
            ->setAttribute('id', 'dukientruythu-file');
        $this->add($file);
    }

    public function addInputFilter()    
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('dukientruythu-file');
        $fileInput->setRequired(true);
        
        $fileInput->getValidatorChain()
        ->attachByName('filemimetype',  array('mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-excel'));
        //            ->attachByName('fileimagesize', array('maxWidth' => 100, 'maxHeight' => 100));
        
        
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/filetmp/dukientruythu.xlsx',
                'randomize' => true,
            )
        );
        
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
}