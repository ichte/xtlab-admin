<?php

namespace XT\Admin\Admin\TestPlugin;

 

use XT\Admin\Controller\AbstractPlugin;

class HelloWorld extends AbstractPlugin
{

    function hi($id)
    {

        echo 'This is Plugin Admin '.$id;
        die;
    }

    
}