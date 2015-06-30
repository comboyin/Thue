<?php
namespace Application\Controller;

use Application\base\baseController;
class ServiceController extends baseController
{
    /**
     *   AJAX
     *   download file neu file ton tai
     *   */
    public function downloadFileAction(){
    
        $fileNameErr = $this->request->getQuery()->get('filename');
    
        if (file_exists($fileNameErr)) {
             
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$fileNameErr);
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileNameErr)); // $file));
            ob_clean();
            flush();
            // readfile($file);
            readfile($fileNameErr);
            unlink ($fileNameErr);
            exit;
    
        }
        $this->response;
    }
}

?>