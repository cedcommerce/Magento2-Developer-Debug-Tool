<?php
namespace Ced\DevTool\Block\DevTool;
class Models extends \Magento\Framework\View\Element\Template
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
     * Retrun all the details related to Models on a particular page load
     */
    public function getModelDetails()
    {
		return $this->_devToolHelper->getDevToolData($this->_devToolHelper->_modelKey);
    }
    
	
}
