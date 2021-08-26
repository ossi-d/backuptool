<?php

function getDbConf()
{
    $khwcrm = [
        'host' => 'hostName',
        'port' => 8889,
        'db'   => 'dbName',
        'user' => 'userName',
        'pwd'  => 'password'
    ];

    $khwcms = [
        'host' => 'hostName',
        'port' => 'portNumber',
        'db'   => 'dbName',
        'user' => 'userName',
        'pwd'  => 'password'
    ];

    return $config = [
        'crm' => $khwcrm,
        'cms' => $khwcms
    ];
}





