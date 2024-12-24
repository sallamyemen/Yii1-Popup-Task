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
            cache: false,
            success: function (response) {
                console.log('Response received:', response);
                if (response.status === 'success') {
                    // Обновляем только контент конкретного попапа
                    $('#popup-content').html(response.popupContent);
                    $('#popup-modal').fadeIn();
                } else {
                    alert('Ошибка: ' + response.message);
                }
            },
            error: function () {
                console.log('AJAX Error:', status, error);
                alert('Ошибка при выполнении запроса.');
            }
        });
    });

    // Закрытие попапа
    $('#close-popup').click(function () {
        $('#popup-modal').fadeOut();
    });
});
