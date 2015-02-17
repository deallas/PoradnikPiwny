<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\BeerImage\Add as AddForm,
    PoradnikPiwny\Form\BeerImage\Edit as EditForm,
    PoradnikPiwny\Entities\BeerImage;

class BeerimageController extends AdminAction
{
    /**
     * @var int
     */
    protected $_beerId;
    
    /**
     * @var int
     */
    protected $_beerImgId;
    
    /**
     * @var \PoradnikPiwny\Entities\Beer
     */
    protected $_beer;

    /**
     * @var \PoradnikPiwny\Entities\BeerImage
     */
    protected $_beerImage;
    
    public function preDispatch() {
        parent::preDispatch();

        $actionName = $this->getRequest()->getActionName();
        
        if(in_array($actionName, array('index', 'add'))) {
            $this->_beerId = $this->_getParam('id', null);      
            $this->_beer = $this->_checkBeer($this->_beerId);
        } else {
            $this->_beerImgId = $this->_getParam('id', null); 
            $this->_beerImage = $this->_checkBeerImage($this->_beerImgId);
            $this->_beer = $this->_beerImage->getBeer();
            $this->_beerId = $this->_beer->getId();
        }
    }
    
    public function postDispatch() {
        parent::postDispatch();
    
        $menu = $this->_navigation->findById('admin_beerimage');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $this->_beerId));
        
        $menu_add = $this->_navigation->findById('admin_beerimage_add');
        $menu_add->setParams(array('id' => $this->_beerId));
    }
    
    public function indexAction()
    {   
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage');  
        $options = $this->_setupOptionsPaginator($rep);  
        $paginator = $rep->getBeerImagesPaginator($options, $this->_beer, true);

        $this->view->images = $paginator;
        $this->view->beer = $this->_beer;
    }
    
    public function addAction()
    {  
        $form = new AddForm(array(
            'beerId' => $this->_beerId
        ));
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST))
            {
                $upload = new \Zend_File_Transfer_Adapter_Http();
                $dir = UPLOAD_PATH . '/images';
                $upload->setDestination($dir);
                $files = $upload->getFileInfo();
                
                foreach($files as $file)
                {
                    $fileName = md5(time().$file['name']).strrchr($file['name'], '.');
                    $upload->addFilter('Rename', array('target' => $fileName, 'overwrite' => true));
                    if($upload->receive($file['name']))
                    {
                        if($form->getValue('status')) {
                            $status = BeerImage::STATUS_WIDOCZNY;
                        } else {
                            $status = BeerImage::STATUS_NIEWIDOCZNY;
                        }
                        
                        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                                  ->addImage($form->getValue('title'), 
                                             $fileName,
                                             $form->getValue('primary'),
                                             $status,
                                             $this->_beer);
                        
                        $this->_helper->FlashMessenger(array('info' => 'Zdjęcie zostało dodane'));              
                        $this->_redirect('/beerimage/index/id/' . $this->_beerId);
                    }
                }                 
            }
        }
        
        $this->view->form = $form;
    }
    
    public function editAction()
    {
        $form = new EditForm(array(
            'beerId' => $this->_beerId,
            'beerImageId' => $this->_beerImgId
        ));
        
        $menu = $this->_navigation->findById('admin_beerimage_edit');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $this->_beerImgId));
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST)) 
            {
                if($form->getValue('status')) {
                    $status = BeerImage::STATUS_WIDOCZNY;
                } else {
                    $status = BeerImage::STATUS_NIEWIDOCZNY;
                }
                
                $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                          ->editImage($this->_beerImage,
                                      $form->getValue('title'), 
                                      $form->getValue('primary'),
                                      $status);

                $this->_helper->FlashMessenger(array('info' => 'Zdjęcie zostało zedytowane'));              
                $this->_redirect('/beerimage/index/id/' . $this->_beerId);
            }
        } else {
            $form->populateByBeerImage($this->_beerImage);
        }
        
        $this->view->form = $form;
    }
    
    public function deleteAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                  ->removeImage($this->_beerImage);
        
        $this->_helper->FlashMessenger(array('success' => 'Zdjęcie zostało usunięte'));              
        $this->_redirect('/beerimage/index/id/' . $this->_beerId);
    }
    
    public function setvisibleAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                  ->setVisible($this->_beerImage);
        
        $this->_helper->FlashMessenger(array('success' => 'Zdjęcie jest już widoczne'));              
        $this->_redirect('/beerimage/index/id/' . $this->_beerId);        
    }
    
    public function setinvisibleAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                  ->setInvisible($this->_beerImage);
        
        $this->_helper->FlashMessenger(array('success' => 'Zdjęcie zostało ukryte'));              
        $this->_redirect('/beerimage/index/id/' . $this->_beerId);        
    }
    
    public function setmainAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                  ->setMainImage($this->_beerImage);
        
        $this->_helper->FlashMessenger(array('success' => 'Główne zdjęcie zostało ustawione'));              
        $this->_redirect('/beerimage/index/id/' . $this->_beerId);        
    }

    public function upAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                  ->moveUpImage($this->_beerImage);
        
        $this->_redirect('/beerimage/index/id/' . $this->_beerId);
    }
    
    public function downAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                  ->moveDownImage($this->_beerImage);
       
        $this->_redirect('/beerimage/index/id/' . $this->_beerId);
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\Beer
     */
    protected function _checkBeer($id)
    {   
        try {
            $beer = $this->_em->getRepository('\PoradnikPiwny\Entities\Beer')
                              ->getBeerById($id); 
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora piwa'));              
            $this->_redirect('/beer');            
        } catch(PoradnikPiwny\Exception\BeerNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dane piwo nie istnieje'));
            $this->_redirect('/beer');            
        }
        
        return $beer;
    }
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\BeerImage
     */
    protected function _checkBeerImage($id)
    {    
        try {
            $bi = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerImage')
                            ->getImageById($id, false, true);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora zdjęcia'));              
            $this->_redirect('/beer');            
        } catch(PoradnikPiwny\Exception\BeerImageNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dane zdjęcie nie istnieje'));
            $this->_redirect('/beer');            
        }
        
        return $bi;
    }
}