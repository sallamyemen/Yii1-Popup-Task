$(document).ready(function () {
    // Обработчик клика на ссылку "Показать"
    $('.show-popup').click(function (e) {
        e.preventDefault();

        var popupId = $(this).data('id');

        $.ajax({
            url: '/index.php/popup/incrementViews',
            type: 'POST',
            data: {id: popupId},
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    //$('#popup-list').html(response.updatedList);
                    // Отображаем попап
                    $('#popup-content').html(response._popupContent);
                    $('#popup-modal').fadeIn();

                } else {
                    alert('Ошибка: ' + response.message);
                }
            },
            error: function () {
                alert(response.message ||'Ошибка при выполнении запроса.');
            }
        });
    });

    // Закрытие попапа
    $('#close-popup').click(function () {
        $('#popup-modal').fadeOut();
    });

    $(document).on('change', '.toggle-active', function() {
        var isActive = $(this).is(':checked') ? 1 : 0;
        var popupId = $(this).data('id');

        $.post('/index.php/popup/toggleActive', { id: popupId, enabled: isActive }, function(response) {
            if (response.success) {
                alert('Статус обновлён.');
            } else {
                alert('Ошибка при обновлении статуса.');
            }
        }, 'json');
    });
});
