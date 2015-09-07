<?php
/**
 * Copyright © 2015 CedCommerce. All rights reserved.
 */
namespace Ced\DevTool\Block\DevTool;
use Ced\DevTool\Block\DevTool;
class Events extends DevTool
{
	/**
	 * Function for getting event and observer details
	 * @return array
	 */
    public function getEventDetails()
    {
		return  $this->_devToolHelper->getDevToolData($this->_devToolHelper->eventDetailsKey);
    }
	
	
}
