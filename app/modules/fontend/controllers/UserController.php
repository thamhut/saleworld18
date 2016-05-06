<?php
/**
 * Created by PhpStorm.
 * User: thamhut
 * Date: 4/21/2016
 * Time: 9:11 PM
 */

namespace App\Modules\Fontend\Controllers;


use App\Components\Common;
use App\Models\AclRole;
use App\Models\AclUser;
use App\Modules\Fontend\Forms\AclUserForm;
use App\Modules\Fontend\Forms\LoginForm;

class UserController extends BaseController
{
    public function loginAction(){
        $form = new LoginForm();
        $message = '';
        if($this->request->isPost()) {
            if($form->isValid($_POST)) {
                $username = $form->getValue('username');
                $password = $form->getValue('password');
                $password = Common::hash($password);
                $language = $form->getValue('language');

                $aclUser = AclUser::findFirst(array(
                    "username = :username: AND password = :password: AND status = 1",
                    'bind' => array(
                        'username' => $username,
                        'password' => $password
                    )
                ));
                if($aclUser) {
                    $this->_registerSession($username, $aclUser->getIdAclRole(), $aclUser->getFullName(), $language, $aclUser->id);
                    echo $message;
                    exit();
                }
                $message = $this->_('Incorrect username or password or the account has been deleted.');
            } else {
                $message = $this->_('Please input valid data to submit.');
            }
        }
        echo $message;
        exit();
    }

    public function logoutAction(){
        $this->session->remove('auth');
        session_regenerate_id();
        return $this->redirect('index', 'index');
    }

    public function registerAction(){
        $form = new AclUserForm();

        if($this->request->isPost()) {
            if($form->isValid($_POST)) {
                $model = new AclUser();
                $data = $form->getValues();
                $data['password'] = Common::hash($data['password']);
                $data['id_acl_role'] = 3;
                if($model->save($data)) {
                    $this->flashSession->success($this->_("Create AclUser successfully"));
                    echo true;
                    return true;
                }
                $this->flashSession->error($this->_("Create AclUser error"));
            }
            else{
                $err = array();
                foreach($form->getMessages() as $v){
                    $err[$v->getField()] = $v->getMessage();
                }
                echo json_encode($err);
            }
        }
    }

    public function successAction(){

    }
    /**
     * Register an authenticated $aclUser into session data
     *
     * @param $aclUser $aclUser
     */
    private function _registerSession($username, $idAclRole, $fullname, $language, $id) {
        $this->session->set('auth', array(
            'username' => $username,
            'id_acl_role' => $idAclRole,
            'fullname' => $fullname,
            'language' => $language,
            'id' => $id,
        ));
    }
}