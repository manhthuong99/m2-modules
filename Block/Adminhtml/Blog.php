<?php
namespace SM\HelloWorld\Block\Adminhtml;

class Blog extends \Magento\Backend\Block\Widget\Grid\Container
{

    protected function _construct()
    {
        $this->_controller = 'adminhtml_blog';
        $this->_blockGroup = 'SM_HelloWorld';
        $this->_headerText = __('Blogs');
        $this->_addButtonLabel = __('Create New Blog');
        parent::_construct();
    }
}