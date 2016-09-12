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
			$observerConfig['method']='execute';
			$this->_devHelper->setObserverDetails($observerConfig,$eventName);
            \Magento\Framework\Profiler::stop('OBSERVER:' . $observerConfig['name']);
        }
        \Magento\Framework\Profiler::stop('EVENT:' . $eventName);
    }
}