<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class nguoinopthue extends \Application\Entity\nguoinopthue implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'MaSoThue', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'TenHKD', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'SoCMND', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'DiaChiCT', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'SoGPKD', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'NgayCapMST', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'ThoiDiemBDKD', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'NgheKD', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'chungtus', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'thongtinnnt', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'thues', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'miengiamthue', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'ngungnghis', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'sonos', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'thuemonbais', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'usernnts', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'NNTNganhs', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'dukienthues', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'dukientruythus', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'truythus', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'dukienmbs');
        }

        return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'MaSoThue', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'TenHKD', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'SoCMND', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'DiaChiCT', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'SoGPKD', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'NgayCapMST', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'ThoiDiemBDKD', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'NgheKD', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'chungtus', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'thongtinnnt', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'thues', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'miengiamthue', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'ngungnghis', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'sonos', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'thuemonbais', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'usernnts', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'NNTNganhs', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'dukienthues', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'dukientruythus', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'truythus', '' . "\0" . 'Application\\Entity\\nguoinopthue' . "\0" . 'dukienmbs');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (nguoinopthue $proxy) {
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
    public function getMaSoThue()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaSoThue', array());

        return parent::getMaSoThue();
    }

    /**
     * {@inheritDoc}
     */
    public function getTenHKD()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTenHKD', array());

        return parent::getTenHKD();
    }

    /**
     * {@inheritDoc}
     */
    public function getSoCMND()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSoCMND', array());

        return parent::getSoCMND();
    }

    /**
     * {@inheritDoc}
     */
    public function getDiaChiCT()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDiaChiCT', array());

        return parent::getDiaChiCT();
    }

    /**
     * {@inheritDoc}
     */
    public function getSoGPKD()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSoGPKD', array());

        return parent::getSoGPKD();
    }

    /**
     * {@inheritDoc}
     */
    public function getNgayCapMST()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNgayCapMST', array());

        return parent::getNgayCapMST();
    }

    /**
     * {@inheritDoc}
     */
    public function getThoiDiemBDKD()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getThoiDiemBDKD', array());

        return parent::getThoiDiemBDKD();
    }

    /**
     * {@inheritDoc}
     */
    public function getNgheKD()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNgheKD', array());

        return parent::getNgheKD();
    }

    /**
     * {@inheritDoc}
     */
    public function getChungtus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getChungtus', array());

        return parent::getChungtus();
    }

    /**
     * {@inheritDoc}
     */
    public function getThongtinnnt()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getThongtinnnt', array());

        return parent::getThongtinnnt();
    }

    /**
     * {@inheritDoc}
     */
    public function getThues()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getThues', array());

        return parent::getThues();
    }

    /**
     * {@inheritDoc}
     */
    public function getMiengiamthue()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMiengiamthue', array());

        return parent::getMiengiamthue();
    }

    /**
     * {@inheritDoc}
     */
    public function getNgungnghis()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNgungnghis', array());

        return parent::getNgungnghis();
    }

    /**
     * {@inheritDoc}
     */
    public function getSonos()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSonos', array());

        return parent::getSonos();
    }

    /**
     * {@inheritDoc}
     */
    public function getThuemonbais()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getThuemonbais', array());

        return parent::getThuemonbais();
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
    public function getNNTNganhs()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNNTNganhs', array());

        return parent::getNNTNganhs();
    }

    /**
     * {@inheritDoc}
     */
    public function getDukienthues()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDukienthues', array());

        return parent::getDukienthues();
    }

    /**
     * {@inheritDoc}
     */
    public function getDukientruythus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDukientruythus', array());

        return parent::getDukientruythus();
    }

    /**
     * {@inheritDoc}
     */
    public function getTruythus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTruythus', array());

        return parent::getTruythus();
    }

    /**
     * {@inheritDoc}
     */
    public function getDukienmbs()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDukienmbs', array());

        return parent::getDukienmbs();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaSoThue($MaSoThue)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaSoThue', array($MaSoThue));

        return parent::setMaSoThue($MaSoThue);
    }

    /**
     * {@inheritDoc}
     */
    public function setTenHKD($TenHKD)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTenHKD', array($TenHKD));

        return parent::setTenHKD($TenHKD);
    }

    /**
     * {@inheritDoc}
     */
    public function setSoCMND($SoCMND)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSoCMND', array($SoCMND));

        return parent::setSoCMND($SoCMND);
    }

    /**
     * {@inheritDoc}
     */
    public function setDiaChiCT($DiaChiCT)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDiaChiCT', array($DiaChiCT));

        return parent::setDiaChiCT($DiaChiCT);
    }

    /**
     * {@inheritDoc}
     */
    public function setSoGPKD($SoGPKD)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSoGPKD', array($SoGPKD));

        return parent::setSoGPKD($SoGPKD);
    }

    /**
     * {@inheritDoc}
     */
    public function setNgayCapMST($NgayCapMST)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNgayCapMST', array($NgayCapMST));

        return parent::setNgayCapMST($NgayCapMST);
    }

    /**
     * {@inheritDoc}
     */
    public function setThoiDiemBDKD($ThoiDiemBDKD)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setThoiDiemBDKD', array($ThoiDiemBDKD));

        return parent::setThoiDiemBDKD($ThoiDiemBDKD);
    }

    /**
     * {@inheritDoc}
     */
    public function setNgheKD($NgheKD)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNgheKD', array($NgheKD));

        return parent::setNgheKD($NgheKD);
    }

    /**
     * {@inheritDoc}
     */
    public function setChungtus($chungtus)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setChungtus', array($chungtus));

        return parent::setChungtus($chungtus);
    }

    /**
     * {@inheritDoc}
     */
    public function setThongtinnnt($thongtinnnt)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setThongtinnnt', array($thongtinnnt));

        return parent::setThongtinnnt($thongtinnnt);
    }

    /**
     * {@inheritDoc}
     */
    public function setThues($thues)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setThues', array($thues));

        return parent::setThues($thues);
    }

    /**
     * {@inheritDoc}
     */
    public function setMiengiamthue($miengiamthue)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMiengiamthue', array($miengiamthue));

        return parent::setMiengiamthue($miengiamthue);
    }

    /**
     * {@inheritDoc}
     */
    public function setNgungnghis($ngungnghis)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNgungnghis', array($ngungnghis));

        return parent::setNgungnghis($ngungnghis);
    }

    /**
     * {@inheritDoc}
     */
    public function setSonos($sonos)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSonos', array($sonos));

        return parent::setSonos($sonos);
    }

    /**
     * {@inheritDoc}
     */
    public function setThuemonbais($thuemonbais)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setThuemonbais', array($thuemonbais));

        return parent::setThuemonbais($thuemonbais);
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
    public function setNNTNganhs($NNTNganhs)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNNTNganhs', array($NNTNganhs));

        return parent::setNNTNganhs($NNTNganhs);
    }

    /**
     * {@inheritDoc}
     */
    public function setDukienthues($dukienthues)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDukienthues', array($dukienthues));

        return parent::setDukienthues($dukienthues);
    }

    /**
     * {@inheritDoc}
     */
    public function setDukientruythus($dukientruythus)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDukientruythus', array($dukientruythus));

        return parent::setDukientruythus($dukientruythus);
    }

    /**
     * {@inheritDoc}
     */
    public function setTruythus($truythus)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTruythus', array($truythus));

        return parent::setTruythus($truythus);
    }

    /**
     * {@inheritDoc}
     */
    public function setDukienmbs($dukienmbs)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDukienmbs', array($dukienmbs));

        return parent::setDukienmbs($dukienmbs);
    }

}
