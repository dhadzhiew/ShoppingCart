<?php

namespace DH\Mvc\ViewHelpers;


class UploadForm
{
    public static function create($uploadTo, $uploadFieldName)
    {
        $form = new Form('post', $uploadTo);
        $form->setAttribute('enctype', 'multipart/form-data')
            ->addElement(
                (new Input('file', $uploadFieldName)))
            ->addElement(
                (new Input('submit', 'submit'))
            );

        return $form->render();
    }
}