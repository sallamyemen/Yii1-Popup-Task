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

    public function actionCode($id)
    {
        $popup = Popup::model()->findByPk($id);

        if ($popup === null) {
            throw new CHttpException(404, 'Попап не найден.');
        }

        $popupHtml = $this->renderPartial('_popupContent', array('popup' => $popup), true);

        $this->render('code', array(
            'popup' => $popup,
            'popupHtml' => CHtml::encode($popupHtml), // Экранирование HTML
        ));
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

        Yii::log('Запрос получен: ' . print_r($_POST, true), 'info');

        if (Yii::app()->request->isPostRequest) {

            Yii::log('Запрос получен: ' . print_r($_POST, true), 'info');

            $id = Yii::app()->request->getPost('id');
            $popup = Popup::model()->findByPk($id);

            if ($popup !== null) {

                $popup->views++;
                $popup->save();

                echo CJSON::encode(array(
                    'status' => 'success',
                    'popupContent' => $this->renderPartial('_popupContent', array('popup' => $popup), true),
                ));
                Yii::app()->end();
            }
        }

        echo CJSON::encode(array('status' => 'error', 'message' => 'Попап не найден.'));
        Yii::app()->end();
    }

}
