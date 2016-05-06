<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/22/2016
 * Time: 3:03 PM
 */

namespace App\Modules\Backend\Forms;

use App\Forms\Groups\BoxFooter;
use OE\Widget\Form;
use OE\Widget\Form\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use OE\Widget\Form\Group;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Url;

class WebsiteForm extends Form
{
    public function init() {

        $name = new Text('name');
        $name->setLabel($this->_('name'));
        $name->addValidator(new StringLength(array(
                'max' => 255,
                'messageMaximum' => $this->_('%n% is too long. Maximum %m% characters', array('n' => 'name', 'm' => 255)))
        ));
        $name->addValidator(new PresenceOf(array(
            'message' => $this->_('%n% is required', array('n' => 'name'))
        )));

        $domain = new Text('domain');
        $domain->setLabel($this->_('domain'));
        $domain->addValidator(new StringLength(array(
                'max' => 255,
                'messageMaximum' => $this->_('%n% is too long. Maximum %m% characters', array('n' => 'domain', 'm' => 255)))
        ));
        $domain->addValidator(new PresenceOf(array(
            'message' => $this->_('%n% is required', array('n' => 'domain'))
        )));
        $domain->addValidator(new Url(array(
            'message' => $this->_('%n% is a url', array('n' => 'domain'))
        )));


        $groupBody = new Group('box-body', array(
            $name, $domain
        ), array('class' => 'box-body'));

        $this->addGroup($groupBody);
        $this->addGroup(new BoxFooter());
    }
}