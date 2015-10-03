<?php
/**
 * Created by PhpStorm.
 * User: Hadzhiew
 * Date: 3.10.2015 ã.
 * Time: 6:49
 */

namespace DH\Mvc;


class BindingModel
{
    private static  $errorMessages = [
        'minLength' => 'The {0} must be {1} characters long.',
        'required' => 'The {0} is required.'
    ];

    public static function validate($data, $config)
    {
        $bindingModelName = array_keys($config)[0];
        $bindingModel = new $bindingModelName();

        foreach($config[$bindingModelName] as $property => $rules) {
            foreach($rules as $rule) {
                if(!is_array($rule)) {
                    if(!\DH\Mvc\Validation::$rule($data[$property])) {
                        $bindingModel->modelState = false;
                        $bindingModel->errors[] = self::parse(self::$errorMessages[$rule], array($property));
                    }
                } else {
                    $method = array_shift($rule);
                    array_unshift($rule, $data[$property]);
                    if(!call_user_func_array("DH\Mvc\Validation::$method", $rule)) {
                        $bindingModel->modelState = false;
                        array_shift($rule);
                        array_unshift($rule, $property);
                        $bindingModel->errors[] = self::parse(self::$errorMessages[$method], $rule);
                    }
                }
            }
        }


        return $bindingModel;
    }

    private static function parse($text, $data)
    {
        preg_match_all('/{(\d+)}/', $text, $result);
        foreach($result[1] as $placeholder)
        {
            $text = str_replace('{'.$placeholder.'}', $data[$placeholder], $text);
        }

        return $text;
    }
}