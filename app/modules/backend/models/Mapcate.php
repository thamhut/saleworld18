<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 3/27/2016
 * Time: 5:34 PM
 */

namespace App\Modules\Backend\Models;


use App\Helpers\Translate;

class Mapcate extends \App\Models\Mapcate
{
    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = -1;
    const STATUS_DELETED  = -2;

    public function beforeValidation(){
        $this->status = 0;
    }

    public static function listStatus() {
        return array(
            self::STATUS_ACTIVE => Translate::t('Active'),
            self::STATUS_INACTIVE => Translate::t('Inactive'),
            self::STATUS_DELETED => Translate::t('Deleted'),
        );
    }

    public static function getStatusLabel($status) {
        $listStatus = self::listStatus();
        return isset($listStatus[$status]) ? $listStatus[$status] : null;
    }

    public static function listCategory(){
        $category = new CmsCategory();
        $category = $category->find();
        $arrCate = $namecate = array();
        foreach($category as $vp){
            if($vp->id_parent > 0) {
                $parent[] = $vp->id_parent;
                $namecate[$vp->id] = $vp->title;
            }
        }
        foreach($category as $v){
            if($v->id_parent > 0) {
                $level = CmsCategory::findFirst('id='.$v->id_parent);
                if($level->id_parent > 0){
                    $level1 = CmsCategory::findFirst('id='.$level->id_parent);
                    $arrCate[$v->id] = $level1->title.'-'.$level->title.'-'.$v->title;
                }
                else{
                    $arrCate[$v->id] = $level->title.'-'.$v->title;
                }
            }else{
                if(!in_array($v->id, $parent)) {
                    $arrCate[$v->id] = $v->title;
                }
            }
        }
        return $arrCate;
    }

    public static function getCategory($idcate) {
        $listCate = self::listCategory();
        return isset($listCate[$idcate]) ? $listCate[$idcate] : null;
    }

    public static function listDomain(){
        $domain = Website::find()->toArray();
        $arrDomain = array();
        foreach($domain as $v){
            $arrDomain[$v['id']] = $v['domain'];
        }
        return $arrDomain;
    }

    public static function getDomain($iddomain) {
        $listDomain = self::listDomain();
        return isset($listDomain[$iddomain]) ? $listDomain[$iddomain] : null;
    }

    /**
     * @return
     */
    public function search() {
        $builder = $this->getModelsManager()
            ->createBuilder()
            ->columns(array('t.id','t.idcate', 't.link', 't.idweb', 't.status'))
            ->addFrom(__CLASS__, 't')
            ->where('t.status > :status:', array('status' => self::STATUS_DELETED));

        return $builder;
    }
}