<?php
class Ifuturz_Searchbysize_Model_Mysql4_Searchbysize extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the coupon_id refers to the key field in your database table.
        $this->_init('searchbysize/searchbysize', 'searchsize_id');
    }
	
}