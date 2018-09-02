<?php

namespace XT\Admin\Controller;

use XT\Core\Common\Common;
use XT\Core\Controller\Controller;
use XT\Core\ToolBox\MessageBox;
use Zend\Mvc\Service\ControllerPluginManagerFactory;
use Zend\Router\RouteInterface;
use Zend\View\Model\ViewModel;

class AdminController extends Controller
{
    /**
     * @var RouteInterface
     */
    var $urladmin;
    /**
     * @var \Zend\Mvc\Controller\PluginManager
     */
    protected $plugin;
    protected $admin_plugins;
    protected $serviceManager;

    const RBAC_ADMIN_INDEX_PAGE = 'admin.index.page';

    public function __invoke($sm)
    {
        $this->urladmin = $sm->get('Router')->getRoute('admin');

        $this->plugin = $sm->get(ControllerPluginManagerFactory::PLUGIN_MANAGER_CLASS);
        $cf = $sm->get('config');
        if (isset($cf['admin_plugins']))
        {
            $this->admin_plugins = $cf['admin_plugins'];
        }
        else
            $this->admin_plugins = [];


        foreach ($this->admin_plugins as $namefunction => $pluginclass)
        {
            $this->plugin->setInvokableClass($namefunction, $pluginclass);
        }

        return $this->init($sm);
    }

    public function indexAction()
    {
        $plugin = $this->params()->fromRoute('plugin');
        $act    = $this->params()->fromRoute('act');
        $id     = $this->params()->fromRoute('id');
        Common::defaultHeader();


        if (($plugin != null) && (isset($this->admin_plugins[$plugin])))
        {
            
            return $this->$plugin($this->serviceManager, $act, $id);
        }
        else if ($plugin != null)
        {
            return MessageBox::viewNoPermission($this->getEvent(), $plugin. ' not registered!');
        }


        $notallow = $this->notAdmin();
        if ($notallow) return $notallow;

        $ar = [];
        foreach ($this->admin_plugins as $key => $admin_plugin) {
            $ar[$key] = $this->$key($this->serviceManager, 'infoplugin', 0);
        }

        
        return new ViewModel(['list' => $ar]);


    }
    public function buildUrl($plugin = null, $act = null, $id = null)
    {
        $options = [];
        if ($plugin)    $options['plugin'] = $plugin;
        if ($act)       $options['act'] = $act;
        if ($id)        $options['id'] = $id;

        return $this->urladmin->assemble($options);
    }

    public function notAdmin()
    {
        if (!$this->isGranted('admin.index.page'))
            return MessageBox::viewNoPermission($this->getEvent(), 'Không có quyền '.'admin.index.page');
        return false;
    }




}