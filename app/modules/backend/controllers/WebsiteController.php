<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/22/2016
 * Time: 11:45 AM
 */

namespace App\Modules\Backend\Controllers;


use App\Modules\Backend\Forms\WebsiteForm;
use App\Modules\Backend\Grids\WebsiteGrid;
use App\Modules\Backend\Models\Website;
use htmldom;

class WebsiteController extends BaseController
{
    public function indexAction(){
        $this->pageTitle = $this->_("Website management");
        $grid = new WebsiteGrid("Website");
        $grid->run();
        $this->view->setVars(array(
            'grid' => $grid
        ));
    }

    public function addAction(){
        $userLogin = $this->session->get('auth');
        $this->pageTitle = $this->_("Register website");
        $model = new Website();
        if($this->request->get('id')) {
            $model = $model->findFirst('id=' . $this->request->get('id'));
        }
        $form = new WebsiteForm($model);
        if($this->request->isPost()) {
            if($form->isValid($_POST)) {
                if(isset($model->uid) && $model->uid != $userLogin['id'])
                {
                    $this->flashSession->success($this->_("Can not access!"));
                    return $this->redirect('index');
                }
                $data_insert = $form->getValues();
                $data_insert['uid'] = $userLogin['id'];
                $content = new htmldom;
                $content = $content->str_get_html($this->file_get_contents_curl($data_insert['domain']));
                /*foreach($content->find('link') as $link){
                    if(strtolower($link->rel) == 'shortcut icon'){
                        if(strpos($link->href, 'http') === false){
                            $data_insert['logo'] = $data_insert['domain'].'/'.$link->href;
                        }else {
                            $data_insert['logo'] = $link->href;
                        }
                        break;
                    }
                }*/
                if($model->save($data_insert)) {
                    $this->flashSession->success($this->_("Register website successfully"));
                    return $this->redirect('index');
                } else {
                    $this->flashSession->error($this->_("Create website error"));
                }
            }
        }

        $this->view->setVars(array(
            'form' => $form
        ));
    }

    public function updateAction(){
        return  $this->redirect('add?id='.$this->request->get('id'));
    }

    public function deleteAction(){
        $userLogin = $this->session->get('auth');
        if($this->request->get('id') && $userLogin['id_acl_role'] == 1){
            if(Website::findFirst('id='.$this->request->get('id'))->delete()){
                $this->flashSession->success($this->_("Successfully"));
            }
            return $this->redirect('index');
        }
    }

}