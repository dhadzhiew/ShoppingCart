<?php
$config['default_controller'] = 'Index';
$config['default_method'] = 'Index2';
$config['namespaces']['Controllers'] = 'D:\Programs\xampp\htdocs\PHP Web Development\IndividualProject\trunk\shoppingCart\controllers';

$config['session']['autostart'] = true;
$config['session']['type'] = 'database';
$config['session']['name'] = '_sess';
$config['session']['lifetime'] = 3600;
$config['session']['path'] = '/';
$config['session']['domain'] = '';
$config['session']['secure'] = false;
$config['session']['dbConnection'] = 'default';
$config['session']['dbTable'] = 'sessions';

return $config;