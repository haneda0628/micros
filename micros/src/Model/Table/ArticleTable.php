<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ArticleTable extends Table
{
  public function initialize(array $config)
  {
    $this->addBehavior('Timestamp');
  }
}
