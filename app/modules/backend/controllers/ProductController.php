<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 3/30/2016
 * Time: 10:34 PM
 */

namespace App\Modules\Backend\Controllers;


use App\Helpers\String;
use App\Models\Product;
use App\Modules\Backend\Forms\ProductForm;
use App\Modules\Backend\Grids\ProductGrid;

class ProductController extends BaseController
{
    public function initialize(){
        $this->assets->addJs('/skin/backend/default/js/product.js');
    }

    public function indexAction(){
        $user = $this->session->get('auth');
        $_SESSION['user'] = $user;
        $this->pageTitle = $this->_("Product management");
        $product = new Product();
        $params = $this->request->get();
        if(isset($params)){
            $_SESSION['product'] = $params;
        }
        $_SESSION['product']['page'] = isset($_SESSION['product']['page']) && $_SESSION['product']['page'] > 0 ? $_SESSION['product']['page'] : 1;
        $product = $product->getProduct();
        $grid = new ProductGrid();
        $grid->set($product['data'], $product['totalData'], $_SESSION['product']['page'], 50, '/backend/product');
        $this->view->setVars(array(
            'grid' => $grid
        ));
    }

    public function ajaxAction(){
        $product = new Product();
        $params = $this->request->get();
        if(isset($params)){
            $_SESSION['product'] = $params;
        }
        $_SESSION['product']['page'] = isset($_SESSION['product']['page']) && $_SESSION['product']['page'] > 0 ? $_SESSION['product']['page'] : 1;
        $product = $product->getProduct();
        $grid = new ProductGrid();
        $grid->set($product['data'], $product['totalData'], $_SESSION['product']['page'], 50, '/backend/product');
        return $grid->render();
    }

    public function addAction(){
        $this->assets->addJs('/skin/common/libs/ckeditor/ckeditor.js');
        $this->assets
            ->addCss('/skin/common/libs/bxslider/jquery.bxslider.css');
        $this->assets
            ->addJs('/skin/common/libs/bxslider/jquery.bxslider.js');
        $userLogin = $this->session->get('auth');
        $this->pageTitle = $this->_("Register product");
        $model = new Product();
        $arrImage = array();
        if($this->request->get('id')) {
            $model = $model->findFirst(array(
                array(
                    '_id' => new \MongoId($this->request->get('id'))
                )
            ));
            $arrImage = $model->image;
        }
        $form = new ProductForm($model);
        if($this->request->isPost()) {
            $arrImage = array();
            $image = $_POST['image'];
            if(isset($image) && is_array($image)) {
                foreach ($image as $item) {
                    $image = json_decode($item);
                    if (isset($image->des)) {
                        $arrImage[] = $image->des[0];
                    }
                }
                if($form->isValid($_POST)) {
                    $data_insert = $form->getValues();
                    $data_insert['uid'] = (int)$userLogin['id_acl_role'];
                    $data_insert['image'] = $arrImage;
                    $data_insert['slug'] = String::slug($data_insert['title'] );
                    foreach($data_insert as $k=>$v)
                    {
                        if($k == 'idcate' || $k == 'status'){
                            $v = (int)$v;
                        }
                        if($k == 'oldprice' || $k == 'newprice'){
                            $v = (float)$v;
                        }
                        $model->$k = $v;
                    }
                    $model->created_at = date('Y-m-d H:i:s');
                    if($model->save()) {
                        $this->flashSession->success($this->_("Register product successfully"));
                        return $this->redirect('index');
                    } else {
                        $this->flashSession->error($this->_("Create product error"));
                    }
                }
            }
        }

        $this->view->setVars(array(
            'form' => $form, 'image' => $arrImage
        ));
    }

    public function updateAction(){

    }
}