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
use Application\Entity\ketqua;
use Zend\Form\Form;
use Zend\Form\Element;
use Application\Entity\NNTNganh;

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
            
            // phân quyền form trên giao diện
            if ($this->getUser()->getLoaiUser() == 3) {
                // element CBQL
                
                $form->get('CanBoQuanLy')->setAttribute('required', true);
                $form->get('CanBoQuanLy')->setAttribute('disabled', null);
                $form->get('CanBoQuanLy')->setAttribute('class', 'span5');
                $form->get('CanBoQuanLy')->setAttribute('placeholder', 'Click tìm để chọn cán bộ quản lý');
                
                // element doithue
                
                $form->get('DoiThue')->setAttribute('value', $this->getUser()
                    ->getCoquanthue()
                    ->getTenGoi());
            } else 
                if ($this->getUser()->getLoaiUser() == 4) {
                    $form->get('CanBoQuanLy')->setAttribute('value', $this->getUser()
                        ->getTenUser());
                    $form->get('DoiThue')->setAttribute('value', $this->getUser()
                        ->getCoquanthue()
                        ->getTenGoi());
                }
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
                        if ($post->get("Quan") != null) {
                            // load phuong
                            /* @var $phuongs phuong */
                            $phuongs = $this->getEntityManager()
                                ->createQueryBuilder()
                                ->select("phuong")
                                ->from("Application\Entity\phuong", "phuong")
                                ->join("phuong.coquanthue", 'doithue')
                                ->join("doithue.chicucthue", "chicucthue")
                                ->where("chicucthue.MaCoQuan = ?1")
                                ->setParameter(1, $post->get("Quan"))
                                ->getQuery()
                                ->getResult();
                            $selectPhuong = $form->get('Phuong');
                            $options = $selectPhuong->getValueOptions();
                            foreach ($phuongs as $phuong) {
                                
                                $items = array();
                                $items['value'] = $phuong->getMaPhuong();
                                $items['label'] = $phuong->getTenPhuong();
                                array_push($options, $items);
                            }
                        }
                        
                        $selectPhuong->setValueOptions($options);
                        
                        $nguoinopthue = new nguoinopthue();
                        $form->setInputFilter($nguoinopthue->getInputFilter());
                        $form->setData($this->getRequest()
                            ->getPost());
                        
                        if ($form->isValid()) {
                            
                            $selectPhuong->setValueOptions($options);
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
                            if ($kq->getKq() == true) {
                                $thoidiembdkd = \DateTime::createFromFormat('d-m-Y', $post->get('ThoiDiemBDKD'));
                                $mauser = $this->getUser()->getMaUser();
                                if ($this->getUser()->getLoaiUser() == 3) {
                                    $mauser = $post->get("CanBoQuanLy");
                                }
                                
                                $kqCall = $nguoinopthueModel->CallPro('in_nnt', array(
                                    'MST' => $post->get('MaSoThue'),
                                    'Ngay' => $thoidiembdkd->format('Y-m-d'),
                                    'MN' => $post->get('Nganh'),
                                    'MU' => $mauser,
                                    'MP' => $post->get('Phuong'),
                                    'DiaChiKD' => $post->get('DiaChiKD'),
                                    'ChanLe' => $post->get('ChanLe'),
                                    'Hem' => $post->get('Hem'),
                                    'SoNha' => $post->get('SoNha'),
                                    'SoNhaPhu' => $post->get('SoNhaPhu'),
                                    'TenDuong' => $post->get('TenDuong')
                                ));
                                
                                if ($kqCall->getKq() == false) {
                                    $kq->setKq(false);
                                    $kq->setMessenger("Tiến trình lỗi: " . $kqCall->getMessenger());
                                    $nguoinopthueModel->remove($nguoinopthue);
                                }
                            }
                            
                            return array(
                                'kq' => $kq,
                                'form' => $form
                            );
                        }
                    } else 
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

    public function capnhatHKDAction()
    {
        $request = $this->getRequest();
        
        $post = $request->getPost();
        if ($post->get("HanhDong") != null && $post->get("MaSoThue") != null) {
            /* @var $nguoinopthue nguoinopthue */
            $nguoinopthue = $this->getEntityManager()->find("Application\Entity\\nguoinopthue", $post->get("MaSoThue"));
            
            if ($nguoinopthue != null) {
                $kq = null;
                /* @var $nguoinopthueModel nguoinopthueModel */
                $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
                
                if($post->get("HanhDong")=="CapNhatNganh")
                {
                    $kq = new ketqua();
                    
                    // sửa nntnganh củ
                    $nntnganhOld = $nguoinopthue->getNNTNganh();
                    $today = (new \DateTime())->format('Y-m-d');
                    $nntnganhOld->setThoiGianKetThuc($today);
                    $kqOld = $nguoinopthueModel->merge($nntnganhOld);
                    
                    
                    // Thêm 1 nntnganh, set thoigianketthuc = null
                    $nganh = $this->getEntityManager()->find("Application\Entity\\nganh", $post->get('MaNganh'));
                    $nntnganhNew = new NNTNganh();
                    $nntnganhNew->setNganh($nganh);
                    $nntnganhNew->setThoiGianBatDau($today);
                    $nntnganhNew->setThoiGianKetThuc(null);
                    $nntnganhNew->setNguoinopthue($nguoinopthue);
                    $kqNew = $nguoinopthueModel->them($nntnganhNew);
                    
                    
                    
                    
                    if($kqNew->getKq()==true && $kqOld->getKq()==true){
                        $kq->setKq(true);
                        $kq->appentMessenger("Cập nhật ngành thành công !");
                        $kq->appentMessenger("Thông tin cập nhật như sau: ");
                        $kq->appentMessenger('Từ ');
                        $kq->appentMessenger($nntnganhOld->getNganh()->getMaNganh().' - '.$nntnganhOld->getNganh()->getTenNganh());
                        $kq->appentMessenger('thành');
                        $kq->appentMessenger($nntnganhNew->getNganh()->getMaNganh().' - '.$nntnganhNew->getNganh()->getTenNganh());
                        
                        //$nguoinopthue = new nguoinopthue();
                       // $nguoinop = $this->getEntityManager()->find("Application\Entity\\nguoinopthue", $post->get("MaSoThue"));
                        $nntnganhtest = $this->getEntityManager()->createQueryBuilder()
                                            ->select("nntnganh")
                                            ->from("Application\Entity\NNTNganh", "nntnganh")
                                            ->where("nntnganh.nguoinopthue = ?1")
                                            ->andWhere("nntnganh.ThoiGianKetThuc is null")
                                            ->setParameter(1, $nguoinopthue)
                                                ->getQuery()
                                                ->getSingleResult();
                        $nguoinopthue->getNNTNganhs()->add($nntnganhtest);
                        //$nguoinopthue->setNNTNganhs($nntnganhtest);
                    }else{
                        $kq->setKq(false);
                        $kq->appentMessenger($kqNew->getMessenger());
                        $kq->appentMessenger($kqOld->getMessenger());
                    }
                    
                    
                    
                }
                else if($post->get("HanhDong")=="CapNhatCanBoQuanLy")
                {
                    
                }
                else if($post->get("HanhDong")=="CapNhatDiaChoKD")
                {
                
                }
                
                else if($post->get("HanhDong")=="CapNhatTTCoBan")
                {
                
                }
                
                
                
                
                return array(
                    'kq' => ($kq==null ? '':$kq),
                    'nguoinopthue' => $nguoinopthue
                );
            }
        }
        
        return $this->redirect()->toRoute("quanlynguoinopthue/default", array(
            "controller" => "Nguoinopthue",
            "action" => "index"
        ));
    }

    /**
     * trả về chuổi ajax, danh sách user thuộc đội thuế do đội trưởng quản lý
     *
     * @return \Zend\Mvc\Controller\Response
     */
    public function laydanhsachcbtAction()
    {
        if ($this->getUser()->getLoaiUser() == 3) {
            
            $user = $this->getEntityManager()
                ->createQueryBuilder()
                ->select(array(
                "user"
            ))
                ->from("Application\Entity\user", "user")
                ->where("user.coquanthue = ?1")
                ->andWhere("user not in(" . $this->getEntityManager()
                ->createQueryBuilder()
                ->select("user1")
                ->from("Application\Entity\user", "user1")
                ->where("user1 = ?3")
                ->getDQL() . ")")
                ->setParameter(1, $this->getUser()
                ->getCoquanthue())
                ->setParameter(3, $this->getUser())
                ->getQuery()
                ->getArrayResult();
            echo json_encode($user);
        }
        
        return $this->response;
    }

    public function laydanhsachnganhAction()
    {
        $MaNganhCu = $this->getRequest()->getQuery()->get("MaNganhCu");
        
        $nganhs = $this->getEntityManager()
            ->createQueryBuilder()
            ->select("nganh")
            ->from("Application\Entity\\nganh", "nganh")
            ->where("nganh not in (".$this->getEntityManager()->createQueryBuilder()
                                    ->select("nganh1")
                                    ->from("Application\Entity\\nganh", "nganh1")
                                    ->where("nganh1.MaNganh = ?1")
                                    
                                    ->getDQL().")")
            
                            ->setParameter(1, $MaNganhCu)
            ->getQuery()
            ->getArrayResult();
        echo json_encode($nganhs);
        return $this->getResponse();
    }

    public function testAction()
    {
        $model = new nguoinopthueModel($this->getEntityManager());
        var_dump($model->CallPro('test', array(
            'a' => 123,
            'b' => '',
            'c' => '1221as21d sa'
        )));
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