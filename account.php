<?php
/**
 * This file is used for Excite Holidays test only. All rights reserved.
 * Author: Gordon Ye 
 * Create Date: 2014-04-30
 * Update Date:
**/

class account {
	private $_account_id = '';
	private $_db; 
	private $settings = array();
	private static $_Object; 
	
    /**
     * constructor : set up the variables
     *
     * @param dbobject $db db_class object

     * @return object
     */
	function __construct(db_class $db, $account_id=1)
	{
		$this->_account_id = $account_id;
		$this->_db = $db;
		$this->_getAccountInfo();
		self::$_Object = $this;
		return self::$_Object;
	}

    /**
     * Get the module static object
     *
     * @return self
     */
    public static function getInstance(db_class $db) 
    {
    	$class = __CLASS__;
    	if (!isset(self::$_Object)) {
    		return new $class($db);
    	}	
    	return self::$_Object;
    }
	    
	/**
     * Get a specific account information such as account balance and transaction history via a join table
     *
     * @return settings
     */
	public function _getAccountInfo()
	{
		$sql = 'SELECT * FROM account WHERE id=' . $this->_account_id;	  
		$pResults = $this->_db->select($sql);
		if($row=$this->_db->get_row($pResults, 'MYSQL_ASSOC')) 
		$this->settings = array();
		foreach($row as $var => $value) {
			$this->settings[$var] = $value;
		}
	}
	
	
	public function update_account($fieldArray)
    {
		$balance = isset($fieldArray["balance"])?$fieldArray["balance"]:0;
  		$sql = 'UPDATE account SET balance=' . $balance . ' WHERE id= 1';
		$this->_db->update_sql($sql);  
    }
	
	
	
    /**
     * Magic Get
     *
     * @param string $property Property name
     *
     * @return mixed
     */
    final public function __get($property)
    {
        return $this->__getProperty($property);
    }

    /**
     * Magic Set
     *
     * @param string $property Property name
     * @param mixed $value New value
     *
     * @return self
     */
    final public function __set($property, $value)
    {
        return $this->__setProperty($property, $value);
    }

/**
     * Get Property
     *
     * @param string $property Property name
     *
     * @return mixed
     */
    protected function __getProperty($property)
    {
        $value = null;

        $methodName = '__getVal' . ucwords($property);
        if(method_exists($this, $methodName)) {
            $value = call_user_func(array($this, $methodName));
        } else {
        	if (isset($this->settings[$property])) {
        		$value = $this->settings[$property];
        	}
        }

        return $value;
    }
	
    /**
     * Set Property
     *
     * @param string $property Property name
     * @param mixed $value Property value
     *
     * @return self
     */
	final protected function __setProperty($property, $value)
    {
        $methodName = '__setVal' . ucwords($property);
        if(method_exists($this, $methodName)) {
            call_user_func(array($this, $methodName), $value);
        } else {
       		$this->settings[$property] = $value;
        }
            
        return $this;
    }
    
	/**
     * Display the object 
     *
     * @return void
     */
    public function printMe() {
		echo '<br />';
		echo '<pre>';
		print_r ($this);
		echo '</pre>';
	}
}


