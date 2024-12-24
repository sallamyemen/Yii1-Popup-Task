<?php

Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/popup.js', CClientScript::POS_END);

?>

<div id="popup-list">
    <?php $this->renderPartial('_popupList', ['popups' => $popups]); ?>
</div>

