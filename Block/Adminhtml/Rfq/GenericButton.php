<?php
/**
 *
 * @category : FME
 * @Package  : FME_OutOfStockNotification
 * @Author   : FME Extensions <support@fmeextensions.com>
 * @copyright Copyright 2020 © fmeextensions.com All right reserved
 * @license https://fmeextensions.com/LICENSE.txt
 */
namespace FME\DeleteMyAccount\Block\Adminhtml\Rfq;

use Magento\Backend\Block\Widget\Context;
//use FME\News\Api\NewsRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var BlockRepositoryInterface
     */
    protected $blockRepository;

    /**
     * @param Context $context
     * @param BlockRepositoryInterface $blockRepository
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * Return CMS block ID
     *
     * @return int|null
     */
    public function getBlockId()
    {
        // $this->getRequest()->getParam('delete_account_id');
 return $this->context->getRequest()->getParam('delete_account_id');

        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
