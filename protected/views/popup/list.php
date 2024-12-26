<?php

Yii::app()->clientScript->registerCoreScript('jquery');

?>

<div id="popup-list">
    <?php $this->renderPartial('_popupList', ['popups' => $popups]); ?>
</div>

