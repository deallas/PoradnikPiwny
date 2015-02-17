<?php

use PoradnikPiwny\Controller\Action\AdminAction,
    PoradnikPiwny\Form\BeerManufacturerImage\Add as AddForm,
    PoradnikPiwny\Form\BeerManufacturerImage\Edit as EditForm,
    PoradnikPiwny\Entities\BeerManufacturerImage;

class BeermanufacturerimageController extends AdminAction
{
    /**
     * @var int
     */
    protected $_beerManId;
    
    /**
     * @var int
     */
    protected $_beerImgId;
    
    /**
     * @var \PoradnikPiwny\Entities\BeerManufacturer
     */
    protected $_beerMan;

    /**
     * @var \PoradnikPiwny\Entities\BeerManufacturerImage
     */
    protected $_beerManImage;
    
    public function preDispatch() {
        parent::preDispatch();

        $actionName = $this->getRequest()->getActionName();
        
        if(in_array($actionName, array('index', 'add'))) {
            $this->_beerManId = $this->_getParam('id', null);      
            $this->_beerMan = $this->_checkBeerManufacturer($this->_beerManId);
        } else {
            $this->_beerManImgId = $this->_getParam('id', null); 
            $this->_beerManImage = $this->_checkBeerManufacturerImage($this->_beerManImgId);
            $this->_beerMan = $this->_beerManImage->getBeerManufacturer();
            $this->_beerManId = $this->_beerMan->getId();
        }
    }
    
    public function postDispatch() {
        parent::postDispatch();
    
        $menu = $this->_navigation->findById('admin_beermanufacturerimage');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $this->_beerManId));
        
        $menu_add = $this->_navigation->findById('admin_beermanufacturerimage_add');
        $menu_add->setParams(array('id' => $this->_beerManId));
        
        $menu_man = $this->_navigation->findById('admin_beermanufacturer_index');
        $menu_man->setParams(array('id' => $this->_beerManId)); 
        $menu_man->setVisible(true);
    }
    
    public function indexAction()
    {   
        $rep = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturerImage');  
        $options = $this->_setupOptionsPaginator($rep);  
        $paginator = $rep->getBeerManufacturerImagesPaginator($options, $this->_beerMan, true);

        $this->view->images = $paginator;
        $this->view->beerMan = $this->_beerMan;
    }
    
    public function addAction()
    {  
        $form = new AddForm(array(
            'beerManId' => $this->_beerManId
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
                            $status = BeerManufacturerImage::STATUS_WIDOCZNY;
                        } else {
                            $status = BeerManufacturerImage::STATUS_NIEWIDOCZNY;
                        }
                        
                        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturerImage')
                                  ->addImage($form->getValue('title'), 
                                             $fileName,
                                             $form->getValue('primary'),
                                             $status,
                                             $this->_beerMan);
                        
                        $this->_helper->FlashMessenger(array('info' => 'Zdjęcie zostało dodane'));              
                        $this->_redirect('/beermanufacturerimage/index/id/' . $this->_beerManId);
                    }
                }                 
            }
        }
        
        $this->view->form = $form;
    }
    
    public function editAction()
    {
        $form = new EditForm(array(
            'beerManId' =>$this->_beerManId,
            'beerManImageId' => $this->_beerManImgId
        ));
        
        $menu = $this->_navigation->findById('admin_beermanufacturerimage_edit');
        $menu->setVisible(true);
        $menu->setParams(array('id' => $this->_beerImgId));
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($_POST)) 
            {
                if($form->getValue('status')) {
                    $status = BeerManufacturerImage::STATUS_WIDOCZNY;
                } else {
                    $status = BeerManufacturerImage::STATUS_NIEWIDOCZNY;
                }
                
                $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturerImage')
                          ->editImage($this->_beerManImage,
                                      $form->getValue('title'), 
                                      $form->getValue('primary'),
                                      $status);

                $this->_helper->FlashMessenger(array('info' => 'Zdjęcie zostało zedytowane'));              
                $this->_redirect('/beermanufacturerimage/index/id/' . $this->_beerManId);
            }
        } else {
            $form->populateByBeerManufacturerImage($this->_beerManImage);
        }
        
        $this->view->form = $form;
    }
    
    public function deleteAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturerImage')
                  ->removeImage($this->_beerManImage);
        
        $this->_helper->FlashMessenger(array('success' => 'Zdjęcie zostało usunięte'));              
        $this->_redirect('/beermanufacturerimage/index/id/' . $this->_beerManId);
    }
    
    public function setvisibleAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturerImage')
                  ->setVisible($this->_beerManImage);
        
        $this->_helper->FlashMessenger(array('success' => 'Zdjęcie jest już widoczne'));              
        $this->_redirect('/beermanufacturerimage/index/id/' .$this->_beerManId);        
    }
    
    public function setinvisibleAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturerImage')
                  ->setInvisible($this->_beerManImage);
        
        $this->_helper->FlashMessenger(array('success' => 'Zdjęcie zostało ukryte'));              
        $this->_redirect('/beermanufacturerimage/index/id/' .$this->_beerManId);        
    }
    
    public function setmainAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturerImage')
                  ->setMainImage($this->_beerManImage);
        
        $this->_helper->FlashMessenger(array('success' => 'Główne zdjęcie zostało ustawione'));              
        $this->_redirect('/beermanufacturerimage/index/id/' .$this->_beerManId);        
    }

    public function upAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturerImage')
                  ->moveUpImage($this->_beerManImage);
        
        $this->_redirect('/beermanufacturerimage/index/id/' .$this->_beerManId);
    }
    
    public function downAction()
    {
        $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturerImage')
                  ->moveDownImage($this->_beerManImage);
       
        $this->_redirect('/beermanufacturerimage/index/id/' .$this->_beerManId);
    }
    
    /* ---------------------------------------------------------------------- */
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\Beer
     */
    protected function _checkBeerManufacturer($id)
    {   
        try {
            $beer = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturer')
                              ->getBeerManufacturerById($id); 
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora wytwórcy'));              
            $this->_redirect('/beer');            
        } catch(PoradnikPiwny\Exception\BeerNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dany wytwórca nie istnieje'));
            $this->_redirect('/beer');            
        }
        
        return $beer;
    }
    
    /**
     * @param int $id
     * @return \PoradnikPiwny\Entities\BeerManufacturerImage
     */
    protected function _checkBeerManufacturerImage($id)
    {    
        try {
            $bi = $this->_em->getRepository('\PoradnikPiwny\Entities\BeerManufacturerImage')
                            ->getImageById($id, false, true);
        } catch(\PoradnikPiwny\Exception\NullPointerException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Nie podano identyfikatora zdjęcia'));              
            $this->_redirect('/beer');            
        } catch(PoradnikPiwny\Exception\BeerManufacturerImageNotFoundException $exc) {
            $this->_helper->FlashMessenger(array('warning' => 'Dane zdjęcie nie istnieje'));
            $this->_redirect('/beer');            
        }
        
        return $bi;
    }
}