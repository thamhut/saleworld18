<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 3/30/2016
 * Time: 10:34 PM
 */

namespace App\Modules\Backend\Controllers;


use App\Models\ProductClone;
use App\Modules\Backend\Forms\ProductForm;
use App\Modules\Backend\Grids\ProductGrid;

class ProductcloneController extends BaseController
{
    public function initialize(){
        $this->assets->addJs('/skin/backend/default/js/productclone.js');
    }

    public function indexAction(){
        $this->pageTitle = $this->_("Product management");
        $product = new ProductClone();
        $params = $this->request->get();
        if(isset($params)){
            $_SESSION['product'] = $params;
        }
        $_SESSION['product']['page'] = isset($_SESSION['product']['page']) && $_SESSION['product']['page'] > 0 ? $_SESSION['product']['page'] : 1;
        $product = $product->getProduct();
        $grid = new ProductGrid();
        $grid->set($product['data'], $product['totalData'], $_SESSION['product']['page'], 50, '/backend/productclone');
        $this->view->setVars(array(
            'grid' => $grid
        ));
    }

    public function ajaxAction(){
        $product = new ProductClone();
        $params = $this->request->get();
        if(isset($params)){
            $_SESSION['product'] = $params;
        }
        $_SESSION['product']['page'] = isset($_SESSION['product']['page']) && $_SESSION['product']['page'] > 0 ? $_SESSION['product']['page'] : 1;
        $product = $product->getProduct();
        $grid = new ProductGrid();
        $grid->set($product['data'], $product['totalData'], $_SESSION['product']['page'], 50, '/backend/productclone');
        return $grid->render();
    }

}