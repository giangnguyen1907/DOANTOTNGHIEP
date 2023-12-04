<?php
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

//$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'prod', false);

//die("He thong dang nang cap. Xin hay quay lai vao ngay mai");

$configuration = ProjectConfiguration::getApplicationConfiguration('backend', 'prod', false);

sfContext::createInstance($configuration)->dispatch();
