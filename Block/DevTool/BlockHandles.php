<?php
/**
 * Copyright © 2015 CedCommerce. All rights reserved.
 */
namespace Ced\DevTool\Block\DevTool;
use Ced\DevTool\Block\DevTool;
class BlockHandles extends DevTool
{
   
	/**
	 * Function for getting block handle details
	 * @return array
	 */
    public function getBlockHandleDetails()
    {
        return $this->_devToolHelper->getBlockDetails();
    }

}