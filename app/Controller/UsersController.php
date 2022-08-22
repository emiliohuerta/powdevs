<?php
// app/Controller/UsersController.php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'logout');
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'login'));
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function login() {
        
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
            return $this->redirect('/marvelCharacters/index');
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }
    
    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

}

?>