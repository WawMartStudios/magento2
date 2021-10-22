<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\QuoteGraphQl\Model;

use Magento\Checkout\Model\Backpressure\CheckoutLimitConfigManager;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\GraphQl\Model\Backpressure\RequestTypeExtractorInterface;
use Magento\QuoteGraphQl\Model\Resolver\PlaceOrder;
use Magento\QuoteGraphQl\Model\Resolver\SetPaymentAndPlaceOrder;

/**
 * Identifies which quote fields need backpressure management.
 */
class BackpressureRequestTypeExtractor implements RequestTypeExtractorInterface
{
    private CheckoutLimitConfigManager $config;

    /**
     * @param CheckoutLimitConfigManager $config
     */
    public function __construct(CheckoutLimitConfigManager $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function extract(Field $field): ?string
    {
        if (($field->getResolver() === SetPaymentAndPlaceOrder::class
                || $field->getResolver()  === PlaceOrder::class
            )
            && $this->config->isEnforcementEnabled()
        ) {
            return CheckoutLimitConfigManager::REQUEST_TYPE_ID;
        }

        return null;
    }
}
