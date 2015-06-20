<?php
namespace Quanlynguoinopthue\Controller;

use Application\base\baseController;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Entity\nguoinopthue;
use Quanlynguoinopthue\Forms\formNguoiNopThue;
use Application\Entity\coquanthue;
use Zend\Form\Element\Select;
use Doctrine\ORM\Query\AST\NullIfExpression;

/**
 * NguoinopthueController
 *
 * @author
 *
 * @version
 *
 */
class NguoinopthueController extends baseController
{

    /**
     * Load danh sách và Xóa
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $post = $request->getPost();
        
        $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
        
        $dsnguoinopthue = $nguoinopthueModel->DanhSachByIdentity($this->getUser())
            ->getObj();
        
        if ($post->get("HanhDong") == "xoa") {
            
            return array(
                'dsnnt' => $dsnguoinopthue,
                'kq' => $kq
            );
        }
        
        return array(
            'dsnnt' => $dsnguoinopthue
        );
    }

    /**
     * Thêm và sửa
     */
    public function persitAction()
    {
        try {
            // khởi tạo form
            $form = new formNguoiNopThue();
            
            /* @var $quans coquanthue */
            $quans = $this->getEntityManager()->createQueryBuilder()
                            ->select('coquanthue')
                            ->from("Application\Entity\coquanthue", "coquanthue")
                            ->where("coquanthue.chicucthue is null")
                            ->getQuery()->getResult();
            

            /* @var $select Select */
            $select = $form->get('Quan');
            
           
            
            $options = $select->getValueOptions();
            foreach ($quans as $quan){
                $items = array();
                $items['value'] = $quan->getMaCoQuan();
                $items['label'] = $quan->getTenGoi();
                
               
                
                array_push($options, $items);
            }
            $select->setValueOptions($options);
            //var_dump($options);
            
            /* @var $HanhDongElem Element */
            $HanhDongElem = $form->get('HanhDong');
            if ($this->getRequest()->isGet()) {
                $HanhDongElem->setAttribute('value', 'Them');
            }
            
            if ($this->getRequest()->isPost()) {
                
                $post = $this->getRequest()->getPost();
                // Sua
                if ($post->get('HanhDong') == 'Sua') {
                    $dkt = new dukienthue();
                    $form->setInputFilter($dkt->getInputFilter());
                    $form->setData($this->getRequest()
                        ->getPost());
                    if ($form->isValid()) {
                        // Viet ham sua
                    }
                } else  // Them
                    if ($post->get('HanhDong') == 'Them') {
                        
                        $dkt = new dukienthue();
                        
                        $form->setInputFilter($dkt->getInputFilter());
                        $form->setData($post);
                        
                        if ($form->isValid()) {
                            
                            // Viet ham them
                            // new mucluc
                            $mucluc = new muclucngansach();
                            $mucluc->setTieuMuc($post->get('TieuMuc'));
                            // new nguoinopthue
                            $nguoinopthue = new nguoinopthue();
                            $nguoinopthue->setMaSoThue($post->get('MaSoThue'));
                            
                            // new dkt
                            $dkt->setKyThue($post->get('KyThue'));
                            $dkt->setNguoinopthue($this->getEntityManager()
                                ->find('Application\Entity\nguoinopthue', $post->get('MaSoThue')));
                            $dkt->setMuclucngansach($this->getEntityManager()
                                ->find('Application\Entity\muclucngansach', $post->get('TieuMuc')));
                            $dkt->setUser($this->getUser());
                            
                            $model = new dukienthueModel($this->getEntityManager());
                            $kq = $model->them($dkt);
                            
                            return array(
                                'kq' => $kq,
                                'form' => $form
                            );
                        }
                    }  // NewSua
else 
                        if ($post->get('HanhDong') == 'NewSua') {
                            // tim
                            
                            // setAttr cho form
                            
                            // trả form to view
                            return array(
                                'quan' => $quans,
                                'form' => $form
                            );
                        }
            }
            // NewThem
            // form to view
            return array(
                'form' => $form
            );
        } catch (\Exception $e) {
            var_dump($e);
        }
    }
    
    // ajax
    public function dsPhuongForQuanAction(){
        $MaCoQuan = $this->getRequest()->getQuery()->get('MaCoQuan');
        $ChiCucThue = $this->getEntityManager()->find("Application\Entity\coquanthue", $MaCoQuan);
        if($ChiCucThue!=null){
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select(array('phuong'))->from('Application\Entity\phuong', 'phuong')
            ->join("phuong.coquanthue", "doithue")
            ->join("doithue.chicucthue", 'chicucthue')
            ->where("chicucthue = ?1")
            ->setParameter(1, $ChiCucThue);
            $kq = $qb->getQuery()->getArrayResult();
            if($kq!=null && count($kq)>0)
            {
                echo json_encode($kq);
            }
            else{
                echo json_encode(array());
            }
            
            
        }
        
        
        return $this->response;
    }
    public function dsnntbyidentityAction()
    {
        $nntModel = new nguoinopthueModel($this->getEntityManager());
        
        $nnt = $nntModel->getDanhSachByIdentity($this->getUser());
        
        if ($nnt->getKq() == true) {
            echo json_encode($nnt->getObj(), JSON_UNESCAPED_UNICODE);
        } else {
            $data['loi'] = true;
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        
        return $this->response;
    }

    /**
     * Kiểm tra mã số thuế có tồn tai khong
     * return json
     */
    public function checkmasothueAction()
    {
        $masothue = $this->getRequest()
            ->getPost()
            ->get("MaSoThue");
        
        $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $masothue);
        
        $kq = null;
        if ($nguoinopthue != null) {
            $kq= array(
                'dem'=>1
            );
        }
        else{
            $kq= array(
                'dem'=>0
            );
        }
        
        echo json_encode($kq);
        return $this->response;
    }
}