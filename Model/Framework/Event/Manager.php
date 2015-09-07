<?php
/**
 * Copyright © 2015 Cedcommerce. All rights reserved.
 */
namespace Ced\DevTool\Model\Framework\Event;

class Manager extends \Magento\Framework\Event\Manager
{
   
   
    /**
     * Events cache
     *
     * @var array
     */
    protected $_events = [];

    /**
     * Event invoker
     *
     * @var InvokerInterface
     */
    protected $_invoker;

    /**
     * Event config
     *
     * @var ConfigInterface
     */
    protected $_eventConfig;
	
	/**
     * DeveloperTool Helper
     *
     * @var \Ced\DevTool\Helper\Data
     */
    protected $_devHelper;
	
    /**
     * @param InvokerInterface $invoker
     * @param ConfigInterface $eventConfig
     */
    public function __construct(\Magento\Framework\Event\InvokerInterface $invoker, \Magento\Framework\Event\ConfigInterface $eventConfig,
	\Ced\DevTool\Helper\Data $helper)
    {
        $this->_invoker = $invoker;
        $this->_eventConfig = $eventConfig;
		$this->_devHelper =$helper;
    }
	public function dispatch($eventName, array $data = [])
    {
        \Magento\Framework\Profiler::start('EVENT:' . $eventName, ['group' => 'EVENT', 'name' => $eventName]);
        foreach ($this->_eventConfig->getObservers($eventName) as $observerConfig) {
            $event = new \Magento\Framework\Event($data);
            $event->setName($eventName);
            $wrapper = new \Magento\Framework\Event\Observer();
            $wrapper->setData(array_merge(['event' => $event], $data));		
            \Magento\Framework\Profiler::start('OBSERVER:' . $observerConfig['name']);
            $this->_invoker->dispatch($observerConfig, $wrapper);
			$this->_devHelper->setObserverDetails($observerConfig,$eventName);
            \Magento\Framework\Profiler::stop('OBSERVER:' . $observerConfig['name']);
        }
        \Magento\Framework\Profiler::stop('EVENT:' . $eventName);
    }
}