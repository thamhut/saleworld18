<?php

namespace App\Modules\Fontend\Controllers;

use App\Models\CmsCategory;
use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductClone;
use App\Models\Slider;
use App\Models\Website;
use OE\Helper;

class IndexController extends BaseController {

	/**
	 * Route action
	 */
	public function indexAction() {
		$data = array();
	    $this->pageTitle = $this->_('Dashboard');
		$this->assets->addCss('/public/skin/common/libs/bxslider/jquery.bxslider.css');
		$this->assets->addCss('/public/skin/common/libs/scrollbar/jquery.mCustomScrollbar.css');
		$this->assets->addJs('/public/skin/common/libs/bxslider/jquery.bxslider.js');
		$this->assets->addJs('/public/skin/common/libs/scrollbar/jquery.mCustomScrollbar.concat.min.js');
		$data['slider'] = Slider::find();
		$data['category'] = CmsCategory::find(array('status > 0', 'columns'=>'id, title, slug, id_parent'))->toArray();
		$data['shop'] = Website::find();
		$data['productuser'] = Product::find(array('order'=>'_id desc','limit'=>10));
		$data['productclone'] = ProductClone::find(array('order'=>'_id desc','limit'=>10));
		$this->view->setVars($data);

    }

	public function aboutAction(){
		$this->pageTitle = $this->_('About us');
	}

	public function faqAction(){
		$this->pageTitle = $this->_('About us');
		$data = array();
		$data['faq'] = Faq::find();
		$this->view->setVars($data);
	}

}