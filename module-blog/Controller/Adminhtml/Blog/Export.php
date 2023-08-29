<?php

namespace SM\HelloWorld\Controller\Adminhtml\Blog;

use Magento\Framework\App\Filesystem\DirectoryList;

class Export extends \Magento\Backend\App\Action
{
    protected $uploaderFactory;

    protected $_blogFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem $filesystem,
        \SM\HelloWorld\Model\BlogFactory $blogFactory // This is returns Collaction of Data
    )
    {
        parent::__construct($context);
        $this->_fileFactory = $fileFactory;
        $this->_blogFactory = $blogFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR); // VAR Directory Path
        parent::__construct($context);
    }

    public function execute()
    {
        $name = date('m-d-Y-H-i-s');
        $filepath = 'export/export-data-' . $name . '.csv'; // at Directory path Create a Folder Export and FIle
        $this->directory->create('export');

        $stream = $this->directory->openFile($filepath, 'w+');
        $stream->lock();

//column name dispay in your CSV

        $columns = ['blog_id', 'name','status','short_description', 'description', 'publish_date_from', 'publish_date_to'];

        foreach ($columns as $column) {
            $header[] = $column; //storecolumn in Header array
        }

        $stream->writeCsv($header);

        $location = $this->_blogFactory->create();
        $location_collection = $location->getCollection(); // get Collection of Table data

        foreach ($location_collection as $item) {
            $itemData = [];

// column name must same as in your Database Table

            $itemData[] = $item->getData('blog_id');
            $itemData[] = $item->getData('name');
            $itemData[] = $item->getData('status');
            $itemData[] = $item->getData('short_description');
            $itemData[] = $item->getData('description');
            $itemData[] = $item->getData('publish_date_from');
            $itemData[] = $item->getData('publish_date_to');

            $stream->writeCsv($itemData);
        }

        $content = [];
        $content['type'] = 'filename'; // must keep filename
        $content['value'] = $filepath;
        $content['rm'] = '1'; //remove csv from var folder

        $csvfilename = 'locator-import-' . $name . '.csv';
        return $this->_fileFactory->create($csvfilename, $content, DirectoryList::VAR_DIR);
    }
}