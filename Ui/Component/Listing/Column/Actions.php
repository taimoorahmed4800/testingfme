<?php

namespace FME\DeleteMyAccount\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action\UrlBuilder;
use Magento\Framework\UrlInterface;
use FME\DeleteMyAccount\Model\DeleteMyAccountFactory;

/**
 * Class PageActions
 */
class Actions extends Column
{
    /** Url path */
    const URL_PATH_EDIT = 'customer/index/edit';
    const URL_PATH_DELETE = 'deletemyaccount/index/delete';

    /** @var UrlBuilder */
    protected $actionUrlBuilder;

    /** @var UrlInterface */
    protected $urlBuilder;
    protected $DeleteMyAccountFactory;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlBuilder $actionUrlBuilder
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     * @param string $editUrl
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlBuilder $actionUrlBuilder,
        UrlInterface $urlBuilder,
        DeleteMyAccountFactory $DeleteMyAccountFactory,
        array $components = [],
        array $data = [],
        $editUrl = self::URL_PATH_EDIT
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->actionUrlBuilder = $actionUrlBuilder;
        $this->editUrl = $editUrl;
        $this->DeleteMyAccountFactory = $DeleteMyAccountFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item['customer_id'])) {
                    $item[$name]['edit_customer'] = [
                        'href' => $this->urlBuilder->getUrl($this->editUrl, ['id' => $item['customer_id']]),
                        'label' => __('Edit Customer ')
                    ];
                    $item[$name]['view'] = [
                        'href' => $this->urlBuilder->getUrl('deletemyaccount/index/edit', ['delete_account_id' => $item['delete_account_id']]),
                        'label' => __('view Account Request')
                    ];
                    $model = $this->DeleteMyAccountFactory->create()->load($item['delete_account_id']);
                    $title = $model->getFirstName()." ".$model->getLastName();
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['delete_account_id' => $item['delete_account_id']]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete Subscriber'.$title),
                            'message' => __('Are you sure you wan\'t to delete Subscriber '.$title." ?")
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
