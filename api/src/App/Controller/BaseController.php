<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
//use App\PsUtil\PsI18n;

class BaseController
{
    protected $logger;
    
	protected $db;
	
	protected $container;
	
	protected $default_setting_app;
	
	//public $psI18n;

    public function __construct(LoggerInterface $logger, $container)
    {
        $this->logger    = $logger;
        $this->db 		 = $container['db'];
        $this->container = $container;
        
        $this->default_setting_app = $container ['default_setting_app'];
        
        /*
        $getQueryLog = $container['db']->connection()->getQueryLog();

        $container['logger_db']->info ('QUERY: '.$getQueryLog[0]['query']);
        */
    }

    public function WriteLog($string = '')
    {
        // Sample log message
        $this->logger->info($string);
    }
    
    // Ghi Log lỗi
    public function WriteLogError($string = '', $user)
    {
        // Sample log message
        $this->logger->info ( "USER ID: " . $user->id);
		$this->logger->info ( "USER ID: " . $user->username);
		$this->logger->err($string);
    }
    
    // Ghi Log SQL
    public function WriteLogSQL($string = '', $user)
    {
        // Sample log message
        $this->logger->info ( "USER ID: " . $user->id);
		$this->logger->info ( "USER ID: " . $user->username);
		$this->logger->err($string);
    }


    // @return - String: VN or EN 
    public function getUserLanguage($user) {
        
    	if (!$user)
    		return APP_CONFIG_LANGUAGE;
    	else {
    		$app_config = json_decode($user->app_config);
    		return isset($app_config->language) ? $app_config->language : APP_CONFIG_LANGUAGE;
    	}
    }
}
