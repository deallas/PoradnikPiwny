<?php

use PoradnikPiwny\Controller\Action\RestAction;

class ErrorController extends RestAction
{
    public function errorAction()
    {
        if ($this->_request->hasError()) {
            $error = $this->_request->getError();
            $this->view->message = $error->message;
            $this->getResponse()->setHttpResponseCode($error->code);
            return;
        }

        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->view->message = 'Page not found';
                $this->getResponse()->setHttpResponseCode(404);
                break;

            default:
                // application error
                $this->view->message = 'Application error';
                $this->getResponse()->setHttpResponseCode(500);
                break;
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception->getMessage();
        }
    }

    public function __callAction()
    {
    }

    public function indexAction()
    {
    }

    public function getAction()
    {
    }

    public function postAction()
    {
    }

    public function putAction()
    {
    }

    public function deleteAction()
    {
    }
}