<?php

namespace XT\Admin\Controller;

use XT\Core\Controller\Controller;
use XT\Core\ToolBox\MessageBox;
use Zend\Db\Adapter\Adapter;
use Zend\Router\RouteInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\ViewModel;

class AbstractPlugin extends \Zend\Mvc\Controller\Plugin\AbstractPlugin
{
    /**
     * @var AdminController
     */
    var $ctrl;

    /**
     * @var ServiceManager
     */
    var $serviceManager;



    protected $nameplugin = 'Default';
    protected $description = 'Default';

    public function infoplugin() {
        return [
            'name' => $this->nameplugin,
            'description' => $this->description
        ];
    }

    public function url($plugin = null, $act = null, $id = null)
    {  
        return $this->ctrl->buildUrl($plugin, $act, $id);
    }


    public function __invoke($servicemanager, $action = null, $id = null)
    {

        $this->serviceManager = $servicemanager;
        
        $this->intPlugin();

        if ($this->ctrl == null)
            $this->ctrl = $this->getController();


        if ($action == null)
            $action = 'index';
        return $this->$action($id);
    }

    public function createView($dir_DIR_, $name_CLASS_, $name_FUNCTION_)
    {
        /**
         * @var $templateMapResolver \Zend\View\Resolver\TemplateMapResolver
         */
        $templateMapResolver = $this->serviceManager->get('ViewTemplateMapResolver');

        $templatename = $name_CLASS_.'-'.$name_FUNCTION_;
        $templatefile = $dir_DIR_.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.$name_FUNCTION_.'.phtml';

        if (!$templateMapResolver->has($templatename))
        {
            $templateMapResolver->add($templatename, $templatefile);
        }

        $view = new ViewModel();
        $view->setTemplate($templatename);
        return $view;
    }

    public function intPlugin()
    {

    }

    public function notAdmin()
    {
        if (!$this->ctrl->isGranted('admin.index.page'))
            return MessageBox::viewNoPermission($this->ctrl->getEvent(), 'Không có quyền '.'admin.index.page');
        return false;
    }
}