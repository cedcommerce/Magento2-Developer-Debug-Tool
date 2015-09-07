<?php
/**
 *
 * Copyright © 2015 Cedcommerce. All rights reserved.
 */
namespace Ced\DevTool\Controller\Systemtasks;

class Togglecache extends \Magento\Framework\App\Action\Action
{

	/**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\StateInterface
     */
    protected $_cacheState;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
       \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheState = $cacheState;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->resultPageFactory = $resultPageFactory;
    }
	
    /**
     * Toggle cache
     *
     */
    public function execute()
    {
		$allTypes = array_keys($this->_cacheTypeList->getTypes());
		$updatedTypes=0;
		$disable=true;
		$enable=true;
		foreach ($allTypes as $code) {
			if ($this->_cacheState->isEnabled($code) && $disable) {
				$this->_cacheState->setEnabled($code, false);
				$updatedTypes++;
				$enable=false;
				
			}
			if (!$this->_cacheState->isEnabled($code) && $enable) {
                    $this->_cacheState->setEnabled($code, true);
                    $updatedTypes++;
					$disable=false;
					
                }
			if($disable)
				$this->_cacheTypeList->cleanType($code);
		}
		if ($updatedTypes > 0) {
                $this->_cacheState->persist();
                $this->messageManager->addSuccess(__("%1 cache type(s) disabled.", $updatedTypes));
            }
        
    }
}
