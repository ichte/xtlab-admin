<?php

namespace XT\Admin\Admin\TestPlugin;

 

use XT\Admin\Controller\AbstractPlugin;
use Zend\Db\Adapter\Adapter;

class HelloWorld extends AbstractPlugin
{

    function hi($id)
    {

        
        $db = $this->serviceManager->get(Adapter::class);
        var_dump($db);
        echo 'This is Plugin Admin '.$id;
        die;


    }


}