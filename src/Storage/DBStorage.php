<?php

/**
 * This file is part of the DBSessionStorage Module (https://github.com/Nitecon/DBSessionStorage.git)
 *
 * Copyright (c) 2013 Will Hattingh (https://github.com/Nitecon/DBSessionStorage.git)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.txt that was distributed with this source code.
 */

namespace DBSessionStorage\Storage;

use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Zend\Db\Adapter\Adapter;
use Zend\Session\SessionManager;
use Zend\Session\Container;

class DBStorage
{

    protected $adapter;
    protected $tblGW;
    protected $sessionConfig;

    public function __construct(Adapter $adapter, $session_config)
    {
        $this->adapter = $adapter;
        $this->sessionConfig = $session_config;
        $this->tblGW = new \Zend\Db\TableGateway\TableGateway('sessions', $this->adapter);
    }

    public function setSessionStorage()
    {
        $gwOpts = new DbTableGatewayOptions();
        $gwOpts->setDataColumn('data');
        $gwOpts->setIdColumn('id');
        $gwOpts->setLifetimeColumn('lifetime');
        $gwOpts->setModifiedColumn('modified');
        $gwOpts->setNameColumn('name');



        $saveHandler = new DbTableGateway($this->tblGW, $gwOpts);
        $sessionManager = new SessionManager();
        if ($this->sessionConfig) {
            $sessionConfig = new \Zend\Session\Config\SessionConfig();
            $sessionConfig->setOptions($this->sessionConfig);
            $sessionManager->setConfig($sessionConfig);
        }
        $sessionManager->setSaveHandler($saveHandler);
        Container::setDefaultManager($sessionManager);
        $sessionManager->start();
    }
}
