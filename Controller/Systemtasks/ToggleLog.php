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

namespace Ced\DevTool\Controller\Systemtasks;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class ToggleLog extends \Magento\Framework\App\Action\Action
{
	/**
     * System Tasks Toggle Block Hint Action
     */
	public function execute()
    {
		$this->setDeveloperIp();
		$path='dev/debug/template_hints_blocks';
		$config=$this->_objectManager->get('Ced\DevTool\Model\Config');
		$currentConfigValue=$config->getCurrentStoreConfigValue($path);
		$value=$currentConfigValue==1?0:1;
		$config->setCurrentStoreConfigValue($path,$value);
		
	}
	
	/**
     * Function for setting developer ip in allowed ip restriction
     *
     * @return bool
     */
	public function setDeveloperIp(){
	
		$path='dev/restrict/allow_ips';
		$config=$this->_objectManager->get('Ced\DevTool\Model\Config');
		$currentConfigValue=$config->getCurrentStoreConfigValue($path);
		$remoteIp=$_SERVER['REMOTE_ADDR'];
		if($currentConfigValue==null){
			$config->setCurrentStoreConfigValue($path,$remoteIp);
		}
		else
		{
			if (strpos($currentConfigValue,$remoteIp) !== false) {
				return true;
			}
			else
			{
				$config->setCurrentStoreConfigValue($path,$currentConfigValue.','.$remoteIp);
			}
		}
		return true;
		
	}
}