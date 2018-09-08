<?php
namespace XT\Admin\Controller;


use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\AbstractPluginManager;

class AdminPluginManager extends PluginManager
{
    protected $factories = [];
    protected $aliases = [];


}