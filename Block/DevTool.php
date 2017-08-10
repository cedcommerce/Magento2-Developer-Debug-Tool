<?php 

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_DevTool
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CedCommerce (http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
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
     * @var \Ced\DevTool\Model\Config
     */
    protected $_request;
	 
    public $_storeManager;
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
        $this->_request = $context->getRequest();
        $this->_storeManager= $context->getStoreManager();
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
	
	/**
	 * Function for getting front controller url for given router path
	 * @param string $routePath
	 * @return string
	 */
	public function getFrontUrl($routePath)
	{
		return $this->_storeManager->getStore()->getBaseUrl().$routePath;
	}
	
	
}
