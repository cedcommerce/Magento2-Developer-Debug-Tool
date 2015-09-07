<?php
/**
 * Copyright © 2015 CedCommerce. All rights reserved.
 */
namespace Ced\DevTool\Block\DevTool;

use Ced\DevTool\Block\DevTool;
use Magento\Framework\UrlFactory;
class Preferences extends DevTool
{	
	/**
	 * Function for getting preferences details
	 * @return array
	 */
	public function getPreferencesDetails(){
		return  $this->_devToolHelper->getDevToolData($this->_devToolHelper->preferencesKey);
	}
}
