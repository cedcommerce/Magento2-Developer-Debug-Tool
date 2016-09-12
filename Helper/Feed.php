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

namespace Ced\DevTool\Helper;

class Feed extends \Magento\Framework\App\Helper\AbstractHelper
{

	protected $_allowedFeedType = array();
	protected $_backendConfig;
	protected $_loader;
	protected $_objectManager;
	protected $parser;
	private $moduleList;
	private $moduleResource;
	private $driver;
	 /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;
	/**
     * @param \Magento\Framework\App\Helper\Context $context
	 * @param \Magento\Framework\Registry $coreRegistry
	 * @param \Magento\Framework\ObjectManager\ConfigInterface $config
     */
	public function __construct(\Magento\Framework\App\Helper\Context $context ,
								\Magento\Framework\Registry $coreRegistry,
								\Magento\Framework\ObjectManager\ConfigInterface $config,
								\Magento\Backend\App\ConfigInterface $backendConfig,
								\Magento\Framework\Module\ModuleListInterface $moduleList,
								\Magento\Framework\Module\ResourceInterface $moduleResource,
								\Magento\Framework\Module\ModuleList\Loader $loader,
								\Magento\Framework\Xml\Parser $parser,
								\Magento\Framework\Filesystem\Driver\File $driver,
								\Magento\Framework\App\ProductMetadataInterface $productMetadata,
								\Magento\Framework\ObjectManagerInterface $objectManager
								
								) {
		$this->_backendConfig=$backendConfig;
		$this->moduleList = $moduleList;
		$this->moduleResource = $moduleResource;
		$this->_loader = $loader;
		$this->parser = $parser;
		$this->driver = $driver;
		$this->_objectManager = $objectManager;
		$this->urlBuilder = $context->getUrlBuilder();
		$this->productMetadata   = $productMetadata;
		$this->_allowedFeedType =  explode(',',$backendConfig->getValue(\Ced\DevTool\Model\Feed::XML_FEED_TYPES));
		parent::__construct($context);
	}
	
		
	 /**
	 * Retrieve admin interest in current feed type
	 *
	 * @param SimpleXMLElement $item
	 * @return boolean $isAllowed
	 */
	public function isAllowedFeedType(\SimpleXMLElement $item) {
		$isAllowed = false;
		if(is_array($this->_allowedFeedType) && count($this->_allowedFeedType) >0) {
			$cedModules = $this->getCedCommerceExtensions();
			switch(trim((string)$item->update_type)) {
				case \Ced\DevTool\Model\Source\Updates\Type::TYPE_NEW_RELEASE :
				case \Ced\DevTool\Model\Source\Updates\Type::TYPE_INSTALLED_UPDATE :
					if (in_array(\Ced\DevTool\Model\Source\Updates\Type::TYPE_INSTALLED_UPDATE,$this->_allowedFeedType) && strlen(trim($item->module)) > 0 && isset($cedModules[trim($item->module)]) && version_compare($cedModules[trim($item->module)],trim($item->release_version), '<')===true) {
						$isAllowed = true;
						break;
					}
				case \Ced\DevTool\Model\Source\Updates\Type::TYPE_UPDATE_RELEASE :
					if(in_array(\Ced\DevTool\Model\Source\Updates\Type::TYPE_UPDATE_RELEASE,$this->_allowedFeedType) && strlen(trim($item->module)) > 0) {
						$isAllowed = true;
						break;
					}
					if(in_array(\Ced\DevTool\Model\Source\Updates\Type::TYPE_NEW_RELEASE,$this->_allowedFeedType)) {
						$isAllowed = true;
					}
					break;
				case \Ced\DevTool\Model\Source\Updates\Type::TYPE_PROMO :
					if(in_array(\Ced\DevTool\Model\Source\Updates\Type::TYPE_PROMO,$this->_allowedFeedType)) {
						$isAllowed = true;
					}
					break;
				case \Ced\DevTool\Model\Source\Updates\Type::TYPE_INFO :
					if(in_array(\Ced\DevTool\Model\Source\Updates\Type::TYPE_INFO,$this->_allowedFeedType)) {
						$isAllowed = true;
					}
					break;
			}
		}
		return $isAllowed;
	}
	/**
	 * Retrieve all the extensions name and version developed by CedCommerce
	 * @param boolean $asString (default false)
	 * @return array|string
	 */
	public function getCedCommerceExtensions($asString = false) {
		if($asString) {
			$cedCommerceModules = '';
		} else {
			$cedCommerceModules = array();
		}
		
		
		foreach($this->getAllModules() as $name=>$module) {
			$name = trim($name);
			if(preg_match('/ced_/i',$name) && isset($module['release_version'])) {
				if($asString) {
					$cedCommerceModules .= $name.':'.trim($module['release_version']).'~';
				} else {
					$cedCommerceModules[$name] = trim($module['release_version']);
				}
			}
		}
		if($asString) trim($cedCommerceModules,'~');
		return $cedCommerceModules;
	}
	/**
     * Returns module config data and a path to the module.xml file.
     *
     * Example of data returned by generator:
     * <code>
     *     [ 'vendor/module/etc/module.xml', '<xml>contents</xml>' ]
     * </code>
     *
     * @return \Traversable
     *
     * @author Josh Di Fabio <joshdifabio@gmail.com>
     */
    private function getModuleConfigs()
    {
        $modulePaths = $this->_objectManager->get('Magento\Framework\Component\ComponentRegistrar')->getPaths(\Magento\Framework\Component\ComponentRegistrar::MODULE);
        foreach ($modulePaths as $modulePath) {
            $filePath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, "$modulePath/etc/module.xml");
            yield [$filePath, $this->driver->fileGetContents($filePath)];
        }
    }

	public function getAllModules($exclude=array())
	{
		
		$result = [];
        foreach ($this->getModuleConfigs() as list($file, $contents)) {
            try {
				
                $this->parser->loadXML($contents);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    new \Magento\Framework\Phrase(
                        'Invalid Document: %1%2 Error: %3',
                        array( $file, PHP_EOL, $e->getMessage())
                    ),
                    $e
                );
            }

            $data = $this->convert($this->parser->getDom());
            $name = key($data);
            if (!in_array($name, $exclude)) {
				if(isset($data[$name]))
					$result[$name] = $data[$name];
            }
        }
		return $result;
		
	}
	 /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function convert($source)
    {
        $modules = [];
        $xpath = new \DOMXPath($source);
        /** @var $moduleNode \DOMNode */
        foreach ($xpath->query('/config/module') as $moduleNode) {
			
            $moduleData = [];
            $moduleAttributes = $moduleNode->attributes;
            $nameNode = $moduleAttributes->getNamedItem('name');
			if (strpos($nameNode->nodeValue,'Ced') === false) {
				continue;
			}
            if ($nameNode === null) {
                throw new \Exception('Attribute "name" is required for module node.');
            }
            $moduleData['name'] = $nameNode->nodeValue;
            $name = $moduleData['name'];
            $versionNode = $moduleAttributes->getNamedItem('setup_version');
            if ($versionNode === null) {
                throw new \Exception("Attribute 'setup_version' is missing for module '{$name}'.");
            }
            $moduleData['setup_version'] = $versionNode->nodeValue;
			if($moduleAttributes->getNamedItem('release_version'))
				$moduleData['release_version'] = $moduleAttributes->getNamedItem('release_version')->nodeValue;
            $moduleData['sequence'] = [];
            /** @var $childNode \DOMNode */
            foreach ($moduleNode->childNodes as $childNode) {
                switch ($childNode->nodeName) {
                    case 'sequence':
                        $moduleData['sequence'] = $this->_readModules($childNode);
                        break;
                }
            }
            // Use module name as a key in the result array to allow quick access to module configuration
            $modules[$nameNode->nodeValue] = $moduleData;
        }
        return $modules;
    }
	/**
     * Convert module depends node into assoc array
     *
     * @param \DOMNode $node
     * @return array
     * @throws \Exception
     */
    protected function _readModules(\DOMNode $node)
    {
        $result = [];
        /** @var $childNode \DOMNode */
        foreach ($node->childNodes as $childNode) {
            switch ($childNode->nodeName) {
                case 'module':
                    $nameNode = $childNode->attributes->getNamedItem('name');
                    if ($nameNode === null) {
                        throw new \Exception('Attribute "name" is required for module node.');
                    }
                    $result[] = $nameNode->nodeValue;
                    break;
            }
        }
        return $result;
    }
	
	
	/**
	 * Retrieve environment information of magento
	 * And installed extensions provided by CedCommerce
	 *
	 * @return array
	 */
	public function getEnvironmentInformation () {
		$info = array();
		$info['plateform'] = 'Magento2.x';
		$info['domain_name'] = $this->urlBuilder->getBaseUrl();
		$info['magento_edition'] = 'default';
		if(method_exists('Mage','getEdition')) $info['magento_edition'] =$this->productMetadata->getEdition();
		$info['magento_version'] = $this->productMetadata->getVersion();
		$info['php_version'] = phpversion();
		$info['feed_types'] = $this->_backendConfig->getValue(\Ced\DevTool\Model\Feed::XML_FEED_TYPES);
		$info['admin_name'] =  $this->_backendConfig->getValue('trans_email/ident_general/name');
		if(strlen($info['admin_name']) == 0) $info['admin_name'] =  $this->_backendConfig->getValue('trans_email/ident_sales/name');
		$info['admin_email'] =  $this->_backendConfig->getValue('trans_email/ident_general/email');
		if(strlen($info['admin_email']) == 0) $info['admin_email'] =  $this->_backendConfig->getValue('trans_email/ident_sales/email');
		$info['installed_extensions_by_cedcommerce'] = $this->getCedCommerceExtensions(true);
		
		return $info;
	}
}