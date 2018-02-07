<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
/**
 * API Controller
 *
 * @property \App\Model\Table\ApiTable $Articles
 *
 * @method \App\Model\Entity\Article[] paginate($object = null, array $settings = [])
 */
class ApiController extends AppController
{


  public function beforeFilter(Event $event)
  {
    $actions = ['index', 'index2'];
    parent::beforeFilter($event);
    $this->autoRender = FALSE;
    $this->Auth->allow($actions);
    $this->eventManager()->off($this->Csrf);
    $this->Security->config('unlockedActions',$actions);

    //$this->Security->blackHoleCallback = "securityError";
    //$this->Security->unlockedActions = array('index');
    //$this->Security->validatePost = false; // Post
    //$this->Security->csrfCheck = false; // Ajax
  }

  function securityError() {
  	$this->redirect('https://' . env('SERVER_NAME') . $this->here);
  }

  /**
    * Index method
    *
    * @return \Cake\Http\Response|void
    */
  public function index()
  {
      $arr = array();
      $arr = [
        '1',
        '2',
        '3'
      ];

      $this->response->charset('UTF-8');
      $this->response->type('json');
      $this->response->body(json_encode($arr));
      return;

  }
}
