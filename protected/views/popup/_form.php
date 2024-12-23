<h1><?php echo $model->isNewRecord ? 'Создать Попап' : 'Редактировать Попап'; ?></h1>
<div>
    <?php $form = $this->beginWidget('CActiveForm'); ?>

    <div>
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title'); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model, 'content'); ?>
        <?php echo $form->textArea($model, 'content'); ?>
        <?php echo $form->error($model, 'content'); ?>
    </div>

    <div>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>
