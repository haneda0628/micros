<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

define('SUPERUSER', 1);
define('COMPANY_MANAGER', 2);
define('GROUP_MANAGER', 3);
define('USER', 4);

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[] paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function beforeFilter(Event $event)
    {
      parent::beforeFilter($event);

      $this->Auth->allow(['logout']);

    }

    /**
    * ログインアクション
    */
   public function login()
   {
     if ($this->request->is('post')) {
       $this->log('login');
       $user = $this->Auth->identify();
       if ($user) {
         $this->Auth->setUser($user);
         return $this->redirect($this->Auth->redirectUrl());
       }
       $this->Flash->error(__('Invalid username or password, try again'));
     }
   }

   /**
    * ログアウトアクション
    */
   public function logout()
   {
       return $this->redirect($this->Auth->logout());
   }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
      //$this->log($this->Users);
        $this->paginate = [
            'contain' => ['Groups', 'Authorities']
        ];
        $users = array();
        //制限なし
        if($this->Auth->user('authority_id') === SUPERUSER)
        {
          $users = $this->paginate($this->Users);
        } else if ($this->Auth->user('authority_id') === COMPANY_MANAGER)
        {
          $group = $this->Users->Groups->get($this->Auth->user('group_id'));
          $company = $this->Users->Groups->Companies->get($group['company_id']);

          //$this->log($company);
          // $users = $this->Users->find('list',
          // [
            // 'contain' =>
            //   [
            //     'Groups' =>
            //     function($q) {
            //       return $q->where([
            //         'Groups.company_id = 1'
            //       ]);
            //     }
            // ],
          //   'limit' => 200
          // ]);
          //$this->log($users);
          //$users = $this->paginate($users);
          $users = $this->paginate($this->Users);

        }  else if ($this->Auth->user('authority_id') === GROUP_MANAGER)
        {

        }


        $this->set(compact('users'));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Groups', 'Authorities']
        ]);

        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // if($this->Auth->user()) {
        //     $this->log($this->Auth->user());
        //     if($this->Auth->user('authority_id') === SUPERUSER) {
        //         $this->log('OK. You are an authenticated user.');
        //     } else {
        //         return $this->redirect(['action' => 'index']);
        //     }
        // }

        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $groups = array();
        $authorities = array();

        $group = $this->Users->Groups->get($this->Auth->user('group_id'));
        $company = $this->Users->Groups->Companies->get($group['company_id']);

        //*アクセス制限なし
        if($this->Auth->user('authority_id') === SUPERUSER) {
          $groups = $this->Users->Groups->find('list', ['limit' => 200]);
          $authorities = $this->Users->Authorities->find('list', ['limit' => 200]);

        //*アクセス制限：
        //　権限IDはグループ管理者およびユーザー管理者のみ作成可能
        //　グループIDは会社以下の権限を付与可能
        } else if ($this->Auth->user('authority_id') === COMPANY_MANAGER)
        {
          $groups = $this->Users->Groups->find('list',
          [
            'valueField' => 'group_name',
            'conditions' => ['groups.company_id = ' => $company['id'] ],
            'limit' => 200,
          ]);
          $authorities = $this->Users->Authorities->find('list',
          [
            'valueField' => 'authority_name',
            'conditions' => [
            'OR' => array(
              ['id = ' => GROUP_MANAGER],
              ['id = ' => USER]
              )
           ],
          ['limit' => 200]
          ]);
          $this->log($authorities);

          //*アクセス制限：
          //　ユーザー管理者のみ作成可能
          //　グループは本人と同等
        } else if ($this->Auth->user('authority_id') === GROUP_MANAGER) {
          $groups = $this->Users->Groups->find('list',
          [
            'valueField' => 'group_name',
            'conditions' =>
            ['groups.id = ' => $this->Auth->user('group_id') ],
            'limit' => 200
          ]);
          $authorities = $this->Users->Authorities->find('list',
          [
            'valueField' => 'authority_name',
            'conditions' => [
            'id = ' => USER
           ],
          ['limit' => 200]
          ]);
        }
        $this->set(compact('user', 'groups', 'authorities'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $groups = $this->Users->Groups->find('list', ['limit' => 200]);
        $authorities = $this->Users->Authorities->find('list', ['limit' => 200]);
        $this->set(compact('user', 'groups', 'authorities'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /*
     * Control authentication in this function.
    */
    public function isAuthorized($user) {
        return parent::isAuthorized($user);
    }
}
