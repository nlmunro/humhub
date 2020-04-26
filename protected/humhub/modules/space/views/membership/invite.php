<?= \humhub\modules\space\widgets\InviteModal::widget([
    'model' => $model,
    'submitText' => Yii::t('SpaceModule.base', 'Send'),
    'submitAction' => $space->createUrl('/space/membership/invite'),
    'linkAction' => $space->createUrl('/space/membership/link'),
    'searchUrl' => $space->createUrl('/space/membership/search-invite')
]); ?>