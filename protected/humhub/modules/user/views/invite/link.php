<?php

use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => false]); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"
                id="myModalLabel"><?php echo Yii::t('UserModule.invite', '<strong>Invite</strong> new people'); ?></h4>
        </div>
        <div class="modal-body">

            <br/>

            <?php echo Yii::t('UserModule.invite', 'Please send the link below to <strong>one</strong> person you want to invite.'); ?>
            <br/>
            <?php echo Yii::t('UserModule.invite', 'Important: create another link for each person.'); ?>
            <br/>
            <br/>
            <div class="form-group">
                <textarea class="link-txt" style="width:80%; margin-right:10px; border: none; overflow: hidden; height: 17px;" spellcheck='false' readonly rows="1"><?php echo $link; ?></textarea>
                <a href="#" data-action-click="copyToClipboard" data-action-target=".link-txt"><i class="fa fa-clipboard" aria-hidden="true"></i></a>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-primary" data-action-click="ui.modal.submit" data-action-url="<?= Url::to(['/user/invite/link']) ?>" data-ui-loader>
                <?= Yii::t('UserModule.invite', 'Create another link') ?>
            </a>

            <a href="#" class="btn btn-primary" data-dismiss="modal">
                <?= Yii::t('UserModule.invite', 'Done') ?>
            </a>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>