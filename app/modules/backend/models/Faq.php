<?php
        		        		
namespace App\Modules\Backend\Models;

use App\Helpers\Translate;
use App\Models\Faq as ModelApp;

class Faq extends ModelApp
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
            ->columns(array('t.id','t.question', 't.anser', 't.status'))
            ->addFrom(__CLASS__, 't')
            ->where('t.status > :status:', array('status' => self::STATUS_DELETED));

        return $builder;
    }

}
