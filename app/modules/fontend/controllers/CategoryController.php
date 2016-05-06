<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/15/2016
 * Time: 11:21 AM
 */

namespace App\Modules\Fontend\Controllers;


use App\Helpers\View;
use App\Models\CmsCategory;
use App\Models\Product;
use App\Models\ProductClone;
use App\Models\Website;

class CategoryController extends BaseController
{
    public function indexAction($slug=''){
        $data = array();
        $limit = 12;
        $data['shop'] = Website::find();
        $data['category'] = CmsCategory::find(array('status > 0', 'columns'=>'id, title, slug, id_parent'))->toArray();
        $params = $this->request->get();
        $data['category_level'] = $category = CmsCategory::findFirst(array("slug = '$slug'"));
        if(empty($category)){
            die;
        }
        if($category->id_parent != 0){
            $data['c_l_2'] = CmsCategory::findFirst(array("id = '$category->id_parent'"));
            if(!empty($data['c_l_2'])){
                $data['level2']['title'] = $data['c_l_2']->title;
                $data['level2']['slug'] = $data['c_l_2']->slug;
                if($data['c_l_2']->id_parent != 0){
                    $parent = $data["c_l_2"]->id_parent;
                    $data['c_l_3'] = CmsCategory::findFirst(array("id = '$parent'"));
                    if(!empty($data['c_l_3'])){
                        $data['level3']['title'] = $data['c_l_3']->title;
                        $data['level3']['slug'] = $data['c_l_3']->slug;
                    }
                }
            }

        }
        $data['c'] = $category->id;
        $data['t'] = isset($params['t']) ? $params['t'] : 'clone';
        $data['p'] = isset($params['p']) && (int)$params['p'] > 0 ? (int)$params['p'] : 1;
        $skip_p = $limit*($data['p'] - 1);
        $skip_pu = $skip_ps = 0;
        $skip_pu = $data['t'] != 'clone'?$skip_p:0;
        $skip_ps = $data['t'] == 'clone'?$skip_p:0;
        $data['s'] = isset($params['s'])?$params['s']:'';
        $data['url_user'] = $params['_url'].'?t=user';
        $data['url_clone'] = $params['_url'].'?t=clone&s='.$data['s'];
        $data['url_clone_s'] = $params['_url'].'?t=clone&p='.$data['p'];
        $data['shopfilter'] = isset($params['shopfilter']) && (int)$params['shopfilter'] > 0 ? (int)$params['shopfilter'] : 0;
        $data['productuser'] = Product::find(array('conditions' => array('idcate' => (int)$data['c']),'sort'=>array('_id'=>-1),'limit'=>$limit, 'skip'=>$skip_pu));
        $data['pagination_user'] = View::next_prev(count($data['productuser']), $data['p'], $data['url_user'], $limit);
        if($data['s'] != '') {
            $data['productclone'] = ProductClone::find(array('conditions' => array('idcate' => (int)$data['c'], 'shopId' => (int)$data['s']), 'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_ps));
        }else{
            $data['productclone'] = ProductClone::find(array('conditions' => array('idcate' => (int)$data['c']), 'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_ps));
        }
        $data['pagination_clone'] = View::next_prev(count($data['productclone']), $data['p'], $data['url_clone'], $limit);

        $this->view->setVars($data);
    }

    public function shopAction($slug1='', $slug=''){
        $data = array();
        $limit = 24;
        $data['category'] = CmsCategory::find(array('status > 0', 'columns'=>'id, title, slug, id_parent'))->toArray();
        $params = $this->request->get();
        $data['category_level'] = $category = CmsCategory::findFirst(array("slug = '$slug'"));

        $data['s'] = $slug1;
        $data['p'] = isset($params['p']) && (int)$params['p'] > 0 ? (int)$params['p'] : 1;
        $skip_p = $limit*($data['p'] - 1);
        $data['url_clone'] = $params['_url'];
        $data['url_clone_s'] = $params['_url'].'?p='.$data['p'];
        if(!empty($category) && $category->id_parent != 0){
            $data['c_l_2'] = CmsCategory::findFirst(array("id = '$category->id_parent'"));
            if(!empty($data['c_l_2'])){
                $data['level2']['title'] = $data['c_l_2']->title;
                $data['level2']['slug'] = $data['c_l_2']->slug;
                if($data['c_l_2']->id_parent != 0){
                    $parent = $data["c_l_2"]->id_parent;
                    $data['c_l_3'] = CmsCategory::findFirst(array("id = '$parent'"));
                    if(!empty($data['c_l_3'])){
                        $data['level3']['title'] = $data['c_l_3']->title;
                        $data['level3']['slug'] = $data['c_l_3']->slug;
                    }
                }
            }
            $data['c'] = $category->id;
            $data['productclone'] = ProductClone::find(array('conditions' => array('idcate' => (int)$data['c'], 'shopId' => (int)$slug1), 'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_p));
        }else{
            $data['productclone'] = ProductClone::find(array('conditions' => array('shopId' => (int)$slug1), 'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_p));
        }
        $data['pagination_clone'] = View::next_prev(count($data['productclone']), $data['p'], $data['url_clone'], $limit);
        $this->view->setVars($data);
    }

    public function productAction($slug=''){
        $data = array();
        $limit = 24;
        $data['category'] = CmsCategory::find(array('status > 0', 'columns'=>'id, title, slug, id_parent'))->toArray();
        $params = $this->request->get();
        $data['category_level'] = $category = CmsCategory::findFirst(array("slug = '$slug'"));
        $data['p'] = isset($params['p']) && (int)$params['p'] > 0 ? (int)$params['p'] : 1;
        $skip_p = $limit*($data['p'] - 1);
        $data['url_clone'] = $params['_url'];
        $data['url_clone_s'] = $params['_url'].'?p='.$data['p'];
        if(!empty($category)){
            if($category->id_parent != 0){
                $data['c_l_2'] = CmsCategory::findFirst(array("id = '$category->id_parent'"));
                if(!empty($data['c_l_2'])){
                    $data['level2']['title'] = $data['c_l_2']->title;
                    $data['level2']['slug'] = $data['c_l_2']->slug;
                    if($data['c_l_2']->id_parent != 0){
                        $parent = $data["c_l_2"]->id_parent;
                        $data['c_l_3'] = CmsCategory::findFirst(array("id = '$parent'"));
                        if(!empty($data['c_l_3'])){
                            $data['level3']['title'] = $data['c_l_3']->title;
                            $data['level3']['slug'] = $data['c_l_3']->slug;
                        }
                    }
                }

            }
            $data['c'] = $category->id;
            $data['productclone'] = Product::find(array('conditions' => array('idcate' => (int)$data['c']), 'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_p));
            $data['pagination_clone'] = View::next_prev(count($data['productclone']), $data['p'], $data['url_clone'], $limit);
        }
        else{
            $data['productclone'] = Product::find(array('sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_p));
            $data['pagination_clone'] = View::next_prev(count($data['productclone']), $data['p'], $data['url_clone'], $limit);
        }
        $this->view->setVars($data);
    }
}