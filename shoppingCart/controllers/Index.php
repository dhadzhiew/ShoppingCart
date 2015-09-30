<?php

namespace Controllers;

class Index
{
    public function Index2()
    {
        $view = \DH\Mvc\View::getInstance();
        $view->appendToLayout('body', 'index');
        $view->appendToLayout('body2', 'admin.index');
        $view->display('layouts.default', array('gosho' => 'penka'));
    }
}