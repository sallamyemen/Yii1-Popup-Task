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
                    // Отображаем попап
                    $('#popup-content').html(response.popupContent);
                    $('#popup-modal').fadeIn();
                } else {
                    alert('Ошибка: ' + response.message);
                }
            },
            error: function () {
                alert('Ошибка при выполнении запроса.');
            }
        });
    });

    // Закрытие попапа
    $('#close-popup').click(function () {
        $('#popup-modal').fadeOut();
    });
});
