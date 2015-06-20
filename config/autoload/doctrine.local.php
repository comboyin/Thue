<?php

return array(
    'doctrine' => array(
        // 1) for Aithentication
        'authentication' => array( // this part is for the Auth adapter from DoctrineModule/Authentication
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                // object_repository can be used instead of the object_manager key
                'identity_class' => 'Application\Entity\user', // 'Application\Entity\User',
                'identity_property' => 'MaUser', // 'username', // 'email',
                'credential_property' => 'MatKhau', // 'password',
                'credential_callable' => function (Application\Entity\user $user, $passwordGiven) { // not only User
                                                                                       // return my_awesome_check_test($user->getPassword(), $passwordGiven);
                                                                                       // echo '<h1>callback user->getPassword = ' .$user->getPassword() . ' passwordGiven = ' . $passwordGiven . '</h1>';
                                                                                       // - if ($user->getPassword() == md5($passwordGiven)) { // original
                                                                                       // ToDo find a way to access the Service Manager and get the static salt from config array
                    if ($user->getMatKhau() == md5($passwordGiven)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            )
        ),
        /* Mx9bWAMt1  */
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                /* 'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'root',
                    'password' => '',
                    'dbname' => 'comboyin1_thuehkd',
                    'charset' => 'utf8', // extra
                    'driverOptions' => array(
                        1002=>'SET NAMES utf8'
                    )
                ) */
                'params' => array(
                    'host' => 'lethithanhmy.com',
                    'port' => '3306',
                    'user' => 'comboyin1_thue',
                    'password' => 'Mainho7ngay',
                    'dbname' => 'comboyin1_thue',
                    'charset' => 'utf8', // extra
                    'driverOptions' => array(
                        1002=>'SET NAMES utf8'
                    )
                )
            )
        ),
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../../module/Application/src/Application/Entity'
                )
            ),
            
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entities'
                )
            )
        ),
        'configuration' => array(
            'orm_default' => array(
                'string_functions' => array(
                    'MATCH_AGAINST' => 'DoctrineExtensions\Query\MsySql\MatchAgainst'
                )
            )
        )
    )
);