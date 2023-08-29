<?php
namespace SM\HelloWorld\Block\Adminhtml\Blog\Edit;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;


class GenericButton
{
    /**
     * @var Context
     */
    private $context;


    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    public function getBlogId()
    {
        try {
            return
                $this->context->getRequest()->getParam('blog_id');
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
