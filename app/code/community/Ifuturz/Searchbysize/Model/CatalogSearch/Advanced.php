<?php
include_once 'Mage/CatalogSearch/Model/Advanced.php';
class Ifuturz_Searchbysize_Model_CatalogSearch_Advanced extends Mage_CatalogSearch_Model_Advanced
{   
    public function addFilters($values)
    {
        $attributes     = $this->getAttributes();
        $hasConditions  = false;
        $allConditions  = array();

        foreach ($attributes as $attribute) {
            /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
            if (!isset($values[$attribute->getAttributeCode()])) {
                continue;
            }
            $value = $values[$attribute->getAttributeCode()];
			
            if ($attribute->getAttributeCode() == 'price') {
                $value['from'] = isset($value['from']) ? trim($value['from']) : '';
                $value['to'] = isset($value['to']) ? trim($value['to']) : '';
                if (is_numeric($value['from']) || is_numeric($value['to'])) {
                    if (!empty($value['currency'])) {
                        $rate = Mage::app()->getStore()->getBaseCurrency()->getRate($value['currency']);
                    } else {
                        $rate = 1;
                    }
                    if ($this->_getResource()->addRatedPriceFilter(
                        $this->getProductCollection(), $attribute, $value, $rate)
                    ) {
                        $hasConditions = true;
                        $this->_addSearchCriteria($attribute, $value);
                    }
                }
            } else if ($attribute->isIndexable()) {
                if (!is_string($value) || strlen($value) != 0) {
                    if ($this->_getResource()->addIndexableAttributeModifiedFilter(
                        $this->getProductCollection(), $attribute, $value)) {
                        $hasConditions = true;
                        $this->_addSearchCriteria($attribute, $value);
                    }
                }
            } else {
				
				
                $condition = $this->_prepareCondition($attribute, $value);
                if ($condition === false) {
                    continue;
                }

                $this->_addSearchCriteria($attribute, $value);

                $table = $attribute->getBackend()->getTable();
                if ($attribute->getBackendType() == 'static'){
                    $attributeId = $attribute->getAttributeCode();
                } else {
                    $attributeId = $attribute->getId();
                }
                $allConditions[$table][$attributeId] = $condition;
            }
        }
		
        /*if (($allConditions)*/
		if (($allConditions) || (isset($values['category']) && is_numeric($values['category']))) {
           	$this->getProductCollection()->addFieldsToFilter($allConditions);			
        } else if (!$hasConditions) {
            Mage::throwException(Mage::helper('catalogsearch')->__('Please specify at least one search term.'));
        }
		
        return $this;
    }

  
    public function getSearchCriterias()
    {       
		 $search = $this->_searchCriterias;
        /* display category filtering criteria */
        if(isset($_GET['category']) && is_numeric($_GET['category'])) {
            $category = Mage::getModel('catalog/category')->load($_GET['category']);
            $search[] = array('name'=>'Category','value'=>$category->getName());
        }
        return $search;
    }
   
    public function getProductCollection()
	{      
		if (is_null($this->_productCollection))
		{
            $this->_productCollection = Mage::getResourceModel('catalogsearch/advanced_collection')
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addStoreFilter();
                Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($this->_productCollection);
                Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($this->_productCollection);
            /* include category filtering */
            if(isset($_GET['category']) && is_numeric($_GET['category'])) $this->_productCollection->addCategoryFilter(Mage::getModel('catalog/category')->load($_GET['category']),true);
        }
 
        return $this->_productCollection;
    }

}
