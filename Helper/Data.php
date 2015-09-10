<?php
/**
 * Copyright © 2015 CedCommerce. All rights reserved.
 */
namespace Ced\DevTool\Helper;
use Magento\Framework\App\Filesystem\DirectoryList;
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
	
	/**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
	
	/**
     * @param \Magento\Framework\App\Helper\Context $context
	 * @param \Magento\Framework\Registry $coreRegistry
	 * @param \Magento\Framework\ObjectManager\ConfigInterface $config
     */
	public function __construct(\Magento\Framework\App\Helper\Context $context ,\Magento\Framework\Registry $coreRegistry,\Magento\Framework\ObjectManager\ConfigInterface $config,
	\Magento\Framework\ObjectManagerInterface $objectManager
	) {
		$this->_coreRegistry = $coreRegistry;
		$this->_preferences=$config->getPreferences();
		$this->_objectManager=$objectManager;
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
		$this->log($data);
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
		$observerKey=$eventName.'-'.$configuration['name'].'-'.$configuration['method'];
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
        return $this->getDevToolData($this->_statusKey);
    }

	/**
     * Set developerTool Block registry
     *
     * @return bool
     */
    public function setDeveloperRegistryBlock($data)
    {
		$this->addDevToolData($this->_statusKey,$data);
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
			return false;
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
	
	/**
     * logging dev tool data in file
     * @return bool
     */
	public function log($data){
		$logDir=$this->_objectManager->get('Magento\Framework\Filesystem')
							->getDirectoryRead(DirectoryList::LOG);
		$log=fopen($logDir->getAbsolutePath().'devTool.log','w');
		fwrite($log,print_r($data,true));
		return true;
		
	}
}