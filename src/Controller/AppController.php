<?php

/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Application level Controller.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * phpMyAdmin Error reporting server
 * Copyright (c) phpMyAdmin project (https://www.phpmyadmin.net/)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) phpMyAdmin project (https://www.phpmyadmin.net/)
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 *
 * @see      https://www.phpmyadmin.net/
 */

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Application Controller.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @see    http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $uses = array('Developer', 'Notification');

    public $whitelist = array(
        'Developers',
        'Pages',
        'Incidents' => array(
            'create',
        ),
    );

    public $css_files = array(
        'jquery.dataTables.min',
        'jquery.jqplot.min',
        'jquery.dataTables_themeroller',
        'bootstrap.min',
        'bootstrap.min.css',
        'shCore',
        'shThemeDefault',
        'custom'
    );

    public $js_files = array(
        'jquery.min',
        'jquery.dataTables.min',
        'bootstrap.min',
        'shCore',
        'shBrushXml',
        'shBrushJScript',
        'shBrushPhp',
        'raphael-min',
        'g.raphael-min',
        'g.pie-min',
        'g.line-min',
        'g.bar-min',
        'g.dot-min',
        'jquery.jqplot.min.js',
        'jqplot.barRenderer.min.js',
        'jqplot.highlighter.min.js',
        'jqplot.dateAxisRenderer.min.js',
        'jqplot.categoryAxisRenderer.min.js',
        'jqplot.pointLabels.min.js',
        'jqplot.canvasTextRenderer.min.js',
        'jqplot.canvasAxisTickRenderer.min.js',
        'jqplot.cursor.min.js',
        'pie',
        'custom'
    );

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Flash');
        /*  $this->loadComponent(
                'Auth', [
                    'loginAction' => [
                        'controller' => 'Developer',
                        'action' => 'login'
                    ],
                    'authError' => 'Did you really think you are allowed to see that?',
                    'authenticate' => [
                        'Form' => [
                            'fields' => ['username' => 'email']
                        ]
                    ]
                ]
            );
        */
    }

    public function beforeFilter(Event $event)
    {
        $controller = $this->request->controller;
        $this->set('current_controller', $controller);
        $notif_count = 0;

        if ($this->request->session()->read('Developer.id')) {
            $current_developer = TableRegistry::get('Developers')->
                    findById($this->request->session()->read('Developer.id'))->all()->first();

            $notif_count = TableRegistry::get('Notifications')->find(
                'all',
                array(
                    'conditions' => array('developer_id' => intval($current_developer['id'])),
                )
            )->count();
            $this->set('current_developer', $current_developer);
            $this->set('developer_signed_in', true);
        } else {
            $this->set('developer_signed_in', false);
            $this->_checkAccess();
        }
        $this->set('notif_count', $notif_count);
        $this->set('css_files', $this->css_files);
        $this->set('js_files', $this->js_files);
    }

    protected function _checkAccess()
    {
        $controller = $this->request->controller;
        $action = $this->request->action;

        if (in_array($controller, $this->whitelist)) {
            return;
        }
        if (isset($this->whitelist[$controller]) &&
                in_array($action, $this->whitelist[$controller])) {
            return;
        }
        $flash_class = 'alert';
        $this->Flash->default('You need to be signed in to do this',
            array('params' => array('class' => $flash_class)));

        // save the return url
        $ret_url = Router::url($this->here, true);
        $this->request->session()->write('last_page', $ret_url);

        return $this->redirect('/');
    }
}
