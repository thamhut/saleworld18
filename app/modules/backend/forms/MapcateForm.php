<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 3/26/2016
 * Time: 7:11 PM
 */

namespace App\Modules\Backend\Forms;


use App\Forms\Groups\BoxFooter;
use App\Modules\Backend\Models\CmsCategory;
use App\Modules\Backend\Models\Website;
use OE\Widget\Form;
use OE\Widget\Form\Element\Text;
use OE\Widget\Form\Element\Select;
use OE\Widget\Form\Group;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Url;

class MapcateForm extends Form
{
    public function init() {
        $link = new Text('link');
        $link->setLabel($this->_('link'));
        $link->addValidator(new StringLength(array(
                'max' => 555,
                'messageMaximum' => $this->_('%n% is too long. Maximum %m% characters', array('n' => 'link', 'm' => 555)))
        ));
        $link->addValidator(new PresenceOf(array(
            'message' => $this->_('%n% is required', array('n' => 'link'))
        )));
        $link->addValidator(new Url(array(
            'message' => $this->_('%n% is a url', array('n' => 'link'))
        )));

        $category = new CmsCategory();
        $category = $category->find('status > 0');
        $arrCate = $parent = $namecate = array();
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
        $category = new Select('idcate',$arrCate);
        $category->setLabel($this->_('Select category'));

        $domain = Website::find('status > 0')->toArray();
        $arrDomain = array();
        foreach($domain as $v){
            $arrDomain[$v['id']] = $v['domain'];
        }

        $domain = new Select('idweb', $arrDomain);
        $domain->setLabel($this->_('Select Domain'));

        $groupBody = new Group('box-body', array(
            $link, $category, $domain
        ), array('class' => 'box-body'));

        $this->addGroup($groupBody);
        $this->addGroup(new BoxFooter());
    }
}