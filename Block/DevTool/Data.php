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

use Ced\DevTool\Block\DevTool;
class Data extends DevTool
{
	public $_getVariables;
    public $_postVariables;

	/**
	 * Function for toggle path hint button tittle
	 * @return string
	 */
	public function getTogglePathHintTitle(){
		if($this->getConfigValue('dev/debug/template_hints_storefront'))
			return __('Disable Path Hint');
		else
			return __('Enable Path Hint');
	}
	
	/**
	 * Function for toggle block hint button tittle
	 * @return string
	 */
	public function getToggleBlockHintTitle(){
		if($this->getConfigValue('dev/debug/template_hints_blocks'))
			return __('Disable Block Hint');
		else
			return __('Enable Block Hint');
	}


	/**
     * Retrun all the details related to Module/Controller/Action on a particular page load
     */
    public function getQueryDetails()
    {
		return $this->_devToolHelper->getQueryDetails();
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
        return $this->_request->getParams();
    }

    /**
	 * Function for getting preferences details
	 * @return array
	 */
	public function getPreferencesDetails(){
		return  $this->_devToolHelper->getDevToolData($this->_devToolHelper->preferencesKey);
	}

	/**
     * Retrun all the details related to Models on a particular page load
     */
    public function getModelDetails()
    {
		return $this->_devToolHelper->getDevToolData($this->_devToolHelper->_modelKey);
    }


	/**
	 * Function for getting event and observer details
	 * @return array
	 */
    public function getEventDetails()
    {
		return  $this->_devToolHelper->getDevToolData($this->_devToolHelper->eventDetailsKey);
    }

    /**
     * Retrun all the details related to collection on a particular page load
     */
    public function getCollectionDetails()
    {
		return $this->_devToolHelper->getDevToolData($this->_devToolHelper->_collectionKey);
    }

	/**
	 * Function for getting block handle details
	 * @return array
	 */
    public function getBlockHandleDetails()
    {
        return $this->_devToolHelper->getBlockDetails();
    }
	
}
