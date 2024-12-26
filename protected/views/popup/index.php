<h1>Главная страница</h1>

<p>
    Добро пожаловать в административную панель.
</p>
<p>
    <a href="<?php echo $this->createUrl('popup/list'); ?>">Список всех попапов</a>
</p>

<script type="text/javascript">
    (function() {
        var popupId = 1;
        var popupUrl = 'http://yii1.local/index.php/popup/GetActivePopup?id=1';
        var incrementUrl = 'http://yii1.local/index.php/popup/incrementViews';

        function loadPopup() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', popupUrl, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var popupDiv = document.createElement('div');
                    popupDiv.innerHTML = xhr.responseText;
                    popupDiv.style.position = 'fixed';
                    popupDiv.style.top = '20%';
                    popupDiv.style.left = '30%';
                    popupDiv.style.backgroundColor = '#fff';
                    popupDiv.style.border = '1px solid #ccc';
                    popupDiv.style.padding = '20px';
                    popupDiv.style.zIndex = '1000';
                    document.body.appendChild(popupDiv);

                    var closeButton = document.createElement('button');
                    closeButton.textContent = 'Закрыть';
                    closeButton.style.marginTop = '10px';
                    closeButton.onclick = function() {
                        document.body.removeChild(popupDiv);
                    };
                    popupDiv.appendChild(closeButton);

                    // Увеличиваем счётчик просмотров
                    incrementViews();
                }
            };
            xhr.onerror = function () {
                console.error('Ошибка загрузки попапа.');
            };
            xhr.send();
        }

        function incrementViews() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', incrementUrl, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log('Счётчик просмотров увеличен.');
                } else {
                    console.error('Ошибка при увеличении счётчика просмотров.');
                }
            };
            xhr.onerror = function () {
                console.error('Ошибка сети при увеличении счётчика просмотров.');
            };
            xhr.send('id=' + popupId);
        }

        setTimeout(loadPopup, 10000);
    })();
</script>