<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/25/2016
 * Time: 3:38 PM
 */

namespace App\Modules\Backend\Controllers;


use App\Helpers\String;
use App\Models\Links;
use App\Models\Mapcate;
use App\Models\ProductClone;
use App\Modules\Backend\Models\Website;
use htmldom;

class CloneController extends BaseController
{
    public $shopId;
    public $idcate;
    public $title;
    public $content;
    public $oldprice;
    public $newprice;
    public $idp;
    public $link;
    public $image=array();
    public function testAction(){
        set_time_limit(0);
        $this->mapcate_website();
    }

    public function fix(){
        $clone = ProductClone::find(array('columns'=>'image'));
        foreach($clone as $item){
            unlink($item->image[0]);
        }
    }

    public function savedata(){
        set_time_limit(0);
        $product = new ProductClone();
        $product->shopId = (int)$this->shopId;
        $product->idcate = (int)$this->idcate;
        $product->title = $this->title;
        $product->content = html_entity_decode($this->content);
        $slug = String::slug($this->title);
        $product->slug = $slug;
        $product->oldprice = (float)$this->oldprice;
        $product->newprice = (float)$this->newprice;
        $product->image = $this->image;
        $product->uid = 1;
        $product->link = $this->link;
        $product->save();
    }

    public function mapcate_website(){
        set_time_limit(0);
        $website = Website::find();
        foreach($website as $item){
            $this->shopId = $item->id;
            $link = Mapcate::find('idweb='.$this->shopId);
            if(strpos($item->domain, 'forever21.com') !== false) {
                foreach ($link as $item_link) {
                    $this->idcate = $item_link->idcate;
                    $this->forever21($item_link->link, $item->domain);
                }
            }
            elseif(strpos($item->domain, 'levi.com') !== false){
                foreach ($link as $item_link) {
                    $this->idcate = $item_link->idcate;
                    $this->levi($item_link->link, $item->domain);
                }
            }
            elseif(strpos($item->domain, 'lacoste.com') !== false){
                foreach ($link as $item_link) {
                    $this->idcate = $item_link->idcate;
                    $this->lacoste($item_link->link, $item->domain);
                }
            }
            elseif(strpos($item->domain, 'sephora.com') !== false){
                foreach ($link as $item_link) {
                    $this->idcate = $item_link->idcate;
                    $this->sephora($item_link->link, $item->domain);
                }
            }
        }
    }

    public function sephora($urlcate, $domain){
        set_time_limit(0);
        $content = new htmldom;
        $content_cate = $content->str_get_html($this->get_fcontentByGoogle(($urlcate)));
        $json = '';
        foreach ($content_cate->find('script#searchResult') as $plink) {
            $json = strip_tags($plink);
            break;
        }
        $json = json_decode($json);
        foreach ($json->products as $product) {
            $this->idp = str_replace('P','',$product->id);
            $link = $domain.$product->product_url;
            if(!$this->checkProduct($link, $domain)){
                $this->title = $product->display_name;
                $this->oldprice = isset($product->derived_sku->list_price_max)?$product->derived_sku->list_price_max:(isset($product->derived_sku->list_price)?$product->derived_sku->list_price:0);
                $this->newprice = isset($product->derived_sku->list_price_min)?$product->derived_sku->list_price_min:(isset($product->derived_sku->sale_price)?$product->derived_sku->sale_price:0);
                $src = $domain.$product->hero_image;
                $upload = new UploadController();
                $upload = $upload->uploadImage($src);
                $this->image = array();
                $this->image[] = $upload;
                $this->link = $link;
                $this->sephora_detail($link);
            }
        }
    }

    public function sephora_detail($link){
        set_time_limit(0);
        $content = new htmldom;
        $content = $content->str_get_html($this->get_fcontentByGoogle($link));
        foreach($content->find('div.long-description') as $item){
            $this->content = htmlentities($item);
            break;
        }
        $this->savedata();
    }


    public function lacoste($urlcate, $domain){
        set_time_limit(0);
        $page = 1;
        //while(1)
        {
            $content = new htmldom;
            $content_cate = $content->str_get_html($this->get_fcontentByGoogle(($urlcate)));

            if($content_cate->find('span.product-name a')) {
                foreach ($content_cate->find('span.product-name a') as $plink) {
                    if (isset($plink->href) && !$this->checkProduct($plink->href, $domain)) {
                        $this->lacoste_detail($plink->href);
                    }
                }
            }else{
            }
        }
    }

    public function lacoste_detail($link){
        set_time_limit(0);
        $content = new htmldom;
        $content = $content->str_get_html($this->get_fcontentByGoogle($link));
        foreach($content->find('h1.sku-product-name') as $item){
            $this->title = trim(strip_tags($item));
        }
        $oldprice = $newprice = 0;
        if($content->find('span.price-standard')) {
            foreach ($content->find('span.price-standard') as $item) {
                $oldprice = str_replace('$','',strip_tags($item));
                break;
            }
        }
        if($content->find('span.price-sales')) {
            foreach ($content->find('span.price-sales') as $item) {
                $newprice = str_replace('$','',strip_tags($item));
                break;
            }
        }
        if($oldprice == $newprice){
            foreach ($content->find('div.sku-product-price') as $item) {
                $newprice = str_replace('$','',strip_tags($item));
                $newprice = explode('-',$newprice);
                $newprice = trim($newprice[0]);
                break;
            }
        }
        $this->oldprice = $oldprice;
        $this->newprice = $newprice;
        foreach ($content->find('img.dn') as $item) {
            $src = explode('?', $item->src);
            $src = $src[0];
            $upload = new UploadController();
            $upload = $upload->uploadImage($src);
            $this->image = array();
            $this->image[] = $upload;
            break;
        }

        foreach ($content->find('div.product-infos-content-more') as $item) {
            $this->content = htmlentities($item);
            break;
        }
        $this->link = $link;
        $this->savedata();
    }

    public function levi($urlcate, $domain){
        set_time_limit(0);
        $content = new htmldom;
        $start = 12;
        $content_cate = $content->str_get_html($this->get_fcontentByGoogle($urlcate));
        foreach ($content_cate->find('ul#container_results li.product-tile div.product-details a') as $plink) {
            if (isset($plink->href) && !$this->checkProduct($plink->href, $domain)) {
                $this->levi_detail($domain . $plink->href);
            }
        }
        while(1) {
            $url_s = str_replace($domain,'',$urlcate);
            $url = 'http://www.levi.com/US/en_US/includes/searchResultsScroll/';
            $url .= '?nao='.$start.'&'.'url='.$url_s;
            $content_cate = $content->str_get_html($this->get_fcontentByGoogle($url));
            if($content_cate->find('div.product-details a')){
                foreach ($content_cate->find('div.product-details a') as $plink) {
                    if (isset($plink->href) && !$this->checkProduct($plink->href, $domain)) {
                        $this->levi_detail($domain . $plink->href);
                    }
                }
                $start = $start + 12;
            }else{
                break;
            }

        }
    }

    public function levi_detail($link){
        set_time_limit(0);
        $content = new htmldom;
        $content = $this->get_fcontentByGoogle($link);
        preg_match('/(\/p\/)(.*)()/',$link,$matches);
        $id = isset($matches[2])?$matches[2]:0;
        $arr = explode("buyStackJSON = '", htmlentities($content));
        $arr = explode("'; var", $arr[1]);
        $json = html_entity_decode($arr[0]);
        $json = str_replace('\\\\','\\', $json);
        $json = str_replace('\\\'','PHAY', $json);
        $product = json_decode($json);
        $product = $product->colorid->$id;
        $this->title = html_entity_decode($product->productName);
        $upload = new UploadController();
        $upload = $upload->uploadImage($product->imageURL.$product->altViewsMain[0]);
        $this->image = array();
        $this->image[] = $upload;
        $this->oldprice = str_replace('$','',strip_tags($product->price[0]->amount));
        $this->newprice = str_replace('$','',strip_tags($product->price[1]->amount));
        $this->content = str_replace('PHAY','\'',$product->name);
        $this->link = $link;
        $this->savedata();
    }

    public function forever21($urlcate, $domain){
        set_time_limit(0);
        $page = 1;
        while(1)
        {
            $content = new htmldom;
            $content_cate = $content->str_get_html($this->get_fcontentByGoogle($urlcate.'&pagesize=60&page='.$page));
            if($content_cate->find('div.product_item div.item_pic a')) {
                foreach ($content_cate->find('div.product_item div.item_pic a') as $plink) {
                    if (isset($plink->href) && !$this->checkProduct($plink->href, $domain)) {
                        $this->forever21_detail($plink->href);
                    }
                }
                $page++;
            }else{
                break;
            }
        }
    }

    public function forever21_detail($url_detail){
        set_time_limit(0);
        $content = new htmldom;
        $content = $content->str_get_html($this->get_fcontentByGoogle($url_detail));
        foreach($content->find('h1.item_name_p') as $title){
            $this->title = trim(strip_tags($title));
            break;
        }
        foreach ($content->find('img.ItemImage') as $item) {
            $upload = new UploadController();
            $upload = $upload->uploadImage($item->src);
            $this->image = array();
            $this->image[] = $upload;
            break;
        }
        foreach ($content->find('div.price_p span.original') as $item) {
            $this->oldprice = str_replace('$','',strip_tags($item));
            break;
        }
        foreach ($content->find('div.price_p span.sale') as $item) {
            $this->newprice = str_replace('$','',strip_tags($item));
            break;
        }
        foreach ($content->find('div.pdp_description article.ac-small') as $item) {
            $this->content = htmlentities($item);
            break;
        }
        $this->link = $url_detail;
        $this->savedata();
    }

    public function checkProduct($link, $domain){
        set_time_limit(0);
        $id=0;
        $linkDetail='';
        if(strpos($link, 'forever21') !== false){
            preg_match('/(ProductID=)(.*)(&)/',$link,$matches);
            $id = isset($matches[2])?$matches[2]:0;
        }
        if(strpos($domain, 'levi.com') !== false){
            preg_match('/(\/p\/)(.*)()/',$link,$matches);
            $id = isset($matches[2])?$matches[2]:0;
        }
        if(strpos($domain, 'lacoste.com') !== false){
            $linkDetail = $link;
        }
        if(strpos($domain, 'sephora.com') !== false){
            $id = $this->idp;
        }
        if($id != 0) {
            $link_check = Links::findFirst(array(array('id_product' => (int)$id), 'domain' => $domain));
        }else{
            $link_check = Links::findFirst(array(array('link' => $linkDetail), 'domain' => $domain));
        }
        if($link_check){
            return true;
        }else{
            $add = new Links();
            $add->domain = $domain;
            $add->id_product = (int)$id;
            $add->link = $linkDetail;
            $add->save();
            return false;
        }
    }

    function get_fcontentByGoogle($url,$fields_string = array()) {
        set_time_limit(0);
        $url = str_replace("&amp;", "&", urldecode(trim($url)));
        (function_exists('curl_init')) ? '' : die('cURL Must be installed. Ask your host to enable it or uncomment extension=php_curl.dll in php.ini');
        $curl = curl_init();
        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Pragma: ";
        $header[] = "Content-type: multipart/form-data; ";

        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);

        $html = curl_exec($curl);

        curl_close($curl);

        return $html;
    }
}