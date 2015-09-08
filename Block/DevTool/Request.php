<?php
namespace Ced\DevTool\Block\DevTool;
class Request extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \Ced\DevTool\Helper\Data
     */
	 protected $_devToolHelper;
     public $_getVariables;
     public $_postVariables;
    
	 
    /**
     * @param \Ced\DevTool\Block\Context $context
     */
    public function __construct( \Ced\DevTool\Block\Context $context, \Magento\Framework\App\RequestInterface $request)
    {
        $this->_devToolHelper = $context->getDevToolHelper();
        $this->_getVariables = $request->getParams();
        parent::__construct($context);
        
    }
    /**
     * Retrun all the details related to Module/Controller/Action on a particular page load
     */
    public function getRequestDetails()
    {
		return $this->_devToolHelper->getDevToolData($this->_devToolHelper->_requestKey);
    }
    /**
     * Retrun all the details related to GET ad POST variables on a particular page load
     */
    public function getPostGetVariableDetails()
    {
        return $this->_getVariables;
    }
    
    
	
}
