<?php
$config['default_controller'] = 'Home';
$config['default_method'] = 'Index';
$config['namespaces']['Controllers'] = 'C:\Users\Hadzhiew\Dropbox\IndividualProject\trunk\shoppingCart\controllers';

$config['session']['autostart'] = true;
$config['session']['type'] = 'native';
$config['session']['name'] = '_sess';
$config['session']['lifetime'] = 3600;
$config['session']['path'] = '/';
$config['session']['domain'] = '';
$config['session']['secure'] = false;
$config['session']['dbConnection'] = 'default';
$config['session']['dbTable'] = 'sessions';

//$config['displayExceptions'] = true;
$config['namespaces']['DH\ShoppingCart'] = '../../shoppingCart';

return $config;