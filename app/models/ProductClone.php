<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 3/30/2016
 * Time: 10:29 PM
 */

namespace App\Models;


use App\Helpers\Translate;
use Phalcon\Mvc\Collection;

class ProductClone extends Collection
{
    const STATUS_ACTIVE   = 1;
    const STATUS_INACTIVE = -1;
    const STATUS_DELETED  = -2;

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
    public function getSource()
    {
        return "ProductClone";
    }

    public function getProduct()
    {
        $data = array();
        $session = $_SESSION['product'];
        $parameter = array();
        if(isset($session['title']) && $session['title']!='')
        {
            $parameter['title'] = $session['title'];
        }
        if(isset($session['uid']) && $session['uid']!= 0)
        {
            $parameter['uid'] = (int)$session['uid'];
        }
        if(isset($session['idcate']) && $session['idcate'] != 0)
        {
            $parameter['idcate'] = (int)$session['idcate'];
        }
        $data['data'] = self::find(array(
            $parameter,
            "limit" => 50,
            "skip" => (int)(($session['page']-1)*50),
            'order' =>  '_id desc',
            'columns'   =>  'title, _id, uid, idcate, status, oldprice, newprice' ,
        ));
        $data['totalData'] = self::count(array(
            $parameter,
        ));

        return $data;
    }
}