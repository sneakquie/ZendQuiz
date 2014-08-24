<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }

    protected function _initRoutes()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router->addRoute(
            'Quiz_View_Question',
            new Zend_Controller_Router_Route('quiz/:question_id',
                                             array(
                                                'controller' => 'quiz',
                                                'action'     => 'view',
        )));
        $router->addRoute(
            'Quiz_Add_Question',
            new Zend_Controller_Router_Route_Static('add_question',
                                                     array(
                                                        'controller' => 'quiz',
                                                        'action'     => 'add',
        )));
    }
}