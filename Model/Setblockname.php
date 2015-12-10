<?php
/**
 * Copyright © 2015 Cedcommerce. All rights reserved.
 */
namespace Ced\DevTool\Model;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
class Setblockname  implements ObserverInterface
{
    protected $_devtoolData;
    protected $_registry = null;
    public $models = array();
	public $collections = array();
    public $actions = array();
	
    public function __construct (
        \Ced\DevTool\Helper\Data $devtoolData,
        \Magento\Framework\Registry $registry
    ) {
		
       $this->_devtoolData = $devtoolData;
        $this->_registry = $registry;
    }

	/**
     *  blocks , handles and template info is saved for current request
     *  via helpers
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
	   if(!$this->_devtoolData->getBlockStatus()) {
            foreach ($observer->getBlock()->getLayout()->getAllBlocks() as $block) {
                      $this->_devtoolData->addBlockInfo($block->getNameInLayout(),get_class($block),$block->getTemplateFile());
               }
            $this->_devtoolData->setBlockStatus();
            }
    }
	
        
    
}