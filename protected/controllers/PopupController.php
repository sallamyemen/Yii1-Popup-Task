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
        if (Yii::app()->request->isPostRequest) {
            $this->loadModel($id)->delete();
            $this->redirect(array('popup/list')); // Возвращаемся на список
        } else {
            throw new CHttpException(400, 'Неверный запрос');
        }
    }

//    public function actionCode($id)
//    {
//        $popup = Popup::model()->findByPk($id);
//
//        if ($popup === null) {
//            throw new CHttpException(404, 'Попап не найден.');
//        }
//
//        $popupHtml = $this->renderPartial('_popupContent', array('popup' => $popup), true);
//
//        $this->render('code', array(
//            'popup' => $popup,
//            'popupHtml' => CHtml::encode($popupHtml), // Экранирование HTML
//        ));
//    }

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

        Yii::log('Запрос получен: ' . print_r($_POST, true), 'info');

        if (Yii::app()->request->isPostRequest) {

            Yii::log('Запрос получен: ' . print_r($_POST, true), 'info');

            $id = Yii::app()->request->getPost('id');
            $popup = Popup::model()->findByPk($id);

            if ($popup !== null) {

                $popup->views++;
                $popup->save();

                // Получаем все попапы для обновления списка
                //$allPopups = Popup::model()->findAll();

                // Рендерим обновленный список попапов
                //$updatedList = $this->renderPartial('_popupList', ['popups' => $popups], true);


                echo CJSON::encode(array(
                    'status' => 'success',
                    'popupContent' => $this->renderPartial('_popupContent', array('popup' => $popup), true),
                    //'updatedList' => $updatedList,
                    //'_popupContent' => $this->renderPartial('_popupContent', ['popup' => $popup], true),
                ));
                Yii::app()->end();
            }
        }

        echo CJSON::encode(array('status' => 'error', 'message' => 'Попап не найден.'));
        Yii::app()->end();
    }

    public function actionShow()
    {
        $id = Yii::app()->request->getPost('id');
        $popup = Popup::model()->findByPk($id);

        if ($popup === null) {
            echo CJSON::encode(['success' => false, 'message' => 'Попап не найден.']);
            Yii::app()->end();
        }

        // Увеличение счётчика просмотров
        $popup->views++;
        $popup->save();

        // Рендеринг контента попапа
        $popupContent = $this->renderPartial('_popupContent', ['popup' => $popup], true);

        // Обновление списка попапов
        $updatedList = $this->renderPartial('_popupList', ['popups' => Popup::model()->findAll()], true);

        echo CJSON::encode([
            'success' => true,
            'popupContent' => $popupContent,
            'updatedList' => $updatedList,
        ]);
        Yii::app()->end();
    }

    public function actionGetActivePopup()
    {
        $popup = Popup::model()->findByAttributes(array('is_active' => 1));

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
        $script = <<<EOT
<script type="text/javascript">
(function() {
    var popupId = {$popup->id};
    var popupUrl = '{$this->createAbsoluteUrl('popup/getPopup', ['id' => $popup->id])}';

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
            }
        };
        xhr.send();
    }

    setTimeout(loadPopup, 10000);
})();
</script>
EOT;

        // Передаём скрипт в представление
        $this->render('code', ['script' => $script]);
    }




}
