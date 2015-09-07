<?php
/**
 * Copyright © 2015 CedCommerce. All rights reserved.
 */
namespace Ced\DevTool\Block;
use Magento\Framework\UrlFactory;
class DevTool extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \Ced\DevTool\Helper\Data
     */
	 protected $_devToolHelper;
	 
	 /**
     * @var \Magento\Framework\Url
     */
	 protected $_urlApp;
	 
	 /**
     * @var \Ced\DevTool\Model\Config
     */
    protected $_config;
	 
    /**
     * @param \Ced\DevTool\Block\Context $context
	 * @param \Magento\Framework\UrlFactory $urlFactory
     */
    public function __construct( \Ced\DevTool\Block\Context $context,
								UrlFactory $urlFactory
	)
    {
        $this->_devToolHelper = $context->getDevToolHelper();
		$this->_config = $context->getConfig();
        $this->_urlApp=$urlFactory->create();
		parent::__construct($context);
	
    }
	
	/**
	 * Function for getting event details
	 * @return array
	 */
    public function getEventDetails()
    {
		return  $this->_devToolHelper->getEventDetails();
    }
	
	/**
     * Function for getting current url
	 * @return string
     */
	public function getCurrentUrl(){
		return $this->_urlApp->getCurrentUrl();
	}
	
	/**
     * Function for getting controller url for given router path
	 * @param string $routePath
	 * @return string
     */
	public function getControllerUrl($routePath){
		
		return $this->_urlApp->getUrl($routePath);
	}
	
	/**
     * Function for getting current url
	 * @param string $path
	 * @return string
     */
	public function getConfigValue($path){
		return $this->_config->getCurrentStoreConfigValue($path);
	}
	
	/**
     * Function canShowDevTool
	 * @return bool
     */
	public function canShowDevTool(){
		$isEnabled=$this->getConfigValue('devtool/module/is_enabled');
		if($isEnabled)
		{
			$allowedIps=$this->getConfigValue('devtool/module/allowed_ip');
			 if(is_null($allowedIps)){
				return true;
			}
			else {
				$remoteIp=$_SERVER['REMOTE_ADDR'];
				if (strpos($allowedIps,$remoteIp) !== false) {
					return true;
				}
			}
		}
		return false;
	}
	
}
