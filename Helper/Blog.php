<?php


namespace SM\HelloWorld\Helper;
use _HumbugBox69342eed62ce\Symfony\Component\Console\Helper\Helper;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\PageCache\Version;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class Blog extends AbstractHelper
{
    protected $cacheTypeList;
    protected $cacheFrontendPool;
    public function __construct(
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    ){

        $this->cacheTypeList = $cacheTypeList;
        $this->cacheFrontendPool = $cacheFrontendPool;

    }

    public function flushCache(Version $subject)
    {
        $_types = [
            'config',
            'layout',
            'block_html',
            'collections',
            'reflection',
            'db_ddl',
            'eav',
            'config_integration',
            'config_integration_api',
            'full_page',
            'translate',
            'config_webservice'
        ];

        foreach ($_types as $type) {
            $this->cacheTypeList->cleanType($type);
        }
        foreach ($this->cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
}