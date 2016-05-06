<?php
        		
namespace App\Modules\Backend\Forms;

use OE\Widget\Form;
use OE\Widget\Form\Group;
use OE\Widget\Form\Element\Text;
use OE\Widget\Form\Element\Email;
use OE\Widget\Form\Element\Password;
use OE\Widget\Form\Element\Select;
use OE\Widget\Form\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Email as EmailValidator;
use App\Forms\Groups\BoxFooter;
use App\Modules\Backend\Models\Faq;

class FaqForm extends Form {

    public function init() {

        $question = new TextArea('question');
		$question->setLabel($this->_('question'));
        $question->addValidator(new PresenceOf(array(
        		'message' => $this->_('%n% is required', array('n' => 'question'))
        )));
		$question->addValidator(new StringLength(array(
        		'max' => 500, 
        		'messageMaximum' => $this->_('%n% is too long. Maximum %m% characters', array('n' => 'question', 'm' => 500)))
        ));
						

        $anser = new Text('anser');
		$anser->setLabel($this->_('anser'));
        $status = new Select('status');
		$status->setLabel($this->_('status'));
        $status->setOptions(Faq::listStatus());

		
        $groupBody = new Group('box-body', array(
		$question, $anser, $status			
		), array('class' => 'box-body'));
		
		$this->addGroup($groupBody);
		$this->addGroup(new BoxFooter());
	}

}
