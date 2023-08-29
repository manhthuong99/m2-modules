<?php
namespace SM\HelloWorld\Block\Adminhtml;

class Categories extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct()
    {
        $this->_controller = 'adminhtml_categories';
        $this->_blockGroup = 'SM_HelloWorld';
        $this->_headerText = __('Categories');
        $this->_addButtonLabel = __('Create New Category');
        parent::_construct();
    }
}