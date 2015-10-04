<?php
/**
 * Created by PhpStorm.
 * User: Hadzhiew
 * Date: 4.10.2015 ã.
 * Time: 10:58
 */

namespace DH\ShoppingCart\Controllers;


use DH\Mvc\BaseController;
use DH\ShoppingCart\models\ProductsModel;

class ProductController extends BaseController
{
    public function publishItem()
    {
        $productId = $this->input->get(0);

        $productModel = new ProductsModel();
        $productModel->publishUserProduct($productId, $this->session->userId);


        $this->redirect('/users/myproducts');
    }

    public function unpublishItem()
    {
        $productId = $this->input->get(0);
        $productModel = new ProductsModel();
        $productModel->unpublishUserProduct($productId, $this->session->userId);

        $this->redirect('/users/myproducts');
    }
}