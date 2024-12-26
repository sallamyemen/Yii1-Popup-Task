<?php


class PopupController extends CController
{

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionList()
    {
        $popups = Popup::model()->findAll();
        $this->render('list', array('popups' => $popups));
    }

    public function actionCreate()
    {
        $model = new Popup;

        if (isset($_POST['Popup'])) {
            $model->attributes = $_POST['Popup'];
            if ($model->save()) {
                $this->redirect(array('popup/list')); // Возвращаемся на список
            }
        }

        $this->render('create', array('model' => $model));
    }


    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['Popup'])) {
            $model->attributes = $_POST['Popup'];
            if ($model->save()) {
                $this->redirect(array('popup/list')); // Возвращаемся на список
            }
        }

        $this->render('update', array('model' => $model));
    }


    public function actionDelete($id)
    {
        $model = $this->loadModel($id);

        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();
            $this->redirect(array('popup/list')); // Возвращаемся на список
        } else {
            throw new CHttpException(400, 'Неверный запрос');
        }
    }

    protected function loadModel($id)
    {
        $model = Popup::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'Попап не найден.');
        }
        return $model;
    }

    public function actionIncrementViews()
    {
        if (Yii::app()->request->isPostRequest) {
            $id = Yii::app()->request->getPost('id');
            $popup = Popup::model()->findByPk($id);

            if ($popup !== null) {
                $popup->views++;
                $popup->save();

                $popupContent = $this->renderPartial('_popupContent', ['popup' => $popup], true);

                Yii::log('Рендеринг контента попапа: ' . $popupContent, 'info');

                echo CJSON::encode([
                    'status' => 'success',
                    'popupContent' => $popupContent,
                ]);
                Yii::app()->end();
            }
        }

        echo CJSON::encode(['status' => 'error', 'message' => 'Попап не найден.']);
        Yii::app()->end();
    }

    public function actionGetActivePopup()
    {
        $popup = Popup::model()->findByAttributes(array('enabled' => 1));

        if ($popup === null) {
            echo 'Нет активных попапов';
            Yii::app()->end();
        }

        // Рендерим HTML-содержимое попапа
        $this->renderPartial('_popupContent', array('popup' => $popup));
    }

    public function actionGenerateScript($id)
    {
        // Получаем попап из базы данных по ID
        $popup = Popup::model()->findByPk($id);

        if ($popup === null) {
            throw new CHttpException(404, 'Попап не найден.');
        }

        // Проверяем, активен ли попап
        if (!$popup->enabled) {
            throw new CHttpException(403, 'Попап неактивен. Скрипт нельзя сгенерировать.');
        }

        // Генерация JavaScript-кода
        $popupUrl = $this->createAbsoluteUrl('popup/GetActivePopup', ['id' => $popup->id]);
        $incrementUrl = $this->createAbsoluteUrl('popup/incrementViews');
        $script = <<<EOT
<script type="text/javascript">
(function() {
    var popupId = {$popup->id};
    var popupUrl = '{$popupUrl}';
    var incrementUrl = '{$incrementUrl}';

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
EOT;

        // Отображение представления сгенерированного скрипта
        $this->render('code', ['script' => $script]);
    }
}
