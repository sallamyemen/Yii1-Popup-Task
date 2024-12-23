<?php

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/popup.js', CClientScript::POS_END);

?>
<h1>Список попапов</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Содержимое</th>
        <th>Просмотры</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($popups as $popup): ?>
        <tr>
            <td><?php echo CHtml::encode($popup->id); ?></td>
            <td><?php echo CHtml::encode($popup->title); ?></td>
            <td><?php echo CHtml::encode($popup->content); ?></td>
            <td><?php echo CHtml::encode($popup->views); ?></td>
            <td>
                <a href="<?php echo $this->createUrl('popup/incrementViews', array('id' => $popup->id)); ?>" class="show-popup" data-id="<?php echo $popup->id; ?>">Показать</a> |
                <a href="<?php echo $this->createUrl('popup/update', array('id' => $popup->id)); ?>">Редактировать</a> |
                <a href="<?php echo $this->createUrl('popup/code', array('id' => $popup->id)); ?>">Показать код</a> |
                <?php echo CHtml::link('Удалить', '#', array(
                    'submit' => array('popup/delete', 'id' => $popup->id),
                    'confirm' => 'Вы уверены, что хотите удалить этот попап?'
                )); ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<p>
    <a href="<?php echo $this->createUrl('popup/create'); ?>">Добавить новый попап</a>
</p>

<!-- Модальное окно -->
<div id="popup-modal" style="display:none; position:fixed; top:20%; left:30%; background:#fff; border:1px solid #ccc; padding:20px; z-index:1000;">
    <div id="popup-content"></div>
    <button id="close-popup">Закрыть</button>
</div>
