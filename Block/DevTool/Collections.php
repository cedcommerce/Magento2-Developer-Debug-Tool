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

namespace Ced\DevTool\Block\DevTool;

class Collections extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \Ced\DevTool\Helper\Data
     */
	 protected $_devToolHelper;
     
	 
    /**
     * @param \Ced\DevTool\Block\Context $context
     */
    public function __construct( \Ced\DevTool\Block\Context $context)
    {
        $this->_devToolHelper = $context->getDevToolHelper();
        parent::__construct($context);
	
    }
    /**
     * Retrun all the details related to collection on a particular page load
     */
    public function getCollectionDetails()
    {
		return $this->_devToolHelper->getDevToolData($this->_devToolHelper->_collectionKey);
    }
    
	
}
