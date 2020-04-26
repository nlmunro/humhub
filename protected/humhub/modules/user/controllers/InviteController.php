<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\user\controllers;

use humhub\modules\user\Module;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use humhub\components\behaviors\AccessControl;
use humhub\modules\admin\permissions\ManageGroups;
use humhub\modules\admin\permissions\ManageUsers;
use humhub\modules\user\models\Invite;
use humhub\modules\user\models\forms\Invite as InviteForm;
use humhub\widgets\ModalClose;
use yii\helpers\Url;

/**
 * InviteController for new user invites
 *
 * @since 1.1
 */
class InviteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'acl' => [
                'class' => AccessControl::class,
            ]
        ];
    }

    /**
     * Invite form and processing action
     *
     * @return string the action result
     * @throws \yii\web\HttpException
     */
    public function actionIndex()
    {
        if (!$this->canInvite()) {
            throw new HttpException(403, 'Invite denied!');
        }

        $model = new InviteForm;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->getEmails() as $email) {
                $this->createInvite($email);
            }

            return ModalClose::widget([
                'success' => Yii::t('UserModule.base', 'User has been invited.')
            ]);
        }

        return $this->renderAjax('index', ['model' => $model]);
    }

    /**
     * Invite form and processing action
     *
     * @return string the action result
     * @throws \yii\web\HttpException
     */
    public function actionLink()
    {
        if (!$this->canInvite()) {
            throw new HttpException(403, 'Invite denied!');
        }

        if (Yii::$app->request->post()) {
            $link = $this->createLink();
        }

        return $this->renderAjax('link', ['link' => $link]);
    }

    /**
     * Creates and sends an e-mail invite
     *
     * @param email $email
     */
    protected function createInvite($email)
    {
        $userInvite = new Invite();
        $userInvite->email = $email;
        $userInvite->source = Invite::SOURCE_INVITE;
        $userInvite->user_originator_id = Yii::$app->user->getIdentity()->id;

        $existingInvite = Invite::findOne(['email' => $email]);
        if ($existingInvite !== null) {
            $userInvite->token = $existingInvite->token;
            $existingInvite->delete();
        }

        $userInvite->save();
        $userInvite->sendInviteMail();
    }

    /**
     * Creates and returns an invite link
     *
     * @param email $email
     */


    protected function createLink()
    {
        $userInvite = new Invite();
        $userInvite->email = uniqid()."-noreply@email.com";
        $userInvite->source = Invite::SOURCE_INVITE;
        $userInvite->user_originator_id = Yii::$app->user->getIdentity()->id;

        $existingInvite = Invite::findOne(['email' => $userInvite->email]);
        if ($existingInvite !== null) {
            $userInvite->token = $existingInvite->token;
            $existingInvite->delete();
        }
        $userInvite->save();
        return Url::to(['/user/registration', 'token' => $userInvite->token], true);
    }

    /**
     * Checks if current user can invite new members
     *
     * @return boolean can invite new members
     */
    protected function canInvite()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('user');

        return $module->settings->get('auth.internalUsersCanInvite') ||
            Yii::$app->user->can([new ManageUsers(), new ManageGroups()]);
    }

}
