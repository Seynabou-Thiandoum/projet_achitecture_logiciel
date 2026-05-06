<?php
require __DIR__ . '/UserService.php';

ini_set('soap.wsdl_cache_enabled', '0');

$wsdlUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST']
        . dirname($_SERVER['SCRIPT_NAME']) . '/service.wsdl';

$server = new SoapServer($wsdlUrl, ['cache_wsdl' => WSDL_CACHE_NONE]);
$server->setClass('UserService');
$server->handle();
