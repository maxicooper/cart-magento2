<?php

namespace MercadoPago\Core\Controller\Basic;

use Magento\Catalog\Controller\Product\View\ViewInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use MercadoPago\Core\Helper\ConfigData;
use MercadoPago\Core\Model\Basic\Payment;

class Pay extends Action implements ViewInterface
{

    /**
     * @var \MercadoPago\Core\Model\Basic\Payment
     */
    protected $_paymentFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Pay constructor.
     * @param Context $context
     * @param Payment $paymentFactory
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(Context $context, Payment $paymentFactory, ScopeConfigInterface $scopeConfig)
    {
        $this->_paymentFactory = $paymentFactory;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $array_assign = $this->_paymentFactory->postPago();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($array_assign['status'] != 400) {
            $resultRedirect->setUrl($array_assign['init_point']);
        } else {
            $resultRedirect->setUrl($this->_url->getUrl(ConfigData::PATH_BASIC_URL_FAILURE));
        }
        return $resultRedirect;
    }
}