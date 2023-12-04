<?php
$logDate = new DateTime ();

return [ 
		
		'settings' => [ 
				
				'displayErrorDetails' => true, // set to false in production
				
				'addContentLengthHeader' => true,
				
				'debug' => true,
				
				'service_directories' => [ 
						
						'init' => APP_ROOT . '/configuration/init/',
						
						'dependencies' => APP_ROOT . '/configuration/dependencies/',
						
						'middlewares' => APP_ROOT . '/configuration/middleware/',
						
						'routes' => APP_ROOT . '/configuration/routes/' 
				],
				
				// Renderer settings
				
				'renderer' => [ 
						
						'template_path' => APP_ROOT . '/templates/',
						
						'template_cache' => APP_ROOT . '/templates_cache/' 
				],
				
				// Monolog settings
				'logger' => [ 
						
						'name' => 'api-app:',
						
						'path' => APP_ROOT . '/logs/' . $logDate->format ( 'Y-m-d' ) . 'app.log',
						
						'level' => Monolog\Logger::INFO 
				],
				
				// Monolog settings
				'logger_db' => [ 
						
						'name' => 'DB:',
						
						'path' => APP_ROOT . '/logs/' . $logDate->format ( 'Y-m-d' ) . '-DB.log',
						
						'level' => Monolog\Logger::DEBUG 
				],
				
				// Database settings
				'db' => [ 
						'driver' => 'mysql',
						//'host' => '103.81.87.111',
						'host' => 'localhost',
						'database' => 'thangnccom_api',
						'username' => 'thangnccom_api',
						'password' => 'Newwaytech@123',
						'charset' => 'utf8mb4',
						'collation' => 'utf8mb4_unicode_ci',
						'prefix' => '' 
				],
				
				// SMTP mail settings
				'mailer' => [ 
						'Host' => 'smtp.gmail.com',
						'SMTPAuth' => true,
						'SMTPSecure' => 'ssl',
						'Port' => 465,
						'Username' => 'kidsschool.vn@gmail.com',
						'Password' => 'nvocgrfdmglbvofv',
						'ContentType' => 'text/html',
						'path_template' => 'formmail/' 
				],
				
				// call function generator password
				
				'algorithm' => [ 
						
						'call function_render_pass' => 'sha1' 
				],
				
				// setting app
				
				'default_setting_app' => [ 
						
						'language' => [ 
								
								'VN',
								
								'EN' 
						],
						
						'style' => [ 
								
								'green',
								
								'blue',
								
								'yellow_orange' 
						] 
				]
				 
		]
		 
]
;