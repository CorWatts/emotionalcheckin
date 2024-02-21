<?php
$main = require __DIR__ . '/main.php';
$mainlocal = require __DIR__ . '/main-local.php';

$config = [
  'id' => 'app-site-tests',
  'basePath' => dirname(__DIR__),
 'components' => [
   'mailer' => [
     'class' => \yii\symphonymailer\Mailer::class,
     'viewPath' => '@common/mail',
     'useFileTransport' => true,
   ],
  ]
];

return yii\helpers\ArrayHelper::merge(
  $main,
  $mainlocal,
  $config
);
