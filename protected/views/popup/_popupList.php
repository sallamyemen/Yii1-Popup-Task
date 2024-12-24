<h1>Список попапов</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Содержание</th>
        <th>Просмотры</th>
        <th>Активен</th>
        <th>Действия</th>
    </tr>
    <?php foreach ($popups as $popup): ?>
        <tr>
            <td><?php echo CHtml::encode($popup->id); ?></td>
            <td><?php echo CHtml::encode($popup->title); ?></td>
            <td><?php echo CHtml::encode($popup->content); ?></td>
            <td><?php echo $popup->views; ?></td>
            <td><?php echo $popup->enabled ? 'Да' : 'Нет'; ?></td>
            <td>
                <a href="<?php echo Yii::app()->createUrl('popup/update', array('id' => $popup->id)); ?>">Редактировать</a> |
                <?php if ($popup->enabled): ?>
                    <a href="<?php echo Yii::app()->createUrl('popup/generateScript', ['id' => $popup->id]); ?>" class="generate-script">Сгенерировать скрипт</a> |
                <?php else: ?>
                    <a href="javascript:void(0);" class="generate-script disabled" style="pointer-events: none; color: gray;">Сгенерировать скрипт</a> |
                <?php endif; ?>
                <a href="<?php echo Yii::app()->createUrl('popup/delete', array('id' => $popup->id)); ?>">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<p>
    <a href="<?php echo $this->createUrl('popup/create'); ?>">Добавить новый попап</a>
</p>

<!-- Модальное окно -->
<div id="popup-modal" style="display:none; position:fixed; top:20%; left:30%; background:#fff; border:1px solid #ccc; padding:20px; height: 200px;width: 600px;justify-content: center;align-items: self-end;">
    <div id="popup-content">

    </div>
    <button id="close-popup">Закрыть</button>
</div>
