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
