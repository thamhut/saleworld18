<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 3/22/2016
 * Time: 11:30 PM
 */

namespace App\Modules\Backend\Models;

use App\Helpers\Translate;
use App\Models\Website as BaseWebsite;
use Phalcon\Session\Adapter\Files as Session;

class Website extends BaseWebsite
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

    /**
     * @return
     */
    public function search() {
        $session = new Session();
        $userLogin = $session->get('auth');
        if($userLogin['id_acl_role'] != 1){
            $builder = $this->getModelsManager()
                ->createBuilder()
                ->columns(array('t.id', 't.name', 't.domain', 't.status'))
                ->addFrom(__CLASS__, 't')
                ->where('t.status > :status: AND t.uid=:uid:', array('status' => self::STATUS_DELETED, 'uid' => $userLogin['id']));
        }else {
            $builder = $this->getModelsManager()
                ->createBuilder()
                ->columns(array('t.id', 't.name', 't.domain', 't.status'))
                ->addFrom(__CLASS__, 't')
                ->where('t.status > :status:', array('status' => self::STATUS_DELETED));
        }
        return $builder;
    }
}