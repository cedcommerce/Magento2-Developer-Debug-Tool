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

class Everycollectionload  implements ObserverInterface
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
     * Register All the Collection related details on the event core_collection_abstract_load_before
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		$collection = $observer->getCollection();          

        $collectionArray = array();
        $collectionArray['sql'] = $collection->getSelectSql(true);
        $collectionArray['type'] = 'Flat';
        $collectionArray['class'] = get_class($collection);
        $this->collections[] = $collectionArray;
        $this->_devtoolData->addDevToolData($this->_devtoolData->_collectionKey , $this->collections);
        
    }
}