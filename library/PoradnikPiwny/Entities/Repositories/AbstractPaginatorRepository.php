<?php

namespace PoradnikPiwny\Entities\Repositories;

use Doctrine\ORM\EntityRepository,
    PoradnikPiwny\Entities\User;

abstract class AbstractPaginatorRepository extends EntityRepository
{
    /**
     * @var \PoradnikPiwny\Entities\Repositories\UsersRepository
     */
    private $rUser;
    
    public function getPaginatorOptions(User $user)
    {
        $this->rUser = $this->_em->getRepository('\PoradnikPiwny\Entities\User');
        $result = $this->rUser->getMetadataByKeyAndUser($this->getOptionsPaginatorName(), $user);
        if($result == null) 
        {
            return $this->_getDefaultPaginatorOptions();
        }
        
        return unserialize($result->getValue());
    }
    
    public function setPaginatorOptions(User $user, $orders, $order, $items, $desc)
    {
        $options = array(
            'orders' => $orders,
            'order' => $order,
            'items' => $items,
            'desc' => $desc,
            'page' => $this->getDefaultPage()
        );

        $f_options = $this->_filterPaginatorOptions($options);
        
        $this->rUser = $this->_em->getRepository('\PoradnikPiwny\Entities\User');
        $this->rUser->setMetadataByKeyAndUser($this->getOptionsPaginatorName(), 
                                              $user,
                                              serialize($f_options));
        
        return $f_options;
    }
    
    public function clearPaginatorOptions(User $user)
    {
        $this->rUser = $this->_em->getRepository('\PoradnikPiwny\Entities\User');
        $this->rUser->removeMetadataByKeyAndUser($this->getOptionsPaginatorName(), $user);
        
        return $this->_getDefaultPaginatorOptions();
    }
    
    /**
     * @param array $dbOptions
     * @param array $customOptions
     * @return array
     */
    public function mergePaginatorOptions($dbOptions, $customOptions)
    {
        $options = $dbOptions;
        if($customOptions['order'] != null) {
            $options['order'] = $customOptions['order'];
        }
        if($customOptions['page'] != null) {
            $options['page'] = $customOptions['page'];
        }
        if($customOptions['items'] != null) {
            $options['items'] = $customOptions['items'];
        }
        if($customOptions['desc'] != null) {
            $options['desc'] = $customOptions['desc'];
        }
        
        return $this->_filterPaginatorOptions($options);
    }
    
    public function getDefaultItems()
    {
        return 10;
    }
    
    public function getDefaultPage()
    {
        return 1;
    }
    
    public function getDefaultDesc()
    {
        return false;
    }
    
    private function _getDefaultPaginatorOptions()
    {
        return array(
            'orders' => $this->getDefaultOrders(),
            'order' => $this->getDefaultOrder(),
            'page' => $this->getDefaultPage(),
            'desc' => $this->getDefaultDesc(),
            'items' => $this->getDefaultItems()
        );
    }
    
    private function _filterPaginatorOptions($options)
    {
        if(!in_array($options['order'], $this->getAvailableOrders())) {
            $options['order'] = $this->getDefaultOrder();
        }
        
        if(!in_array($options['items'], $this->getAvailableItems())) {
            $options['items'] = $this->getDefaultItems();
        }
        if(is_array($options['orders'])) {
            $options['orders'] = array_intersect($options['orders'], $this->getAvailableOrders());
        } else {
            $options['orders'] = $this->getDefaultOrders();
        }

        $options['desc'] = (bool)$options['desc'];
        
        return $options;
    }
    
    abstract public function getAvailableOrders();
    abstract public function getDefaultOrders();
    abstract public function getAvailableItems();
    abstract public function getOptionsPaginatorName();
    abstract public function getDefaultOrder();
}
