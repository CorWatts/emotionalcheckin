<?php
return [
  'id' => 'app-common-tests',
  'basePath' => dirname(__DIR__),
  'components' => [
    'mailer' => [
      'class' => 'yii\symphonymailer\Mailer',
      'viewPath' => '@common/mail',
      'useFileTransport' => true,
    ],
  ]
];
