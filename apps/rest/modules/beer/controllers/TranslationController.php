<?php

use PoradnikPiwny\Controller\Action\RestAction;

class Beer_TranslationController extends RestAction
{   
    public function getAction()
    {
        $beerId = $this->_getParam('beer_id', null);
        try {
            $this->_em->getRepository('\PoradnikPiwny\Entities\Beer')
                      ->getBeerById($beerId, false, true);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->view->msg = self::NULL_POINTER;
            $this->_response->badRequest();
            return;
        } catch(\PoradnikPiwny\Exception\BeerNotFoundException $exc) {
            $this->view->msg = self::BEER_NOT_FOUND;
            $this->_response->notFound();
            return;
        }
        
        /* @var $locale \Zend_Locale */
        $locale = $this->getFrontController()->getParam('bootstrap')->getResource('locale');
        $lang = $locale->getLanguage();
        
        /* @var $translation \PoradnikPiwny\Entities\BeerTranslation */
        $translation = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerTranslation')
                                 ->getBeerTranslationByBeerIdAndLang($beerId, $lang);
        
        $this->view->translation = $translation->toArray(array(
            'description',
            'lang'
        ));
    }
}