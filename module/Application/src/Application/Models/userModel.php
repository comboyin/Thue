<?php
namespace Application\Models;

use Application\base\baseModel;
use Application\Entity\user;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;

class userModel extends baseModel
{

    /**
     * Tìm user theo Mã user
     *
     * @param string $MaUser            
     * @return Application\Entity\user|NULL
     *
     */
    public function findById($MaUser)
    {
        try {
            return $this->em->find('Application\Entity\user', $MaUser);
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * Lấy danh sách user
     *
     * @return user[]|NULL
     *
     */
    public function getDanhSach()
    {
        try {
            /*
             * $dql = 'select u from Application\Entity\user u';
             * $q = $this->em->createQuery($dql);
             * return $q->getResult();
             */
            return $this->em->getRepository('Application\Entity\user')->findAll();
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * Sửa 1 user trong csdl
     * return:
     * ==null: Lỗi
     * ==true || false: Thành công
     * ==
     *
     * @param user $UserNew            
     * @return null|boolean
     *
     */
    public function sua($UserNew)
    {
        try {
            
            if ($UserNew instanceof user) {
                
                $userMerge = $this->em->merge($UserNew);
                $this->em->flush();
                
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * Thêm 1 user vào csdl
     * return:
     *  Trả về array bao gồm các option:
     *         + ketqua: true|false - kết quả của quá trình
     *         + mesenger: string - thông tin trang thái 
     *
     * @param user $user            
     * @return array
     *
     */
    public function them($user)
    {
        try {
            
            if ($user instanceof user) {
                // md5 mat khau
                $user->setMatKhau(md5($user->getMatKhau()));
                $UserMerge = $this->em->persist($user);
                // kiem tra them thanh cong hay khong
                $check = $this->em->getUnitOfWork()->isScheduledForInsert($user);
                
                if ($check == true) {
                    $this->em->flush();
                    return array(
                        'ketqua' => $check,
                        'mesenger' => 'Thêm user thành công !'
                    );
                }
            }else {
                return array(
                    'ketqua' => false,
                    'mesenger' => 'Đối số truyền vào không phải là entity user.'
                );
            }
          
        } catch (UniqueConstraintViolationException $e1) {
            return array(
                'ketqua' => false,
                'mesenger' => 'Tên user hoặc email đã tồn tại trong hệ thống.'
            );
        }
        catch (NotNullConstraintViolationException $e2) {
            var_dump($e2);
            return array(
                'ketqua' => false,
                'mesenger' => 'Vui lòng điền đầy đủ các thông tin quan trọng.'
            );
        }
        
        catch (\Exception $e3) {
            var_dump($e1);
           return array(
                'ketqua' => false,
                'mesenger' => 'Hệ thống đã xãy ra lỗi, vui lòng liên hệ quản trị viên.'
            ); 
        }
    }

    /**
     * return null if xoa khong thanh cong
     * neu khac null xoa thanh cong
     * return:
     * ==null: lỗi
     * ==false : xoa khong thanh cong
     * ==true : xoa thanh cong
     *
     * @param string $id            
     * @return null|Application\Entity\user|Application\Entity\user[]
     */
    public function xoa($id)
    {
        try {
            $user = $this->findById($id);
            if ($user instanceof user) {
                $this->em->remove($user);
                $check = $this->em->getUnitOfWork()->isScheduledForDelete($user);
                var_dump($check);
                if ($check == true) {
                    $this->em->flush();
                    return array(
                        'ketqua' => $check,
                        'mesenger' => 'Xóa user thành công !'
                    );
                }
                return $check;
            }
            else {
                return array(
                    'ketqua' => false,
                    'mesenger' => 'Đối số truyền vào không phải là entity user.'
                );
            }
        } catch (\Exception $e) {
            var_dump($e);
            echo $e->getMessage();
            return null;
        }
    }
}
