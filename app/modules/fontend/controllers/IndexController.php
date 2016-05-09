<?php

namespace App\Modules\Fontend\Controllers;

use App\Helpers\App;
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
		$data['meta_title'] = 'saleworld18 - the site aggregate product deal, sale off of the all shop in usa';
		$data['meta_content'] = 'Website aggregate product deal, sale off of the all shop in usa, include clothes, fashion and Cosmetics, Beauty Products, Fragrances & Tools and shop forever21, levi, sephora, lacoste,...';
		$this->tag->SetTitle($this->_('Sale world'));
		$this->assets->addCss('/public/skin/common/libs/bxslider/jquery.bxslider.css');
		$this->assets->addCss('/public/skin/common/libs/scrollbar/jquery.mCustomScrollbar.css');
		$this->assets->addJs('/public/skin/common/libs/bxslider/jquery.bxslider.js');
		$this->assets->addJs('/public/skin/common/libs/scrollbar/jquery.mCustomScrollbar.concat.min.js');
		$data['slider'] = Slider::find();
		$data['category'] = CmsCategory::find(array('status > 0', 'columns'=>'id, title, slug, id_parent'))->toArray();
		$data['shop'] = Website::find(array('columns'=>'id, logo, name'));
		$data['productuser'] = Product::find(array('sort'=>array('_id'=>-1),'limit'=>10, 'columns'=>'_id, title, slug, image'));
		$data['productclone'] = ProductClone::find(array('sort'=>array('_id'=>-1),'limit'=>10, 'columns'=>'_id, title, slug, image'));
		$this->view->setVars($data);

    }

	public function aboutAction(){
		$data['meta_title'] = 'saleworld18 - the site aggregate product deal, sale off of the all shop in usa';
		$data['meta_content'] = 'Website aggregate product deal, sale off of the all shop in usa, include clothes, fashion and Cosmetics, Beauty Products, Fragrances & Tools and shop forever21, levi, sephora, lacoste,...';
		$this->tag->SetTitle($this->_('About us'));
	}

	public function faqAction(){
		$data['meta_title'] = 'saleworld18 - the site aggregate product deal, sale off of the all shop in usa';
		$data['meta_content'] = 'Website aggregate product deal, sale off of the all shop in usa, include clothes, fashion and Cosmetics, Beauty Products, Fragrances & Tools and shop forever21, levi, sephora, lacoste,...';
		$this->tag->SetTitle($this->_('FAQs'));
		$data = array();
		$data['faq'] = Faq::find();
		$this->view->setVars($data);
	}

	public function writeAction($json){
		$path = __DIR__."/../../../../public/filecache/p_2";
		$file = fopen($path,'a');
		fwrite($file,$json);
		fclose($path);
	}

	public function readAction($id){
		$path = __DIR__."/../../../../public/filecache/".$id;
		$file = fopen($path,'r');
		$json = fgets($file);
		fclose($path);
		echo $json;
	}

	public function sitemapAction()
	{
		set_time_limit(0);
		$start = array(0);
			$product = ProductClone::find(array('columns'=>'_id,slug'));
			$xmldoc = new \DOMDocument('1.0','');
			$xmldoc->formatOutput = true;

			// create root nodes
			$root = $xmldoc->createElement("urlset");
			$root->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
			$root->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
			$root->setAttribute('xsi:schemaLocation','http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

			$xmldoc->appendChild($root);
			foreach($product as $item)
			{
				$elem = $xmldoc->createElement("url");
				//
				$loc = 'http://saleworld18.com/product-clone/'.$item->slug;
				$fname = $xmldoc->createElement("loc");
				$fname->appendChild($xmldoc->createTextNode($loc));
				$elem->appendChild( $fname );
				//
				$lastmod = date('Y-m-d').'T00:00:00+07:00';
				$lname = $xmldoc->createElement("lastmod");
				$lname->appendChild($xmldoc->createTextNode($lastmod));
				$elem->appendChild($lname);
				//
				//
				$priority = '0.5';
				$_4priority = $xmldoc->createElement("priority");
				$_4priority->appendChild($xmldoc->createTextNode($priority));
				$elem->appendChild($_4priority);

				//create end root nodes
				$root->appendChild($elem);
				// create element nodes
			}
			//save file
			$xmldoc->save(__DIR__."/../../../../sitemap_detail.xml") ;


	}
	public function sitemapuAction()
	{
		set_time_limit(0);
		$start = array(0);
		$product = Product::find(array('columns'=>'_id,slug'));
		$xmldoc = new \DOMDocument('1.0','');
		$xmldoc->formatOutput = true;

		// create root nodes
		$root = $xmldoc->createElement("urlset");
		$root->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
		$root->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
		$root->setAttribute('xsi:schemaLocation','http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

		$xmldoc->appendChild($root);
		foreach($product as $item)
		{
			$elem = $xmldoc->createElement("url");
			//
			$loc = 'http://saleworld18.com/product-user/'.$item->slug;
			$fname = $xmldoc->createElement("loc");
			$fname->appendChild($xmldoc->createTextNode($loc));
			$elem->appendChild( $fname );
			//
			$lastmod = date('Y-m-d').'T00:00:00+07:00';
			$lname = $xmldoc->createElement("lastmod");
			$lname->appendChild($xmldoc->createTextNode($lastmod));
			$elem->appendChild($lname);
			//
			//
			$priority = '0.5';
			$_4priority = $xmldoc->createElement("priority");
			$_4priority->appendChild($xmldoc->createTextNode($priority));
			$elem->appendChild($_4priority);

			//create end root nodes
			$root->appendChild($elem);
			// create element nodes
		}
		//save file
		$xmldoc->save(__DIR__."/../../../../sitemap_user.xml") ;


	}

}