<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 4/28/2016
 * Time: 10:36 PM
 */

namespace App\Models;


use Phalcon\Mvc\Collection;

class Links extends Collection
{
    public function getSource(){
        return "Links";
    }
}