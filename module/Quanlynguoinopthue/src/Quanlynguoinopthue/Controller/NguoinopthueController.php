<?php
namespace Quanlynguoinopthue\Controller;

use Application\base\baseController;
use Quanlynguoinopthue\Models\nguoinopthueModel;
use Application\Entity\nguoinopthue;
use Quanlynguoinopthue\Form\formNguoiNopThue;
use Application\Entity\coquanthue;
use Zend\Form\Element\Select;
use Application\Entity\nganh;
use Application\Entity\phuong;
use Application\Entity\ketqua;
use Zend\Form\Element;
use Application\Entity\NNTNganh;
use Application\Entity\usernnt;
use Quanlynguoinopthue\Form\formTTCoBanNNT;
use Quanlynguoinopthue\Form\ValidationTTCoBanNNT;
use Quanlynguoinopthue\Form\formThayDoiDiaChiKDNNT;
use Application\Models\phuongModel;
use Application\Models\coquanthueModel;
use Application\Entity\thongtinnnt;
use Application\Models\thongtinnntModel;
use Quanlynguoinopthue\Form\ValidationThayDoiDiaChiKD;
use Application\Unlity\Unlity;

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
        
        $dsnguoinopthue = $nguoinopthueModel->DanhSachByIdentity($this->getUser(),'object')->getObj();

        
        return array(
            'dsnnt' => $dsnguoinopthue
        );
    }

    /**
     * Thêm và sửa
     */
    public function persitAction()
    {
        $kq = new ketqua();
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
            $coquanthueModel = new coquanthueModel($this->getEntityManager());
            $quans = $coquanthueModel->DanhSachChiCucThue();
            
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
                ->getRepository('Application\Entity\nganh')
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
                            $phuongModel = new phuongModel($this->getEntityManager());
                            
                            $phuongs = $phuongModel->DanhSachPhuongThuocQuan($post->get("Quan"))->getObj();
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
                        $form->setData($this->getRequest()->getPost());
                        
                        if ($form->isValid()) {
                            
                            $selectPhuong->setValueOptions($options);
                            $nguoinopthue->setMaSoThue($post->get("MaSoThue"));
                            $nguoinopthue->setNgayCapMST($post->get("NgayCapMST"));
                            $nguoinopthue->setTenHKD($post->get("TenHKD"));
                            $nguoinopthue->setDiaChiCT($post->get("DiaChiKD"));
                            $nguoinopthue->setSoGPKD($post->get("SoGPKD"));
                            $nguoinopthue->setSoCMND($post->get("SoCMND"));
                            $nguoinopthue->setNgayCapMST($post->get("NgayCapMST"));
                            $nguoinopthue->setThoiDiemBDKD($post->get("ThoiDiemBDKD"));
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
                        else{
                            
                            $kq->setKq(false);
                            $kq->setMessenger($this->getErrorMessengerForm($form));
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
            $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $post->get("MaSoThue"));
            // formTTCoBanNNT
            $formTTCoBanNNT = new formTTCoBanNNT();
            
            $formTTCoBanNNT->get("TenHKD")->setAttribute("value", $nguoinopthue->getTenHKD());
            $formTTCoBanNNT->get("SoCMND")->setAttribute("value", $nguoinopthue->getSoCMND());
            $formTTCoBanNNT->get("DiaChiCT")->setAttribute("value", $nguoinopthue->getDiaChiCT());
            $formTTCoBanNNT->get("SoGPKD")->setAttribute("value", $nguoinopthue->getSoGPKD());
            $formTTCoBanNNT->get("NgayCapMST")->setAttribute("value", $nguoinopthue->getNgayCapMST());
            $formTTCoBanNNT->get("ThoiDiemBDKD")->setAttribute("value", $nguoinopthue->getThoiDiemBDKD());
            $formTTCoBanNNT->get("Nghe")->setAttribute("value", $nguoinopthue->getNgheKD());
            // formThayDoiDiaChiKDNNT
            $formThayDoiDiaChiKDNNT = new formThayDoiDiaChiKDNNT();
            $formThayDoiDiaChiKDNNT->get("DiaChiKD")->setAttribute("value", $nguoinopthue->getThongtinnnt()
                ->getDiaChiKD());
            $formThayDoiDiaChiKDNNT->get("ChanLe")->setAttribute("value", $nguoinopthue->getThongtinnnt()
                ->getChanLe());
            $formThayDoiDiaChiKDNNT->get("Hem")->setAttribute("value", $nguoinopthue->getThongtinnnt()
                ->getHem());
            $formThayDoiDiaChiKDNNT->get("SoNha")->setAttribute("value", $nguoinopthue->getThongtinnnt()
                ->getSoNha());
            $formThayDoiDiaChiKDNNT->get("SoNhaPhu")->setAttribute("value", $nguoinopthue->getThongtinnnt()
                ->getSoNhaPhu());
            $formThayDoiDiaChiKDNNT->get("TenDuong")->setAttribute("value", $nguoinopthue->getThongtinnnt()
                ->getTenDuong());
            
            // thêm danh sách phường vào select dựa trên Mã quận
            
            $selectPhuong = $formThayDoiDiaChiKDNNT->get('Phuong');
            $this->addDataInputSelectPhuong($selectPhuong, $nguoinopthue->getThongtinnnt()
                ->getPhuong()
                ->getCoquanthue()
                ->getChicucthue()
                ->getMaCoQuan());
            $selectQuan = $formThayDoiDiaChiKDNNT->get('Quan');
            $this->addDataInputSelectQuan($selectQuan);
            $formThayDoiDiaChiKDNNT->get("Phuong")->setAttribute("value", $nguoinopthue->getThongtinnnt()
                ->getPhuong()
                ->getMaPhuong());
            $formThayDoiDiaChiKDNNT->get("Quan")->setAttribute("value", $nguoinopthue->getThongtinnnt()
                ->getPhuong()
                ->getCoquanthue()
                ->getChicucthue()
                ->getMaCoQuan());
            
            if ($nguoinopthue != null) {
                $kq = null;
                /* @var $nguoinopthueModel nguoinopthueModel */
                $nguoinopthueModel = new nguoinopthueModel($this->getEntityManager());
                
                if ($post->get("HanhDong") == "CapNhatNganh") {
                    $kq = new ketqua();
                    $MaNganh = $post->get("MaNganh");
                    $nntnganhOld = $nguoinopthue->getNNTNganh();
                    
                    // Kiem tra Mã ngành mới có trùng với mã ngành củ không
                    if ($nntnganhOld->getNganh()->getMaNganh() != $MaNganh) {
                        // sửa nntnganh củ
                        
                        $today = (new \DateTime())->format('Y-m-d');
                        $nntnganhOld->setThoiGianKetThuc($today);
                        
                        // Thêm 1 nntnganh, set thoigianketthuc = null
                        $nganh = $this->getEntityManager()->find('Application\Entity\nganh', $post->get('MaNganh'));
                        $nntnganhNew = new NNTNganh();
                        $nntnganhNew->setNganh($nganh);
                        $nntnganhNew->setThoiGianBatDau($today);
                        $nntnganhNew->setThoiGianKetThuc(null);
                        $nntnganhNew->setNguoinopthue($nguoinopthue);
                        
                        $kqCapNhatNganh = $nguoinopthueModel->capNhatNganh($nntnganhOld, $nntnganhNew, $nguoinopthue);
                        
                        if ($kqCapNhatNganh->getKq() == true) {
                            $nguoinopthue->getNNTNganhs()->add($kqCapNhatNganh->getObj());
                            $kq->setKq(true);
                            $kq->appentMessenger($kqCapNhatNganh->getMessenger());
                        } else {
                            
                            $kq->setKq(false);
                            $kq->appentMessenger($kqCapNhatNganh->getMessenger());
                        }
                    } else {
                        $kq->setKq(false);
                        $kq->setMessenger("Ngành bạn vừa chọn trùng với ngành hiện tại mà HKD đang kinh doanh !");
                    }
                } else 
                    if ($post->get("HanhDong") == "CapNhatCanBoQuanLy") {
                        $kq = new ketqua();
                        $MaCanBo = $post->get("MaCanBo");
                        $UsernntCu = $nguoinopthue->getUsernnt();
                        
                        // Kiem tra Mã ngành mới có trùng với mã ngành củ không
                        if ($UsernntCu->getUser()->getMaUser() != $MaCanBo) {
                            // sửa $UsernntCu củ
                            
                            $today = (new \DateTime())->format('Y-m-d');
                            $UsernntCu->setThoiGianKetThuc($today);
                            
                            // Thêm 1 Usernnt, set thoigianketthuc = null
                            
                            $user = $this->getEntityManager()->find('Application\Entity\user', $post->get('MaCanBo'));
                            $UsernntNew = new usernnt();
                            $UsernntNew->setNguoinopthue($nguoinopthue);
                            $UsernntNew->setThoiGianBatDau($today);
                            $UsernntNew->setThoiGianKetThuc(null);
                            $UsernntNew->setUser($user);
                            
                            $kqCapNhatCanBo = $nguoinopthueModel->capNhatCanBoQuanLy($UsernntCu, $UsernntNew, $nguoinopthue);
                            $kq->setKq($kqCapNhatCanBo->getKq());
                            $kq->setMessenger($kqCapNhatCanBo->getMessenger());
                            if ($kqCapNhatCanBo->getKq() == true) {
                                
                                $nguoinopthue->getUsernnts()->add($kqCapNhatCanBo->getObj());
                             
                            }
                        } else {
                            $kq->setKq(false);
                            $kq->setMessenger("Cán bộ bạn vừa chọn trùng với cán bộ hiện tại đang quản lý HKD này !");
                        }
                    } else 
                        if ($post->get("HanhDong") == "CapNhatDiaChiKD") {} 

                        else 
                            if ($post->get("HanhDong") == "CapNhatTTCoBan") {
                                
                                $kq = new ketqua();
                                $TenHKD = $post->get("TenHKD");
                                $SoCMND = $post->get("SoCMND");
                                $DiaChiCT = $post->get("DiaChiCT");
                                $SoGPKD = $post->get("SoGPKD");
                                $ThoiDiemBDKD = $post->get("ThoiDiemBDKD");
                                $NgayCapMST = $post->get("NgayCapMST");
                                $Nghe = $post->get("Nghe");
                                $valida = new ValidationTTCoBanNNT();
                                $formTTCoBanNNT->setInputFilter($valida->getInputFilter());
                                $formTTCoBanNNT->setData($post);
                                if ($formTTCoBanNNT->isValid()) {
                                    $nguoinopthue->setTenHKD($TenHKD);
                                    $nguoinopthue->setSoCMND($SoCMND);
                                    $nguoinopthue->setDiaChiCT($DiaChiCT);
                                    $nguoinopthue->setSoGPKD($SoGPKD);
                                    $nguoinopthue->setThoiDiemBDKD($ThoiDiemBDKD);
                                    $nguoinopthue->setNgayCapMST($NgayCapMST);
                                    $nguoinopthue->setNgheKD($Nghe);
                                    
                                    $kq = $nguoinopthueModel->merge($nguoinopthue);
                                    
                                    if ($kq->getKq() == false) {
                                        $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $post->get("MaSoThue"));
                                    } else {
                                        $kq->setMessenger("Cập nhật thông tin cơ bản của HKD thành công !");
                                    }
                                } else {
                                    $kq->appentMessenger($this->getErrorMessengerForm($formTTCoBanNNT));
                                    $kq->setKq(false);
                                }
                            } else 
                                if ($post->get("HanhDong") == "ThayDoiDiaChiKD") {
                                    $kq = new ketqua();
                                    
                                    $DiaChiKD = $post->get("DiaChiKD");
                                    $Phuong = $post->get("Phuong");
                                    $Quan = $post->get("Quan");
                                    $ChanLe = $post->get("ChanLe");
                                    $Hem = $post->get("Hem");
                                    $SoNha = $post->get("SoNha");
                                    $SoNhaPhu = $post->get("SoNhaPhu");
                                    $TenDuong = $post->get("TenDuong");
                                    $ThoiDiemThayDoi = $post->get("ThoiDiemThayDoi");
                                    
                                    // thêm danh sách phường vào select dựa trên Mã quận
                                    
                                    $selectPhuong = $formThayDoiDiaChiKDNNT->get('Phuong');
                                    $this->addDataInputSelectPhuong($selectPhuong, $Quan);
                                    
                                    $formThayDoiDiaChiKDNNT->get("Phuong")->setAttribute("value", $Phuong);
                                    $formThayDoiDiaChiKDNNT->get("Quan")->setAttribute("value", $Quan);
                                    
                                    $formThayDoiDiaChiKDNNT->setInputFilter((new ValidationThayDoiDiaChiKD())->getInputFilter());
                                    $formThayDoiDiaChiKDNNT->setData($post);
                                    if ($formThayDoiDiaChiKDNNT->isValid()) {
                                        $thongtinnntModel = new thongtinnntModel($this->getEntityManager());
                                        $thongtinnntOld = $nguoinopthue->getThongtinnnt();
                                        if ($post->get('SubmitCapNhat') != null) {
                                            
                                            $thongtinnntOld->setDiaChiKD($DiaChiKD);
                                            $thongtinnntOld->setPhuong($this->getEntityManager()
                                                ->find('Application\Entity\phuong', $Phuong));
                                            $thongtinnntOld->setChanLe($ChanLe);
                                            $thongtinnntOld->setHem($Hem);
                                            $thongtinnntOld->setSoNha($SoNha);
                                            $thongtinnntOld->setSoNhaPhu($SoNhaPhu);
                                            $thongtinnntOld->setTenDuong($TenDuong);
                                            
                                            $thongtinnntOld->setThoiGianBatDau(Unlity::stringDateToStringDate($ThoiDiemThayDoi));
                                            
                                            $thongtinnntOld->setNguoinopthue($nguoinopthue);
                                            $thongtinnntModel->merge($thongtinnntOld);
                                            $kq->setKq(true);
                                            $kq->setKq('Cập nhật địa chỉ thành công !');
                                            
                                        } else {
                                            
                                            $thongtinnntOld->setThoiGianKetThuc(Unlity::stringDateToStringDate($ThoiDiemThayDoi));
                                            
                                            $thongtinnntNew = new thongtinnnt();
                                            $thongtinnntNew->setDiaChiKD($DiaChiKD);
                                            $thongtinnntNew->setPhuong($this->getEntityManager()
                                                ->find('Application\Entity\phuong', $Phuong));
                                            $thongtinnntNew->setChanLe($ChanLe);
                                            $thongtinnntNew->setHem($Hem);
                                            $thongtinnntNew->setSoNha($SoNha);
                                            $thongtinnntNew->setSoNhaPhu($SoNhaPhu);
                                            $thongtinnntNew->setTenDuong($TenDuong);
                                            
                                            $thongtinnntNew->setThoiGianBatDau(Unlity::stringDateToStringDate($ThoiDiemThayDoi));
                                            $thongtinnntNew->setThoiGianKetThuc(null);
                                            $thongtinnntNew->setNguoinopthue($nguoinopthue);
                                            
                                            // bắt đầu thay đổi địa chỉ kinh doanh
                                            $kq = $thongtinnntModel->ThayDoiDiaChiKD($thongtinnntOld, $thongtinnntNew, $nguoinopthue->getMaSoThue());
                                            
                                            if ($kq->getKq() == true) {
                                                
                                                $nguoinopthue->getThongtinnnts()->add((new thongtinnntModel($this->getEntityManager()))->ThongtinnntDangHoatDong($nguoinopthue->getMaSoThue())
                                                    ->getObj());
                                            }
                                        }
                                    } else {
                                        $kq->appentMessenger($this->getErrorMessengerForm($formThayDoiDiaChiKDNNT));
                                        $kq->setKq(false);
                                    }
                                }
                
                return array(
                    'kq' => ($kq == null ? '' : $kq),
                    'nguoinopthue' => $nguoinopthue,
                    'canbothue' => $nguoinopthue->getCanBoDangQuanLy(),
                    'formTTCoBanNNT' => $formTTCoBanNNT,
                    'formThayDoiDiaChiKDNNT' => $formThayDoiDiaChiKDNNT
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
                ->from('Application\Entity\user', "user")
                ->where("user.coquanthue = ?1")
                ->andWhere("user not in(" . $this->getEntityManager()
                ->createQueryBuilder()
                ->select("user1")
                ->from('Application\Entity\user', "user1")
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
        $MaNganhCu = $this->getRequest()
            ->getQuery()
            ->get("MaNganhCu");
        
        $nganhs = $this->getEntityManager()
            ->createQueryBuilder()
            ->select("nganh")
            ->from('Application\Entity\nganh', "nganh")
            ->where("nganh not in (" . $this->getEntityManager()
            ->createQueryBuilder()
            ->select("nganh1")
            ->from('Application\Entity\nganh', "nganh1")
            ->where("nganh1.MaNganh = ?1")
            ->getDQL() . ")")
            ->setParameter(1, $MaNganhCu)
            ->getQuery()
            ->getArrayResult();
        echo json_encode($nganhs);
        return $this->getResponse();
    }



    /**
     * Thêm dử liệu vào cho input select quận
     * 
     * @param Select $select            
     *
     */
    private function addDataInputSelectQuan(Select &$select)
    {
        $quans = (new coquanthueModel($this->getEntityManager()))->DanhSachChiCucThue();
        $options = $select->getValueOptions();
        foreach ($quans as $quan) {
            $items = array();
            $items['value'] = $quan->getMaCoQuan();
            $items['label'] = $quan->getTenGoi();
            array_push($options, $items);
        }
        $select->setValueOptions($options);
    }

    /**
     * Thêm dử liệu vào cho input select phường
     * 
     * @param Select $select            
     * @param string $MaChiCuc            
     *
     */
    private function addDataInputSelectPhuong(Select &$select, $MaChiCuc)
    {
        /* @var $phuongs phuong */
        $phuongs = (new phuongModel($this->getEntityManager()))->DanhSachPhuongThuocQuan($MaChiCuc)->getObj();
        
        $options = $select->getValueOptions();
        foreach ($phuongs as $phuong) {
            $items = array();
            $items['value'] = $phuong->getMaPhuong();
            $items['label'] = $phuong->getTenPhuong();
            array_push($options, $items);
        }
        $select->setValueOptions($options);
    }
    
    // ajax
    public function checkHKDStopAction()
    {
        $MaSoThue = $this->getRequest()
            ->getPost()
            ->get("MaSoThue");
        $nguoinopthue = $this->getEntityManager()->find('Application\Entity\nguoinopthue', $MaSoThue);
        $data = array(
            'check' => true
        );
        
        if ($nguoinopthue->getTrangThai() == 0) {
            $data['check'] = true;
            echo json_encode($data);
        } else {
            $data['check'] = false;
            echo json_encode($data);
        }
        return $this->response;
    }

    public function testAction()
    {
        var_dump(Unlity::CheckTodayLonHonHoacBang("28-06-2015"));
        return $this->response;
    }
    
    // ajax
    public function dsPhuongForQuanAction()
    {
        $MaCoQuan = $this->getRequest()
            ->getQuery()
            ->get('MaCoQuan');
        $ChiCucThue = $this->getEntityManager()->find('Application\Entity\coquanthue', $MaCoQuan);
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