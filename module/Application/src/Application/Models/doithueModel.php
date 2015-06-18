<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\doithue;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;

class doithueModel extends baseModel
{

    /**
     * Tìm doithue theo Mã đội thuế
     *
     * @param string $MaDoiThue            
     * @return doithue|NULL
     *
     */
    public function findById($MaDoiThue)
    {
        try {
            return $this->em->find('Application\Entity\nguoinopthue', $MaDoiThue);
        } catch (\Exception $e) {
            var_dump($e);
            return null;
        }
    }

    /**
     * Lấy danh sách doithue
     *
     * @return doithue[]|NULL
     *
     */
    public function getDanhSach()
    {
        try {
            $dql = 'select u from Application\Entity\user u';
            $q = $this->em->createQuery($dql);
            return $q->getResult();
        } catch (\Exception $e) {
            var_dump($e);
            return null;
        }
    }

    /**
     * sửa 1 doithue trong csdl
     * return:
     * Trả về array bao gồm các option:
     * + ketqua: true|false - kết quả của quá trình
     * + mesenger: string - thông tin trang thái
     *
     * @param doithue $doithue            
     * @return array
     *
     */
    public function sua($DoiThueNew)
    {
        try {
            
            if ($DoiThueNew instanceof doithue) {
                
                $this->em->merge($DoiThueNew);
                $this->em->flush();
                return true;
            }
            
            return false;
        } catch (UniqueConstraintViolationException $e1) {
            return array(
                'ketqua' => false,
                'mesenger' => 'Mã doithue đã tồn tại trong hệ thống.'
            );
        } catch (\Exception $e) {
            var_dump($e);
            return array(
                'ketqua' => false,
                'mesenger' => ''
            );
        }
    }

    /**
     * thêm 1 doithue vào csdl
     * return:
     * Trả về array bao gồm các option:
     * + ketqua: true|false - kết quả của quá trình
     * + mesenger: string - thông tin trang thái
     *
     * @param doithue $doithue            
     * @return array
     *
     */
    public function them($doithue)
    {
        try {
            if ($doithue instanceof doithue) {
                
                $this->em->persist($doithue);
                // kiem tra them thanh cong hay khong
                $check = $this->em->getUnitOfWork()->isScheduledForInsert($doithue);
                
                if ($check == true) {
                    $this->em->flush();
                    return array(
                        'ketqua' => true,
                        'mesenger' => 'Thêm đội thuế thành công.'
                    );
                }
                
                return array(
                    'ketqua' => false,
                    'mesenger' => ''
                );
            }
            return array(
                'ketqua' => false,
                'mesenger' => 'Đối số truyền vào không phải là entity doithue.'
            );
        } catch (UniqueConstraintViolationException $e1) {
            return array(
                'ketqua' => false,
                'mesenger' => 'Mã doithue đã tồn tại trong hệ thống.'
            );
        } catch (NotNullConstraintViolationException $e2) {
            
            return array(
                'ketqua' => false,
                'mesenger' => 'Vui lòng điền đầy đủ các thông tin quan trọng.'
            );
        } catch (\Exception $e) {
            var_dump($e);
            return array(
                'ketqua' => false,
                'mesenger' => 'error - vui lòng liên hệ quản trị viên'
            );
        }
    }

    /**
     * Xóa 1 doithue trong csdl
     * return:
     * Trả về array bao gồm các option:
     * + ketqua: true|false - kết quả của quá trình
     * + mesenger: string - thông tin trang thái
     *
     * @param string $id            
     * @return array
     *
     */
    public function xoa($id)
    {
        $doithue = $this->findById($id);
        try {
            
            if ($doithue instanceof doithue) {
                $this->em->remove($doithue);
                $check = $this->em->getUnitOfWork()->isScheduledForDelete($doithue);
                if ($check == true) {
                    $this->em->flush();
                    return array(
                        'ketqua' => true,
                        'mesenger' => 'Xóa không thành công - Đội thuế này đang giử liên hệ với một bảng khác'
                    );
                }
                return $check;
            }
            return false;
        } catch (ForeignKeyConstraintViolationException $e1) {
            $madoi = $doithue->getMaDoiThue();
            return array(
                'ketqua' => false,
                'mesenger' => "Không thể xóa đội thuế có mã là $madoi - Đội thuế này đang giử liên hệ với một đối tượng khác."
            );
        } catch (\Exception $e2) {
            var_dump($e2);
            $madoi = $doithue->getMaDoiThue();
            return array(
                'ketqua' => false,
                'mesenger' => "Không thể xóa đội thuế có mã là $madoi"
            );
        }
    }
}
