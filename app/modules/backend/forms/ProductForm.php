<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 3/30/2016
 * Time: 10:41 PM
 */

namespace App\Modules\Backend\Forms;


use App\Forms\Groups\BoxFooter;
use App\Modules\Backend\Models\CmsCategory;
use OE\Widget\Form;
use OE\Widget\Form\Element\Select;
use OE\Widget\Form\Element\Text;
use OE\Widget\Form\Element\TextArea;
use OE\Widget\Form\Group;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Url;

class ProductForm extends Form
{
    public function init()
    {
        $title = new Text('title');
        $title->setLabel($this->_('title'));
        $title->addValidator(new StringLength(array(
                'max' => 355,
                'messageMaximum' => $this->_('%n% is too long. Maximum %m% characters', array('n' => 'title', 'm' => 355)))
        ));
        $title->addValidator(new PresenceOf(array(
            'message' => $this->_('%n% is required', array('n' => 'title'))
        )));

        $link = new Text('link');
        $link->setLabel($this->_('link'));
        $link->addValidator(new StringLength(array(
                'max' => 355,
                'messageMaximum' => $this->_('%n% is too long. Maximum %m% characters', array('n' => 'link', 'm' => 355)))
        ));
        $link->addValidator(new PresenceOf(array(
            'message' => $this->_('%n% is required', array('n' => 'link'))
        )));
        $link->addValidator(new Url(array(
            'message' => $this->_('%n% is a url', array('n' => 'link'))
        )));

        $content = new TextArea('content');
        $content->setLabel($this->_('content'));

        $oldprice = new Text('oldprice');
        $oldprice->setLabel($this->_('Old Price'));
        $oldprice->addValidator(new Numericality(array(
            'message' => $this->_('Old price is number')
        )));
        $oldprice->addValidator(new PresenceOf(array(
            'message' => $this->_('%n% is required', array('n' => 'title'))
        )));

        $newprice = new Text('newprice');
        $newprice->setLabel($this->_('New Price'));
        $newprice->addValidator(new Numericality(array(
            'message' => $this->_('New price is number')
        )));
        $newprice->addValidator(new PresenceOf(array(
            'message' => $this->_('%n% is required', array('n' => 'title'))
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

        $status = new Select('status',array(1=>'active', 0=>'inactive'));
        $status->setLabel($this->_('Status'));

        $groupBody = new Group('box-body', array(
            $title, $link, $content, $oldprice, $newprice, $category, $status
        ), array('class' => 'box-body'));

        $this->addGroup($groupBody);
        $this->addGroup(new BoxFooter());
    }
}