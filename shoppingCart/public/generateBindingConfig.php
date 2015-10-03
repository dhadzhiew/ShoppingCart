<?php

require_once '../../mvc/Loader.php';

\DH\Mvc\Loader::registerNamespace('DH\Mvc', realpath('../../mvc'));
\DH\Mvc\Loader::registerNamespace('DH\ShoppingCart', realpath('../../ShoppingCart'));
\DH\Mvc\Loader::registerAutoLoad();

function scanModels($path, &$models)
{
    $dirs = scandir($path);

    foreach ($dirs as $entity) {
        if ($entity == '.' || $entity == '..') {
            continue;
        }

        $entity = $path . DIRECTORY_SEPARATOR . $entity;

        if (is_dir($entity)) {
            scanModels($entity, $models);
        } else {
            if (preg_match('/\.php/', $entity)) {
                $models[] = $entity;
            }
        }
    }
}


function scanControllers($path, &$controllers)
{
    $dirs = scandir($path);

    foreach ($dirs as $entity) {
        if ($entity == '.' || $entity == '..') {
            continue;
        }

        $entity = $path . DIRECTORY_SEPARATOR . $entity;
        if (is_dir($entity)) {
            scanControllers($entity, $controllers);
        } else {
            if (preg_match('/Controller\.php/', $entity)) {
                $controllers[] = $entity;
            }
        }
    }
}

function createCustomRoutes($namespaces)
{
    scanControllers('..\..', $controllers);

    $lines = '<?php'.PHP_EOL;
    foreach($controllers as $controller) {
        foreach($namespaces as $namespace) {
            if(!preg_match('/(base|Front)/i', $controller)) {
                preg_match_all('/(\w+)Controller/', $controller, $resultController);
                $controllerName=  strtolower($resultController[1][0]);
                preg_match_all('/(\w+)\.php/', $controller, $result);
                $className = $result[1][0];
                $className = $namespace. '\\' . $className;

                if(class_exists($className)) {
                    $reflection = new ReflectionClass($className);
                    foreach($reflection->getMethods() as $method) {
                        $lines .= '$config["'.$namespace.'"]["'.$controllerName.'"]["'.$method->getName().'"]';
                        foreach($method->getParameters() as $param) {
                            $class = $param->getClass();
                            if($class) {
                                foreach($class->getProperties() as $prop) {
                                    $lines .= '';
                                    print_r(parseAttribute($prop->getDocComment()));
                                }
                            }
                        }
                    }

                    break;
                }
            }
        }
echo $lines;
//        file_put_contents('../config/customRoutes.php', $lines);
    }
}

function parseAttribute($doc)
{
    $isMatched = preg_match_all('/\[(\w+)(\((.+?)\))?\]/', $doc, $result);
    if(!$isMatched){
        return false;
    }



    $return = array(
        'method' => $result[1][0],
    );
    if($result[3][0]) {
        $params = explode(',', $result[3][0]);
        $return['params'] = $params;
    }

    return $return;
}

createCustomRoutes(
    array(
        'DH\ShoppingCart\Controllers',
        'DH\ShoppingCart\Controllers\Admin'
    ));