<?php
/**
 * Copyright © 2015 Cedcommerce. All rights reserved.
 */
namespace Ced\DevTool\Model;
class Observer
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

    /*
     *  blocks , handles and template info is saved for current request
     *  via helpers
     */
	public function setBlockname(\Magento\Framework\Event\Observer $observer)
    {
        if(!$this->_devtoolData->getBlockStatus()) {
            foreach ($observer->getBlock()->getLayout()->getAllBlocks() as $block) {
                      $this->_devtoolData->addBlockInfo($block->getNameInLayout(),get_class($block),$block->getTemplateFile());
               }
            $this->_devtoolData->setBlockStatus();
            }
        }

    public function onEveryModelLoad(\Magento\Framework\Event\Observer $observer)
    {  
        $event = $observer->getEvent();
		$object = $event->getObject();
		$key = get_class($object);
		
		if( array_key_exists($key, $this->models) ) {
			$this->models[$key]['occurences']++; 
		} else {
			$model = array();
			$model['class'] = get_class($object);
			$model['resource_name'] = $object->getResourceName();
			$model['occurences'] = 1;
			$this->models[$key] = $model;
            
		}
		$this->_devtoolData->addDevToolData($this->_devtoolData->_modelKey,$this->models);
		return $this;
    }
    /**
     * Register All the Collection related details on the event core_collection_abstract_load_before
     */
    public function onEveryCollectionLoad(\Magento\Framework\Event\Observer $observer)
    {
        $collection = $observer->getCollection();          

        $collectionArray = array();
        $collectionArray['sql'] = $collection->getSelectSql(true);
        $collectionArray['type'] = 'Flat';
        $collectionArray['class'] = get_class($collection);
        $this->collections[] = $collectionArray;
        $this->_devtoolData->addDevToolData($this->_devtoolData->_collectionKey , $this->collections);
        
    }
    /**
     * Register All the EAV Collection related details on the event eav_collection_abstract_load_before
     */
    public function onEveryEavCollectionLoad(\Magento\Framework\Event\Observer $observer)
    {
        $collection = $observer->getCollection();
        $collectionArrayEav = array();
        $collectionArrayEav['sql'] = $collection->getSelectSql(true);
        $collectionArrayEav['type'] = 'Eav';
        $collectionArrayEav['class'] = get_class($collection);
        $this->collections[] = $collectionArrayEav;
        $this->_devtoolData->addDevToolData($this->_devtoolData->_collectionKey , $this->collections);
    }
    /**
     * Register All the Controller related details on the event controller_action_postdispatch
     */
    public function onControllerCall(\Magento\Framework\Event\Observer $observer)
    {
        $action = $observer->getControllerAction();
        $actionArray = array();
        $actionArray['Controller Name'] = $action->getRequest()->getControllerName();
        $actionArray['Action Name'] = $action->getRequest()->getActionName();
        $actionArray['Module Name'] = $action->getRequest()->getRouteName();
        $actionArray['Path Info'] = $action->getRequest()->getPathInfo();
        $this->actions[] = $actionArray;
        $this->_devtoolData->addDevToolData($this->_devtoolData->_requestKey , $this->actions);
    }
}