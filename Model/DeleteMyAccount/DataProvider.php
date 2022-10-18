<?php

namespace FME\DeleteMyAccount\Model\DeleteMyAccount;

use FME\DeleteMyAccount\Model\ResourceModel\DeleteMyAccount\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
/**
 * @var \Magento\Cms\Model\ResourceModel\Block\Collection
 */
    protected $collection;
    public $_storeManager;

    protected $Product;
/**
 * @var DataPersistorInterface
 */
    protected $dataPersistor;
/**
 * @var array
 */
    protected $loadedData;
/**
 * Constructor
 *
 * @param string $name
 * @param string $primaryFieldName
 * @param string $requestFieldName
 * @param CollectionFactory $blockCollectionFactory
 * @param DataPersistorInterface $dataPersistor
 * @param array $meta
 * @param array $data
 */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $blockCollectionFactory,
        \Magento\Catalog\Model\Product $Product,
        DataPersistorInterface $dataPersistor,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $blockCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->Product = $Product;
        $this->_storeManager=$storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
/**
 * Get data
 *
 * @return array
 */
    public function getData()
    {
        $baseurl =  $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Magento\Cms\Model\Block $block */
        foreach ($items as $block) {
            $data = $block->getData();
            $this->loadedData[$block->getId()] = $data;
        }
        $data = $this->dataPersistor->get('fme_deletemyaccount');
        if (!empty($data)) {
            $block = $this->collection->getNewEmptyItem();
            $block->setData($data);
            $this->loadedData[$block->getId()] = $block->getData();
            $this->dataPersistor->clear('fme_deletemyaccount');
        }
        return $this->loadedData;
    }
}
