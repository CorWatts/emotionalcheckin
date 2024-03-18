<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;dbname=[DBNAME]',
            'username' => '[USER]',
            'password' => '[PASSWORD]',
            'charset' => 'utf8',
        ],
        'mail' => [
            'class' => 'yii\symphonymailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
