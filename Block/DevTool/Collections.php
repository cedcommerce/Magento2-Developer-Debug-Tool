<?php
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
