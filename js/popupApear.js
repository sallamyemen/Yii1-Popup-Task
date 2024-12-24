$(document).load(function() {
    setTimeout(function() {
        $.get('<?php echo Yii::app()->createUrl("/index.php/popup/getActivePopup"); ?>', function(response) {
            if (response) {
                $('body').append(response);
                $('#popup-container').fadeIn();
            }
        });
    }, 1000);
});