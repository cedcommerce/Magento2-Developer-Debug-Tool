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
    public function execute(\Magento\Framework\Event\Observer $observer) {
		$val = [ ];
		if (! $this->_devtoolData->getBlockStatus()) {
			foreach ( $observer->getBlock ()->getLayout()->getAllBlocks() as $block ) {
				try {
					$this->_devtoolData->addBlockInfo($block->getNameInLayout(), get_class($block),$block->getTemplateFile());
				} catch (\Exception $e) {
					continue;
				}
			}
			$this->_devtoolData->setBlockStatus();
    	}
   }
    	
        
    
}