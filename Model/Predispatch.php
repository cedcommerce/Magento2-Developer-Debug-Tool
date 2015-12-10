<?php
/**
 * Copyright © 2015 Cedcommerce. All rights reserved.
 */
namespace Ced\DevTool\Model;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
class Predispatch  implements ObserverInterface
{
    protected $_feed;
	protected $_backendAuthSession;
	protected $_objectManager;
	
    public function __construct (
        \Ced\DevTool\Model\Feed $_feed,
		\Magento\Framework\ObjectManagerInterface $objectInterface,
		\Magento\Backend\Model\Auth\Session $backendAuthSession
    ) {
       $this->_feed = $_feed;
       $this->_backendAuthSession = $backendAuthSession;
	   $this->_objectManager = $objectInterface;
    }

	
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		if ($this->_backendAuthSession->isLoggedIn()) {
			$this->_feed->checkUpdate();
	
		}
		return $this;
    }
}