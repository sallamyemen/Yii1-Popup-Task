<div id="popup-<?php echo $popup->id; ?>" class="popup">
    <h2><?php echo CHtml::encode($popup->title); ?></h2>
    <p><?php echo CHtml::encode($popup->content); ?></p>
    <button onclick="document.getElementById('popup-<?php echo $popup->id; ?>').style.display='none';">Закрыть</button>
</div>
