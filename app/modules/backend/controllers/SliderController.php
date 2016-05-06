<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 3/27/2016
 * Time: 6:14 PM
 */

namespace App\Modules\Backend\Controllers;



use App\Modules\Backend\Grids\SliderGrid;
use App\Modules\Backend\Models\Slider;

class SliderController extends BaseController
{
    public function indexAction(){
        $this->pageTitle = $this->_("Slider management");
        $grid = new SliderGrid("Slider");
        $grid->run();
        $this->view->setVars(array(
            'grid' => $grid
        ));
    }

    public function addAction(){
        $this->pageTitle = $this->_("Slider");
        $data = array();
        $slider = new Slider();
        if($this->request->get('id')){
            $slider = Slider::findFirst('id='.$this->request->get('id'));
            $data['input'] = $slider->toArray();
        }
        if($this->request->isPost()) {
            $input = $this->request->getPost();
            $url = json_decode($input['image']);
            $slider->status = $input['status'];
            $slider->url = isset($url->des[0])?$url->des[0]:$input['image'];
            if($slider->save()) {
                $this->flashSession->success($this->_("Successfully"));
                return $this->redirect('index');
            }else{
                $this->flashSession->error($this->_("Error"));
                @unlink($slider->image);
            }
        }

        $this->view->setVars($data);
    }

    public function updateAction(){
        return $this->redirect('add?id='.$this->request->get('id'));
    }

    public function viewAction(){
        return $this->redirect('add?id='.$this->request->get('id'));
    }

    public function deleteAction(){
        if($this->request->get('id')){
            if(Slider::findFirst('id='.$this->request->get('id'))->delete()){
                $this->flashSession->success($this->_("Successfully"));
            }
            return $this->redirect('index');
        }
    }
}