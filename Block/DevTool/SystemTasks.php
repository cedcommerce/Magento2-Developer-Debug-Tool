<?php
/**
 * Copyright © 2015 CedCommerce. All rights reserved.
 */
namespace Ced\DevTool\Block\DevTool;

use Ced\DevTool\Block\DevTool;
use Magento\Framework\UrlFactory;
class SystemTasks extends DevTool
{	
	/**
	 * Function for toggle path hint button tittle
	 * @return string
	 */
	public function getTogglePathHintTitle(){
		if($this->getConfigValue('dev/debug/template_hints'))
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
}
