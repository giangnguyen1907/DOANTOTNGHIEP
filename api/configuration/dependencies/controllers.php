<?php
/** @var \Interop\Container\ContainerInterface $container */
$container = $app->getContainer ();

$container ['DefaultController'] = function ($container) use ($app){
	
	return new \App\Controller\DefaultController ( $container->get ( 'logger' ), $container->get ( 'renderer' ), $container );
};

// Nguoi dung
$container ['UserController'] = function ($container) use ($app) {
	
	return new \Api\Users\Controller\UserController ( $container->get ( 'logger' ), $container, $app );
};

// Hoc sinh
$container ['StudentController'] = function ($container) use ($app) {
	
	return new \Api\Students\Controller\StudentController ( $container->get ( 'logger' ), $container, $app );
};

// giao vien
$container ['TeacherController'] = function ($container) use ($app) {
	
	return new \Api\PsMembers\Controller\TeacherController ( $container->get ( 'logger' ), $container, $app );
};

// thong bao
$container ['CmsNotificationController'] = function ($container) use ($app) {
	
	return new \Api\PsCmsNotifications\Controller\CmsNotificationController($container->get('logger'),$container, $app);

};

/*
// Chat
$container['ChatController'] = function($container){
	
	return new \Api\PsChatController\Controller\ChatController($container->get('logger'),$container);
	
};*/

// Chat
$container['CmsChatController'] = function($container)use ($app){
	
	return new \Api\PsCmsChat\Controller\CmsChatController($container->get('logger'),$container, $app);
	
};

//Album
$container['AlbumController'] = function($container)use ($app){

    return new \Api\Albums\Controller\AlbumController($container->get('logger'),$container, $app);

};

// dan do
$container['AdviceController'] = function($container)use ($app){

    return new \Api\PsAdvices\Controller\AdviceController($container->get('logger'),$container, $app);

};

// Xin nghi hoc
$container['OffSchoolController'] = function($container)use ($app){

    return new \Api\PsOffSchool\Controller\OffSchoolController($container->get('logger'),$container, $app);

};

// Xin nghi hoc
$container['PsCmsArticlesController'] = function($container)use ($app){

    return new \Api\PsCmsArticles\Controller\PsCmsArticlesController($container->get('logger'),$container, $app);

};

$container['PsCmsProductsController'] = function($container)use ($app){

    return new \Api\PsProducts\Controller\PsCmsProductsController($container->get('logger'),$container, $app);

};

// Nhận xét
$container['ReviewController'] = function($container)use ($app){

    return new \Api\Review\Controller\ReviewController($container->get('logger'),$container, $app);

};
						
//$this->logger_db->info ('QUERY: '.$getQueryLog[0]['query']);
//$this->logger_db->info ('BINDINGS: '.$response->withJson($getQueryLog[0]['bindings']));
//$this->logger_db->info ('TIME: '.$getQueryLog[0]['time']);
