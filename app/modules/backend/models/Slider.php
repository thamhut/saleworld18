<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 3/28/2016
 * Time: 10:57 PM
 */

namespace App\Modules\Backend\Models;


use App\Helpers\Translate;

class Slider extends \App\Models\Slider
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

    /**
     * @return
     */
    public function search() {
        $builder = $this->getModelsManager()
            ->createBuilder()
            ->columns(array('t.id','t.url', 't.status'))
            ->addFrom(__CLASS__, 't')
            ->where('t.status > :status:', array('status' => self::STATUS_DELETED));

        return $builder;
    }
}