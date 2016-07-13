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

namespace Ced\DevTool\Model;

use Magento\Framework\Event\ObserverInterface;

class Everymodelload  implements ObserverInterface
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

	
    public function execute(\Magento\Framework\Event\Observer $observer)
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
}