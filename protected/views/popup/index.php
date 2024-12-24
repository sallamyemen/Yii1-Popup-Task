<?php

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/popupApear.js', CClientScript::POS_READY);

?>

<h1>Главная страница</h1>

<p>
    Добро пожаловать в административную панель.
</p>
<p>
    <a href="<?php echo $this->createUrl('popup/list'); ?>">Список всех попапов</a>
</p>


