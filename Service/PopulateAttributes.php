<?php

declare(strict_types=1);

namespace Mooore\XcoreAttributes\Service;

use Dealer4dealer\Xcore\Api\PriceListRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class PopulateAttributes
{
    /**
     * @var PriceListRepositoryInterface
     */
    private $priceListRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(PriceListRepositoryInterface $priceListRepository, LoggerInterface $logger)
    {
        $this->priceListRepository = $priceListRepository;
        $this->logger = $logger;
    }

    public function execute(CustomerInterface $customer): CustomerInterface
    {
        $extensionAttributes = $customer->getExtensionAttributes();

        $priceList = $this->getCustomAttributeValue($customer, 'price_list');
        $priceListGuid = !empty($priceList) ? $this->mapPriceList($priceList) : null;
        if ($priceListGuid !== null) {
            $extensionAttributes->setXcorePriceList($priceListGuid);
        }

        $vatClass = $this->getCustomAttributeValue($customer, 'vat_class');

        if ($vatClass !== null) {
            $extensionAttributes->setXcoreVatCode($vatClass);
        }

        return $customer;
    }

    private function mapPriceList(string $priceListId): ?string
    {
        try {
            return $this->priceListRepository->getById($priceListId)->getGuid();
        } catch (LocalizedException $e) {
            $this->logger->error($e->getLogMessage(), ['price_list_id' => $priceListId]);
            return null;
        }
    }

    public function getCustomAttributeValue(CustomerInterface $customer, string $attribute): ?string
    {
        $attribute = $customer->getCustomAttribute($attribute);

        return $attribute !== null ? (string) $attribute->getValue() : null;
    }
}
