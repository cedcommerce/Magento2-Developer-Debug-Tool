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
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{	
	/**
     * Developer Data key
     *
     * @var string
     */
    protected $_dataKey = 'developer_tool_data';
	
	/**
     * Data key
     *
     * @var string
     */
    protected $_statusKey = 'developer_tool_block';
	
	/**
     * Model Details Key
     *
     * @var string
     */
	public $_modelKey = 'model_details';
	
	/**
     * Collection Details Key
     *
     * @var string
     */
    public $_collectionKey = 'collection_details';
	
	/**
     * Request Details Key
     *
     * @var string
     */
    public $_requestKey = 'request_details';
	
	/**
     * Preferences Key
     *
     * @var string
     */
    public $preferencesKey = 'preferences';
	
	/**
     * Event Details Key
     *
     * @var string
     */
    public $eventDetailsKey = 'event_details';
	
	/**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
	
	/**
     * DeveloperTool Session
     *
     * @var \Ced\DevTool\Helper\Data
     */
    protected $_devToolSession=null;
	
	/**
     * preferences
     *
     */
    protected $_preferences=null;
	
	/**
     * DeveloperTool Session
     *
     * @var \Ced\DevTool\Helper\Data
     */
    protected $_session=null;
	
	protected $_resource;

	
	/**
     * @param \Magento\Framework\App\Helper\Context $context
	 * @param \Magento\Framework\Registry $coreRegistry
	 * @param \Magento\Framework\ObjectManager\ConfigInterface $config
     */
	public function __construct(\Magento\Framework\App\Helper\Context $context ,\Magento\Framework\Registry $coreRegistry,\Magento\Framework\ObjectManager\ConfigInterface $config,\Magento\Backend\App\ConfigInterface $backendConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\ObjectManagerInterface $objectManager
        ) {
		$this->_coreRegistry = $coreRegistry;
		$this->_preferences=$config->getPreferences();
		$this->_resource = $resource;
		$this->addDevToolData($this->preferencesKey,$this->_preferences);
		
		parent::__construct($context);
	}
	
	/**
     * Getting developerTool registry
     *
     * @return array
     */
	public function getDeveloperRegistry()
	{
		
	   if($data=$this->_coreRegistry->registry($this->_dataKey)) 
		{
		
			return $data;
		}
		return array();
	}
	
	/**
     * Set developerTool registry
     *
     * @return bool
     */
	public function setDeveloperRegistry($data)
	{
		
	   if($this->_coreRegistry->registry($this->_dataKey)) 
		{
			$this->_coreRegistry->unregister($this->_dataKey);
		}
		$this->_coreRegistry->register($this->_dataKey,$data);
		return true;
	}
	
	/**
     * Setting observer details
     *
     * @param array $configuration
     * @param string $eventName
     * @return bool
     */
	public function setObserverDetails($configuration,$eventName)
	{
		$method=isset($configuration['method'])?$configuration['method']:'';
		$observerKey=$eventName.'-'.$configuration['name'].'-'.$method;
		if(isset($data[$this->eventDetailsKey][$observerKey]))
		{
			$count=$data[$this->eventDetailsKey][$observerKey]['count']+1;
		}
		else
		{
			$count=1;
		}
		if($data=$this->getDeveloperRegistry()){
			
			
			$data[$this->eventDetailsKey][$observerKey]=array('configuration'=>$configuration,'event'=>$eventName,'count'=>$count);	
		}
		else
		{	
			$data=array();
			$data[$this->eventDetailsKey][$observerKey]=array('configuration'=>$configuration,'event'=>$eventName,'count'=>$count);	
		}
		
		$this->setDeveloperRegistry($data);
		return true;
		
	}
	
	/**
     * Getting developerTool registry
     *
     * @return array
     */
    public function getDeveloperRegistryBlock()
    {

        if($data=$this->_coreRegistry->registry($this->_statusKey))
        {
            return $data;
        }
        return array();
    }

	/**
     * Set developerTool Block registry
     *
     * @return bool
     */
    public function setDeveloperRegistryBlock($data)
    {

        if($this->_coreRegistry->registry($this->_statusKey))
        {
            $this->_coreRegistry->unregister($this->_statusKey);
        }
        $this->_coreRegistry->register($this->_statusKey,$data);
        return true;
    }
	
	/**
     * Function for adding data to specific key in developer registry
     *
     * @return bool
     */
	public function addDevToolData($key,$data)
    {
   	    if($dataRegistry=$this->getDeveloperRegistry()){
			$dataRegistry[$key]=$data;			
		}
		else
		{	
			$dataRegistry=array();
			$dataRegistry[$key]=$data;	
		}
		
		$this->setDeveloperRegistry($dataRegistry);
		return true;
    }
	
	/**
     * Function for getting data of specific key in developer registry
     *
     * @return bool
     */
    public function getDevToolData($key)
    {

   	    if($data=$this->getDeveloperRegistry()){
			if(isset($data[$key]))
				return $data[$key];
			else
				return array();
		}
		else
		{	
			return array();
		}
		return true;
    }

    /**
     * Setting block,handle,template info for current request
     *
     * @param array $handle
     * @param string $class
     * @param string $template
     * @return bool
     */
	public function addBlockInfo($handle,$class,$template){
        
        if($data=$this->getDeveloperRegistry()){
            $data['block_details'][]=array('handle'=>$handle,'class'=>$class,'template'=>$template);
        }
        else
        {
            $data=array();
            $data['block_details'][]=array('handle'=>$handle,'class'=>$class,'template'=>$template);
        }

        $this->setDeveloperRegistry($data);
        return true;
    }

    /**
     * Retrieving block details
     * @return array
     */
    public function getBlockDetails()
    {
        
        if($data=$this->getDeveloperRegistry()){
			if(isset($data['block_details']))
				return $data['block_details'];
        }
        return array();
    }

    /**
     * Public function get query details
     * @return arrray
     */
    function getQueryDetails(){
    
        $sqlProfiler = $this->_resource->getConnection('read')->getProfiler();
        if($sqlProfiler->getEnabled())
            $profiles = $sqlProfiler->getQueryProfiles();
        else
            $profiles = [];
        return $profiles;
        
    }

    /**
     * Fetching block status
     * @return bool
     */
    public function setBlockStatus(){
        $this->setDeveloperRegistryBlock(true);
        return true;
    }

    /**
     * Retrieve block status
     * @return bool
     */
    public function getBlockStatus(){
        if($data=$this->getDeveloperRegistryBlock()){
            return $data;
        }
        return false;
    }
	
	
  
}