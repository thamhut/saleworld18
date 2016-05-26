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
        $limit = 24;
        $data['shop'] = Website::find(array('columns'=>'id,domain'));
        $data['category'] = CmsCategory::find(array('status > 0', 'columns'=>'id, title, slug, id_parent'))->toArray();
        $params = $this->request->get();
        $data['category_level'] = $category = CmsCategory::findFirst(array("slug = '$slug'"));
        $data['meta_title'] = 'saleworld18 - category ';
        if(empty($category)){
            die;
        }
        if($category->id_parent != 0){
            $data['c_l_2'] = CmsCategory::findFirst(array("id = '$category->id_parent'"));
            if(!empty($data['c_l_2'])){
                $data['level2']['title'] = $data['c_l_2']->title;
                $data['level2']['slug'] = $data['c_l_2']->slug;
                $data['meta_title'] = $data['meta_title'].' '.$data['level2']['title'];
                if($data['c_l_2']->id_parent != 0){
                    $parent = $data["c_l_2"]->id_parent;
                    $data['c_l_3'] = CmsCategory::findFirst(array("id = '$parent'"));
                    if(!empty($data['c_l_3'])){
                        $data['level3']['title'] = $data['c_l_3']->title;
                        $data['meta_title'] = $data['meta_title'].' '.$data['level3']['title'];
                        $data['level3']['slug'] = $data['c_l_3']->slug;
                    }
                }
            }

        }
        $data['meta_title'] = $data['meta_title'].' '.$category->title;
        $data['meta_content'] = 'Product in '.$data['meta_title'];
        $this->tag->SetTitle($this->_('Sale world | '.$category->title));
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
        $data['productuser'] = Product::find(array('conditions' => array('idcate' => (int)$data['c']),'sort'=>array('_id'=>-1),'limit'=>$limit, 'skip'=>$skip_pu,'columns'=>'slug,image, title, created_at'));
        $data['pagination_user'] = View::next_prev(count($data['productuser']), $data['p'], $data['url_user'], $limit);
        if($data['s'] != '') {
            $data['productclone'] = ProductClone::find(array('conditions' => array('idcate' => (int)$data['c'], 'shopId' => (int)$data['s']), 'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_ps, 'columns'=>'slug,image, title, created_at'));
        }else{
            $data['productclone'] = ProductClone::find(array('conditions' => array('idcate' => (int)$data['c']), 'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_ps, 'columns'=>'slug,image, title, created_at'));
        }
        $data['pagination_clone'] = View::next_prev(count($data['productclone']), $data['p'], $data['url_clone'], $limit);

        $this->view->setVars($data);
    }

    public function shopAction($slug1='', $slug=''){
        $data = array();
        $limit = 36;
        $data['category'] = CmsCategory::find(array('status > 0', 'columns'=>'id, title, slug, id_parent'))->toArray();
        $params = $this->request->get();
        $data['category_level'] = $category = CmsCategory::findFirst(array("slug = '$slug'"));
        $data['meta_title'] = 'saleworld18 - category ';
        $data['s'] = Website::findFirst(array("slug = '$slug1'"));
        $data['s'] = empty($data['s'])?$slug1:$data['s']->id;
        $data['p'] = isset($params['p']) && (int)$params['p'] > 0 ? (int)$params['p'] : 1;
        $skip_p = $limit*($data['p'] - 1);
        $data['url_clone'] = $params['_url'];
        $data['url_clone_s'] = $params['_url'].'?p='.$data['p'];
        if(!empty($category) && $category->id_parent != 0){
            $this->tag->SetTitle($this->_('Sale world | '.$category->title));
            $data['c_l_2'] = CmsCategory::findFirst(array("id = '$category->id_parent'"));
            if(!empty($data['c_l_2'])){
                $data['level2']['title'] = $data['c_l_2']->title;
                $data['meta_title'] = $data['meta_title'].' '.$data['level2']['title'];
                $data['level2']['slug'] = $data['c_l_2']->slug;
                if($data['c_l_2']->id_parent != 0){
                    $parent = $data["c_l_2"]->id_parent;
                    $data['c_l_3'] = CmsCategory::findFirst(array("id = '$parent'"));
                    if(!empty($data['c_l_3'])){
                        $data['level3']['title'] = $data['c_l_3']->title;
                        $data['meta_title'] = $data['meta_title'].' '.$data['level3']['title'];
                        $data['level3']['slug'] = $data['c_l_3']->slug;
                    }
                }
            }
            $data['meta_title'] = $data['meta_title'].' '.$slug1.' '.$category->title;
            $data['meta_content'] = 'Product in '.$data['meta_title'];
            $data['c'] = $category->id;
            $data['productclone'] = ProductClone::find(array('conditions' => array('idcate' => (int)$data['c'], 'shopId' => (int)$data['s']), 'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_p, 'columns'=>'slug,image, title, created_at'));
        }else{
            $data['meta_title'] = $data['meta_title'].' '.$category->title;
            $data['meta_content'] = 'Product in '.$data['meta_title'];
            $this->tag->SetTitle($this->_('Sale world | Category'));
            $data['productclone'] = ProductClone::find(array('conditions' => array('shopId' => (int)$data['s']), 'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_p, 'columns'=>'slug,image, title, created_at'));
        }
        $data['pagination_clone'] = View::next_prev(count($data['productclone']), $data['p'], $data['url_clone'], $limit);
        $this->view->setVars($data);
    }

    public function productAction($slug=''){
        $data = array();
        $limit = 36;
        $data['category'] = CmsCategory::find(array('status > 0', 'columns'=>'id, title, slug, id_parent'))->toArray();
        $params = $this->request->get();
        $data['category_level'] = $category = CmsCategory::findFirst(array("slug = '$slug'"));
        $data['p'] = isset($params['p']) && (int)$params['p'] > 0 ? (int)$params['p'] : 1;
        $skip_p = $limit*($data['p'] - 1);
        $data['url_clone'] = $params['_url'];
        $data['url_clone_s'] = $params['_url'].'?p='.$data['p'];
        $data['meta_title'] = 'saleworld18 - category';
        if(!empty($category)){
            $this->tag->SetTitle($this->_('Sale world | '.$category->title));
            if($category->id_parent != 0){
                $data['c_l_2'] = CmsCategory::findFirst(array("id = '$category->id_parent'"));
                if(!empty($data['c_l_2'])){
                    $data['level2']['title'] = $data['c_l_2']->title;
                    $data['meta_title'] = $data['meta_title'].' '.$data['level2']['title'];
                    $data['level2']['slug'] = $data['c_l_2']->slug;
                    if($data['c_l_2']->id_parent != 0){
                        $parent = $data["c_l_2"]->id_parent;
                        $data['c_l_3'] = CmsCategory::findFirst(array("id = '$parent'"));
                        if(!empty($data['c_l_3'])){
                            $data['level3']['title'] = $data['c_l_3']->title;
                            $data['meta_title'] = $data['meta_title'].' '.$data['level3']['title'];
                            $data['level3']['slug'] = $data['c_l_3']->slug;
                        }
                    }
                }

            }
            $data['meta_title'] = $data['meta_title'].' '.$category->title;
            $data['meta_content'] = 'Product in '.$data['meta_title'];
            $data['c'] = $category->id;
            $data['productclone'] = Product::find(array('conditions' => array('idcate' => (int)$data['c']), 'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_p, 'columns'=>'slug,image, title, created_at'));
            $data['pagination_clone'] = View::next_prev(count($data['productclone']), $data['p'], $data['url_clone'], $limit);
        }
        else{
            $data['meta_title'] = $data['meta_title'].' '.$category->title;
            $data['meta_content'] = 'Product in '.$data['meta_title'];
            $this->tag->SetTitle($this->_('Sale world | Category'));
            $data['productclone'] = Product::find(array('conditions' => array(),'sort'=>array('_id'=>-1), 'limit' => $limit, 'skip' => $skip_p, 'columns'=>'slug,image, title, created_at'));
            $data['pagination_clone'] = View::next_prev(count($data['productclone']), $data['p'], $data['url_clone'], $limit);
        }
        $this->view->setVars($data);
    }
}