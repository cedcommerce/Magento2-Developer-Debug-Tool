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

namespace Ced\DevTool\Model\Source\Updates;

class Type extends \Magento\Framework\Model\AbstractModel
{
	const TYPE_PROMO            = 'PROMO';
	const TYPE_NEW_RELEASE      = 'NEW_RELEASE';
	const TYPE_UPDATE_RELEASE   = 'UPDATE_RELEASE';
	const TYPE_INFO             = 'INFO';
	const TYPE_INSTALLED_UPDATE = 'INSTALLED_UPDATE';
	
	
	public function toOptionArray()
	{
		return array(
			array('value' => self::TYPE_INSTALLED_UPDATE, 'label' => __('Only Installed Extension(s) Updates')),
			array('value' => self::TYPE_UPDATE_RELEASE,   'label' => __('All Extensions Updates')),
			array('value' => self::TYPE_NEW_RELEASE,      'label' => __('New Releases')),
			array('value' => self::TYPE_PROMO,            'label' => __('Special Offers')),
			array('value' => self::TYPE_INFO,             'label' => __('Other Information'))
		);
	}
	
	/**
     * Retrive all attribute options
     *
     * @return array
     */
    public function getAllOptions()
    {
    	return $this->toOptionArray();
	}
	
	
	/**
	 * Returns label for value
	 * @param string $value
	 * @return string
	 */
	public function getLabel($value)
	{
		$options = $this->toOptionArray();
		foreach($options as $v){
			if($v['value'] == $value){
				return $v['label'];
			}
		}
		return '';
	}
	
	/**
	 * Returns array ready for use by grid
	 * @return array 
	 */
	public function getGridOptions()
	{
		$items = $this->getAllOptions();
		$out = array();
		foreach($items as $item){
			$out[$item['value']] = $item['label'];
		}
		return $out;
	}
}
