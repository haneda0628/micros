<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Authority $authority
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $authority->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $authority->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Authorities'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="authorities form large-9 medium-8 columns content">
    <?= $this->Form->create($authority) ?>
    <fieldset>
        <legend><?= __('Edit Authority') ?></legend>
        <?php
            echo $this->Form->control('authority_name');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
