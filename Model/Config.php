<?php

namespace SM\HelloWorld\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface as MagentoUrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{
    const MEDIA_FOLDER = 'sm/tmp/image';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var MagentoUrlInterface
     */
    protected $urlManager;

    /**
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param Filesystem            $filesystem
     * @param MagentoUrlInterface   $urlManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        MagentoUrlInterface $urlManager
    ) {
        $this->scopeConfig  = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->filesystem   = $filesystem;
        $this->urlManager   = $urlManager;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->urlManager->getUrl($this->getBaseRoute());
    }

    /**
     * @return string
     */
    public function getBaseRoute()
    {
        return $this->scopeConfig->getValue('blog/seo/base_route');
    }

    /**
     * @return string
     */
    public function getDisqusShortname()
    {
        return $this->scopeConfig->getValue('blog/comments/disqus_shortname');
    }
    public function isDisplayInMenu()
    {
        return $this->scopeConfig->getValue(
            'blog/display/main_menu',
            ScopeInterface::SCOPE_STORE
        );
    }
    public function getMenuTitle($store = null)
    {
        return $this->scopeConfig->getValue(
            'blog/appearance/menu_title',
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

}
