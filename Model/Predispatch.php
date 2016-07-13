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