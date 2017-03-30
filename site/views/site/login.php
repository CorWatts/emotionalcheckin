<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \common\models\LoginForm $model
 */
$this->title = 'The Faster Scale App | Login';
?>
<div class="site-login">
  <h1>Login</h1>
  <p><strong>NOTE: Log in via username has now been removed. Please log in using your email address.</strong></p>
  <p>Please fill out the following fields to login:</p>

  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
        <div style="color:#999;margin:1em 0">
          If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
        </div>
        <div class="form-group">
          <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
