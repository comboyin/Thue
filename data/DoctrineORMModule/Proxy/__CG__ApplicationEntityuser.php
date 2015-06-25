<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class user extends \Application\Entity\user implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'inputFilter', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'MaUser', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'LoaiUser', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'TenUser', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'Email', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'MatKhau', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'TrangThai', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'buttoannothues', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'usernnts', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'coquanthue', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'usernntDangHoatDong');
        }

        return array('__isInitialized__', 'inputFilter', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'MaUser', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'LoaiUser', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'TenUser', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'Email', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'MatKhau', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'TrangThai', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'buttoannothues', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'usernnts', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'coquanthue', '' . "\0" . 'Application\\Entity\\user' . "\0" . 'usernntDangHoatDong');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (user $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getUsernntDangHoatDong()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUsernntDangHoatDong', array());

        return parent::getUsernntDangHoatDong();
    }

    /**
     * {@inheritDoc}
     */
    public function getMaUser()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaUser', array());

        return parent::getMaUser();
    }

    /**
     * {@inheritDoc}
     */
    public function getLoaiUser()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLoaiUser', array());

        return parent::getLoaiUser();
    }

    /**
     * {@inheritDoc}
     */
    public function getTenUser()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTenUser', array());

        return parent::getTenUser();
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', array());

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function getMatKhau()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMatKhau', array());

        return parent::getMatKhau();
    }

    /**
     * {@inheritDoc}
     */
    public function getTrangThai()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTrangThai', array());

        return parent::getTrangThai();
    }

    /**
     * {@inheritDoc}
     */
    public function getButtoannothues()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getButtoannothues', array());

        return parent::getButtoannothues();
    }

    /**
     * {@inheritDoc}
     */
    public function getUsernnts()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUsernnts', array());

        return parent::getUsernnts();
    }

    /**
     * {@inheritDoc}
     */
    public function getCoquanthue()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCoquanthue', array());

        return parent::getCoquanthue();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaUser($MaUser)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaUser', array($MaUser));

        return parent::setMaUser($MaUser);
    }

    /**
     * {@inheritDoc}
     */
    public function setLoaiUser($LoaiUser)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLoaiUser', array($LoaiUser));

        return parent::setLoaiUser($LoaiUser);
    }

    /**
     * {@inheritDoc}
     */
    public function setTenUser($TenUser)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTenUser', array($TenUser));

        return parent::setTenUser($TenUser);
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($Email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', array($Email));

        return parent::setEmail($Email);
    }

    /**
     * {@inheritDoc}
     */
    public function setMatKhau($MatKhau)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMatKhau', array($MatKhau));

        return parent::setMatKhau($MatKhau);
    }

    /**
     * {@inheritDoc}
     */
    public function setTrangThai($TrangThai)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTrangThai', array($TrangThai));

        return parent::setTrangThai($TrangThai);
    }

    /**
     * {@inheritDoc}
     */
    public function setButtoannothues($buttoannothues)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setButtoannothues', array($buttoannothues));

        return parent::setButtoannothues($buttoannothues);
    }

    /**
     * {@inheritDoc}
     */
    public function setUsernnts($usernnts)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUsernnts', array($usernnts));

        return parent::setUsernnts($usernnts);
    }

    /**
     * {@inheritDoc}
     */
    public function setCoquanthue($coquanthue)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCoquanthue', array($coquanthue));

        return parent::setCoquanthue($coquanthue);
    }

    /**
     * {@inheritDoc}
     */
    public function getChucVu()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getChucVu', array());

        return parent::getChucVu();
    }

    /**
     * {@inheritDoc}
     */
    public function getInputFilter()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInputFilter', array());

        return parent::getInputFilter();
    }

    /**
     * {@inheritDoc}
     */
    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInputFilter', array($inputFilter));

        return parent::setInputFilter($inputFilter);
    }

}
