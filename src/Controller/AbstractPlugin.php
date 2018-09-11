<?php

namespace XT\Admin\Controller;

use XT\Admin\Exception\BadMethodCallException;
use XT\Core\Common\Common;
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

    /**
     * @var \XT\Db\Adapter
     */
    public $dbAdapter;



    protected $nameplugin = 'Default';
    protected $description = 'Default';

    protected $listaction = [];

    /**
     * @return array
     */
    public function getListactionIndex()
    {
        $ar = [];
        foreach ($this->listaction as $key => $item) {
            if ($item['index'])
                $ar[$key] = $item;
        }
        return $ar;
    }
    /**
     * @return array
     */
    public function getListaction()
    {
        return $this->listaction;
    }

    /**
     * @param array $listaction
     */
    public function setListaction($listaction)
    {
        $this->listaction = $listaction;
    }




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

        $this->dbAdapter = $servicemanager->get(\Zend\Db\Adapter\Adapter::class);

        $this->intPlugin();

        if ($this->ctrl == null)
            $this->ctrl = $this->getController();


        if ($action == null)
            $action = 'index';


        if (method_exists($this,$action))
            return $this->$action($id);
        else
            throw new BadMethodCallException("$action not found in " . __CLASS__ );

    }

    public function createView($dir_DIR_, $name_CLASS_, $name_FUNCTION_, $name_action = null)
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
        if (!$templateMapResolver->has('dropdown_breadcrumbs'))
        {
            $templateMapResolver->add('dropdown_breadcrumbs', __DIR__.'/../view/dropdown_breadcrumbs.phtml');
        }

        $view = new ViewModel();

        $listplugins = $this->getController()->getListplugins();


        $view->setVariable('listplugins' , $listplugins);
        $view->setVariable('currentNameplugin' , $this->nameplugin);
        $view->setVariable('listactions' , $this->getListaction());
        $view->setVariable('currentNameaction' , ($name_action != null) ? $name_action : $name_FUNCTION_);





        $view->setTemplate($templatename);
        return $view;
    }

    public function intPlugin()
    {

    }

    public function notAllow($granted) {
        if (!$this->ctrl->isGranted($granted))
            return MessageBox::viewNoPermission($this->ctrl->getEvent(),
                Common::translate('Not permission granted'). ' : '. $granted);
        return false;

    }

    public function notAdmin()
    {
        if (!$this->ctrl->isGranted('admin.index.page'))
            return MessageBox::viewNoPermission($this->ctrl->getEvent(), 'Không có quyền '.'admin.index.page');
        return false;
    }
}