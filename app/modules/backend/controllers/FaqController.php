<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 4/23/2016
 * Time: 3:42 PM
 */

namespace App\Modules\Backend\Controllers;


use App\Modules\Backend\Forms\FaqForm;
use App\Modules\Backend\Grids\FaqGrid;
use App\Modules\Backend\Models\Faq;

class FaqController extends BaseController
{
    public function indexAction(){
        $this->pageTitle = $this->_("Faq management");
        $grid = new FaqGrid();
        $grid->run();
        $this->view->setVars(array(
            'grid' => $grid
        ));
    }

    public function updateAction(){
        $this->pageTitle = $this->_("Faq management");
        $data = array();
        $faq = new Faq();
        if($this->request->get('id')){
            $faq = Faq::findFirst('id='.$this->request->get('id'));
        }
        $data['form'] = $form = new FaqForm($faq);
        if($this->request->isPost()) {
            if($form->isValid($_POST)) {
                $input = $this->request->getPost();
                if ($faq->save($input)) {
                    $this->flashSession->success($this->_("Successfully"));
                    return $this->redirect('index');
                } else {
                    $this->flashSession->error($this->_("Error"));
                }
            }
        }

        $this->view->setVars($data);
    }
}