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
