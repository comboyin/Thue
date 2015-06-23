<?php
namespace Quanlynguoinopthue\Controller;

use Application\base\baseController;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Entity\nguoinopthue;
use Quanlynguoinopthue\Forms\formNguoiNopThue;
use Application\Entity\coquanthue;
use Zend\Form\Element\Select;
use Doctrine\ORM\Query\AST\NullIfExpression;
use Application\Entity\nganh;
use Application\Entity\phuong;

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
            $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
            /* @var $quans coquanthue */
            $quans = $this->getEntityManager()
                ->createQueryBuilder()
                ->select('coquanthue')
                ->from("Application\Entity\coquanthue", "coquanthue")
                ->where("coquanthue.chicucthue is null")
                ->getQuery()
                ->getResult();
            
            /* @var $select Select */
            $select = $form->get('Quan');
            $options = $select->getValueOptions();
            foreach ($quans as $quan) {
                $items = array();
                $items['value'] = $quan->getMaCoQuan();
                $items['label'] = $quan->getTenGoi();
                array_push($options, $items);
            }
            $select->setValueOptions($options);
            // var_dump($options);
            
            /* @var $HanhDongElem Element */
            $HanhDongElem = $form->get('HanhDong');
            
            // load nganh
            /* @var $nganhs nganh */
            $nganhs = $this->getEntityManager()
                ->getRepository("Application\Entity\\nganh")
                ->findAll();
            /* @var $selectNganh Select */
            $selectNganh = $form->get('Nganh');
            $options = $selectNganh->getValueOptions();
            foreach ($nganhs as $nganh) {
                $items = array();
                $items['value'] = $nganh->getMaNganh();
                $items['label'] = $nganh->getTenNganh();
                array_push($options, $items);
            }
            $selectNganh->setValueOptions($options);
            
            // load phuong
            /* @var $phuongs phuong */
            $phuongs = $this->getEntityManager()
                ->getRepository("Application\Entity\phuong")
                ->findAll();
            $selectPhuong = $form->get('Phuong');
            $options = $selectPhuong->getValueOptions();
            foreach ($phuongs as $phuong) {
                $items = array();
                $items['value'] = $phuong->getMaPhuong();
                $items['label'] = $phuong->getTenPhuong();
                array_push($options, $items);
            }
            $selectPhuong->setValueOptions($options);
            
            if ($this->getRequest()->isGet()) {
                $HanhDongElem->setAttribute('value', 'Them');
            }
            
            if ($this->getRequest()->isPost()) {
                
                $post = $this->getRequest()->getPost();
                // Sua
                if ($post->get('HanhDong') == 'Sua') {
                    $nguoinopthue = new nguoinopthue();
                    $form->setInputFilter($nguoinopthue->getInputFilter());
                    $form->setData($this->getRequest()
                        ->getPost());
                    if ($form->isValid()) {
                        // Viet ham sua
                    }
                } else 
                    if ($post->get('HanhDong') == 'Them') {
                        
                        $nguoinopthue = new nguoinopthue();
                        $form->setInputFilter($nguoinopthue->getInputFilter());
                        $form->setData($this->getRequest()
                            ->getPost());
                        
                        if ($form->isValid()) {
                            
                            $nguoinopthue->setMaSoThue($post->get("MaSoThue"));
                            $nguoinopthue->setNgayCapMST($post->get("NgayCapMST"));
                            $nguoinopthue->setTenHKD($post->get("TenHKD"));
                            $nguoinopthue->setDiaChiCT($post->get("DiaChiKD"));
                            $nguoinopthue->setSoGPKD($post->get("SoGPKD"));
                            $nguoinopthue->setSoCMND($post->get("SoCMND"));
                            $nguoinopthue->setNgayCapMST(\DateTime::createFromFormat('d-m-Y', $post->get("NgayCapMST")));
                            $nguoinopthue->setThoiDiemBDKD(\DateTime::createFromFormat('d-m-Y', $post->get("ThoiDiemBDKD")));
                            $nguoinopthue->setNgheKD($post->get("Nghe"));
                            
                            $kq = $nguoinopthueModel->them($nguoinopthue);
                            
                            $thoidiembdkd = \DateTime::createFromFormat('d-m-Y',$post->get('ThoiDiemBDKD'));
                            
                            $nguoinopthueModel->CallPro('in_nnt', array(
                                'MST'=>$post->get('MaSoThue'),
                                'Ngay'=>$thoidiembdkd->format('Y-m-d'),
                                'MN'=>$post->get('Nganh'),
                                'MU'=>$this->getUser()->getMaUser(),
                                'MP'=>$post->get('Phuong'),
                                'DiaChiKD'=>$post->get('DiaChiKD'),
                                'ChanLe'=>$post->get('ChanLe'),
                                'Hem'=>$post->get('Hem'),
                                'SoNha'=>$post->get('SoNha'),
                                'SoNhaPhu'=>$post->get('SoNhaPhu'),
                                'TenDuong'=>$post->get('TenDuong')
                            ));
                            
                            $kq->setMessenger('Thêm thành công !');
                            $kq->setKq(true);
                            return array(
                                'kq' => $kq,
                                'form' => $form
                            );
                        }
                    } else if ($post->get('HanhDong') == 'NewSua') {
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

    public function testAction()
    {
        $model = new nguoinopthueModel($this->getEntityManager());
        var_dump($model->CallPro('test', array(
            'a' => 123,
            'b' => '',
            'c' => '1221as21d sa'
        )
        ));
        return $this->response;
    }
    
    // ajax
    public function dsPhuongForQuanAction()
    {
        $MaCoQuan = $this->getRequest()
            ->getQuery()
            ->get('MaCoQuan');
        $ChiCucThue = $this->getEntityManager()->find("Application\Entity\coquanthue", $MaCoQuan);
        if ($ChiCucThue != null) {
            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select(array(
                'phuong'
            ))
                ->from('Application\Entity\phuong', 'phuong')
                ->join("phuong.coquanthue", "doithue")
                ->join("doithue.chicucthue", 'chicucthue')
                ->where("chicucthue = ?1")
                ->setParameter(1, $ChiCucThue);
            $kq = $qb->getQuery()->getArrayResult();
            if ($kq != null && count($kq) > 0) {
                echo json_encode($kq);
            } else {
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
            $kq = array(
                'dem' => 1
            );
        } else {
            $kq = array(
                'dem' => 0
            );
        }
        
        echo json_encode($kq);
        return $this->response;
    }
}