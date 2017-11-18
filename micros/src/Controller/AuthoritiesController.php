<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Authorities Controller
 *
 * @property \App\Model\Table\AuthoritiesTable $Authorities
 *
 * @method \App\Model\Entity\Authority[] paginate($object = null, array $settings = [])
 */
class AuthoritiesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $authorities = $this->paginate($this->Authorities);

        $this->set(compact('authorities'));
        $this->set('_serialize', ['authorities']);
    }

    /**
     * View method
     *
     * @param string|null $id Authority id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $authority = $this->Authorities->get($id, [
            'contain' => ['Users']
        ]);

        $this->set('authority', $authority);
        $this->set('_serialize', ['authority']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $authority = $this->Authorities->newEntity();
        if ($this->request->is('post')) {
            $authority = $this->Authorities->patchEntity($authority, $this->request->getData());
            if ($this->Authorities->save($authority)) {
                $this->Flash->success(__('The authority has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The authority could not be saved. Please, try again.'));
        }
        $this->set(compact('authority'));
        $this->set('_serialize', ['authority']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Authority id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $authority = $this->Authorities->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $authority = $this->Authorities->patchEntity($authority, $this->request->getData());
            if ($this->Authorities->save($authority)) {
                $this->Flash->success(__('The authority has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The authority could not be saved. Please, try again.'));
        }
        $this->set(compact('authority'));
        $this->set('_serialize', ['authority']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Authority id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $authority = $this->Authorities->get($id);
        if ($this->Authorities->delete($authority)) {
            $this->Flash->success(__('The authority has been deleted.'));
        } else {
            $this->Flash->error(__('The authority could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
