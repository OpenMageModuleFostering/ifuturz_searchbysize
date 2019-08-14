<?php
class Ifuturz_Searchbysize_Adminhtml_SearchbysizeController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->loadLayout()
			->_setActiveMenu('searchbysize')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Search By Size'), Mage::helper('adminhtml')->__('Coupon Category Management'));
		
		return $this;
	}
	
	public function indexAction() 
	{
		$this->_initAction()
			->renderLayout();
	}
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('searchbysize/searchbysize')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
/*			if($id!=0)
			{
				Mage::register('region_reload', $model);	
			}
*/
			Mage::register('searchbysize_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('searchbysize');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Special Offer Message Management'), Mage::helper('adminhtml')->__('Special Offer Message Management'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Rule News'), Mage::helper('adminhtml')->__('Rule News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('searchbysize')->__('Message does not exist'));
			$this->_redirect('*/*/');
		}
	}
	public function saveAction() 
	{
		$width = $this->getRequest()->getParam('width');
		$depth = $this->getRequest()->getParam('depth');
		$height = $this->getRequest()->getParam('height');
		
		$width = implode(",",$width);
		$depth = implode(",",$depth);
		$height = implode(",",$height);
		
		//echo $width."<br>"; echo $depth."<br>";  echo $height;die;
		
			$model = Mage::getModel('searchbysize/searchbysize');
			$collection = $model->getCollection();
			if(count($collection)==0)
			{
				$model->setWidth($width)
				->setDepth($depth)
				->setheight($height)
				->setId($this->getRequest()->getParam('id'));
				try 
				{
					$model->save();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('searchbysize')->__('Sizes are successfully saved'));
					//Mage::getSingleton('adminhtml/session')->setFormData(false);
					$this->_redirect('*/*/');
					return;
				}
				catch (Exception $e) 
				{
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					//Mage::getSingleton('adminhtml/session')->setFormData($data);
					return;
				}
			}
			else
			{
				try 
				{
					$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
					$connection->query("UPDATE ifuturz_searchbysize SET width='$width',depth='$depth',height='$height'");
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('searchbysize')->__('Sizes are successfully saved'));
					$this->_redirect('*/*/');
					return;
				}
				catch(Exception $e)
				{
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					return;
				}
			}
			
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('searchbysize')->__('Unable to find User to save'));
        $this->_redirect('*/*/');
	}
  
}