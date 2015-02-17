<?php

namespace WS\Auth\Adapter;

use PoradnikPiwny\Security;

class Doctrine implements \Zend_Auth_Adapter_Interface
{
    /**
     * Doctrine Entity Manager
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em = null;
    
    /**
     * The entity name to check for an identity.
     *
     * @var string
     */
    protected $_entityName;

    /**
     * $_identityColumn - the column to use as the identity
     *
     * @var string
     */
    protected $_identityColumn = null;

    /**
     * $_credentialColumn - columns to be used as the credentials
     *
     * @var string
     */
    protected $_credentialColumn = null;

    /**
     * $_saltColumn
     * 
     * @var string
     */
    protected $_saltColumn = null;

    /**
     * @var string
     */
    protected $_inheritRoleColumn = null;
    
    /**
     * $_identity - Identity value
     *
     * @var string
     */
    protected $_identity = null;

    /**
     * $_credential - Credential values
     *
     * @var string
     */
    protected $_credential = null;
    
    /**
     * $_authenticateResultInfo
     *
     * @var array
     */
    protected $_authenticateResultInfo = null;
    
    /**
     * @var mixed 
     */
    protected $_resultRow = null;
    
    /**
     * @var int
     */
    protected $_inheritRole = Security::ROLE_GUEST;
    
    /**
     * __construct() - Sets configuration options
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param string $entityName
     * @param string $identityColumn
     * @param string $credentialColumn
     * @param string $credentialTreatment
     * @return void
     */
    public function __construct($em = null, $entityName = null, $identityColumn = null,
                                $credentialColumn = null)
    {
        if (null !== $em) {
            $this->setEm($em);
        }

        if (null !== $entityName) {
            $this->setEntityName($entityName);
        }

        if (null !== $identityColumn) {
            $this->setIdentityColumn($identityColumn);
        }

        if (null !== $credentialColumn) {
            $this->setCredentialColumn($credentialColumn);
        }
    }
    
    /**
     *
     * setEm() - set the Doctrine2 Entity Manager
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEm($em)
    {
        $this->_em = $em;
        return $this;
    }
    
    /**
     * setEntityName() - set the entity name to be used in the select query
     *
     * @param string $entityName
     * @return WS\Auth\Adapter\Doctrine Provides a fluent interface
     */
    public function setEntityName($entityName)
    {
        $this->_entityName = $entityName;
        return $this;
    }

    /**
     * setIdentityColumn() - set the column name to be used as the identity column
     *
     * @param string $identityColumn
     * @return WS\Auth\Adapter\Doctrine Provides a fluent interface
     */
    public function setIdentityColumn($identityColumn)
    {
        $this->_identityColumn = $identityColumn;
        return $this;
    }

    /**
     * setCredentialColumn() - set the column name to be used as the credential column
     *
     * @param string $credentialColumn
     * @return WS\Auth\Adapter\Doctrine Provides a fluent interface
     */
    public function setCredentialColumn($credentialColumn)
    {
        $this->_credentialColumn = $credentialColumn;
        return $this;
    }

    /**
     * @param string $saltColumn
     * @return \WS\Auth\Adapter\Doctrine
     */
    public function setSaltColumn($saltColumn)
    {
        $this->_saltColumn = $saltColumn;
        return $this;
    }
    
    /**
     * @param string $inheritRoleColumn
     * @return \WS\Auth\Adapter\Doctrine
     */
    public function setInheritRoleColumn($inheritRoleColumn)
    {
        $this->_inheritRoleColumn = $inheritRoleColumn;
        return $this;
    }
    
    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param string $value
     * @return WS\Auth\Adapter\Doctrine Provides a fluent interface
     */
    public function setIdentity($value)
    {
        $this->_identity = $value;
        return $this;
    }

    /**
     * setCredential() - set the credential value to be used
     *
     * @param string $credential
     * @return WS\Auth\Adapter\Doctrine Provides a fluent interface
     */
    public function setCredential($credential)
    {
        $this->_credential = $credential;
        return $this;
    }

    /**
     * @param int $inheritRole
     * @return \WS\Auth\Adapter\Doctrine
     */
    public function setInheritRole($inheritRole = null)
    {
        $this->_inheritRole = $inheritRole;
        
        return $this;
    }
    
    /**
     * authenticate() - defined by \Zend_Auth_Adapter_Interface. This method is called to
     * attempt an authentication. Previous to this call, this adapter would have already
     * been configured with all necessary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws \Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return \Zend_Auth_Result
     */
    public function authenticate()
    {
        $this->_authenticateSetup();
        $query = $this->_getQuery();
        $resultIdentities = $this->_performQuery($query);

        $authResult = $this->_validateResult($resultIdentities);
        return $authResult;
    }

    /**
     * _authenticateSetup() - This method abstracts the steps involved with making sure
     * that this adapter was indeed setup properly with all required peices of information.
     *
     * @throws \Zend_Auth_Adapter_Exception - in the event that setup was not done properly
     * @return true
     */
    protected function _authenticateSetup()
    {
        $exception = null;

        if ($this->_em === null) {
            $exception = 'A database connection was not set, nor could one be created.';
        } elseif ($this->_entityName == '') {
            $exception = 'A entity name must be supplied for the WS\Auth\Adapter\Doctrine authentication adapter.';
        } elseif ($this->_identityColumn == '') {
            $exception = 'An identity column must be supplied for the WS\Auth\Adapter\Doctrine authentication adapter.';
        } elseif ($this->_credentialColumn == '') {
            $exception = 'A credential column must be supplied for the WS\Auth\Adapter\Doctrine authentication adapter.';
        } elseif ($this->_identity == '') {
            $exception = 'A value for the identity was not provided prior to authentication with WS\Auth\Adapter\Doctrine.';
        } elseif ($this->_credential === null) {
            $exception = 'A credential value was not provided prior to authentication with WS\Auth\Adapter\Doctrine.';
        }

        if (null !== $exception) {
            throw new \Zend_Auth_Adapter_Exception($exception);
        }
        
        $this->_authenticateResultInfo = array(
            'code' => \Zend_Auth_Result::FAILURE,
            'identity' => $this->_identity,
            'messages' => array()
            );
            
        return true;
    }

    /**
     * _getQuery() - This method creates a Doctrine\ORM\Query object that
     * is completely configured to be queried against the database.
     *
     * @return \Doctrine\ORM\Query
     */
    protected function _getQuery()
    {
        if(is_string($this->_identityColumn)) {
            $ic = array($this->_identityColumn);
        } else {
            $ic = $this->_identityColumn;
        }
        
        $dql = 'SELECT u FROM ' . $this->_entityName . ' u  WHERE ';
        $params = array();
        foreach($ic as $col) {
            $params[] = 'u.' . $col . ' = ?1';
        }
        $dql .= implode(' OR ', $params);
        
        $query = $this->_em->createQuery($dql)
                            ->setParameter(1, $this->_identity);
                            
        return $query;
    }

    /**
     * _performQuery() - This method accepts a Doctrine\ORM\Query object and
     * performs a query against the database with that object.
     *
     * @param \Doctrine\ORM\Query $query
     * @throws \Zend_Auth_Adapter_Exception - when a invalid select object is encoutered
     * @return array
     */
    protected function _performQuery(\Doctrine\ORM\Query $query)
    {
        try {
            $resultIdentities = $query->execute();
        } catch (\Exception $e) {
            throw new \Zend_Auth_Adapter_Exception('The supplied parameters to \Doctrine\ORM\EntityManager failed to '
                                                . 'produce a valid sql statement, please check entity and column names '
                                                . 'for validity.');
        }
        return $resultIdentities;
    }

    /**
     * _validateResult() - This method attempts to validate that the record in the
     * result set is indeed a record that matched the identity provided to this adapter.
     *
     * @param array $resultIdentities
     * @return \Zend_Auth_Result
     */
    protected function _validateResult($resultIdentities)
    {
        if (count($resultIdentities) < 1) {
            $this->_authenticateResultInfo['code'] = \Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
            $this->_authenticateResultInfo['messages'][] = 'A record with the supplied identity could not be found.';
            return $this->_authenticateCreateAuthResult();
        } elseif (count($resultIdentities) > 1) {
            $this->_authenticateResultInfo['code'] = \Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS;
            $this->_authenticateResultInfo['messages'][] = 'More than one record matches the supplied identity.';
            return $this->_authenticateCreateAuthResult();
        } elseif (count($resultIdentities) == 1) {
            $resultIdentity = $resultIdentities[0];
            
            /** @todo Uwględnienie uprawnień podczas logowania */
            
            /*
            $lr_getter = new \ReflectionMethod($resultIdentity, 'get' . ucfirst($this->_inheritRoleColumn));
            $role = $lr_getter->invoke($resultIdentity);

            if($level < $this->_minPermissionLevel) {
                $this->_authenticateResultInfo['code'] = \Zend_Auth_Result::FAILURE_UNCATEGORIZED;
            } else {*/       
                $h_getter = new \ReflectionMethod($resultIdentity, 'get' . ucfirst($this->_credentialColumn));              
                $hash = $h_getter->invoke($resultIdentity);

                $password = $this->_credential; 
                if($this->_saltColumn != null)
                {
                    $s_getter = new \ReflectionMethod($resultIdentity, 'get' . ucfirst($this->_saltColumn));
                    $password .= $s_getter->invoke($resultIdentity);
                }

                if (Security::getInstance()->createPassword($password) != $hash) {
                    $this->_authenticateResultInfo['code'] = \Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
                    $this->_authenticateResultInfo['messages'][] = 'Supplied credential is invalid.';
                } else {
                    $this->_authenticateResultInfo['code'] = \Zend_Auth_Result::SUCCESS;
                    $this->_authenticateResultInfo['identity'] = $this->_identity;
                    $this->_authenticateResultInfo['messages'][] = 'Authentication successful.';
                    $this->_resultRow = $resultIdentity;
                }
            //}
        } else {
            $this->_authenticateResultInfo['code'] = \Zend_Auth_Result::FAILURE_UNCATEGORIZED;
        }

        return $this->_authenticateCreateAuthResult();
    }
    
    /**
     * _authenticateCreateAuthResult() - This method creates a \Zend_Auth_Result object
     * from the information that has been collected during the authenticate() attempt.
     *
     * @return \Zend_Auth_Result
     */
    protected function _authenticateCreateAuthResult()
    {
        return new \Zend_Auth_Result(
            $this->_authenticateResultInfo['code'],
            $this->_authenticateResultInfo['identity'],
            $this->_authenticateResultInfo['messages']
            );
    }
    
    /**
     * @return stdClass|boolean
     */
    public function getResultRowObject()
    {
        if (!$this->_resultRow) {
            return false;
        }
        
        return $this->_resultRow;
    }
}