<?php

declare(strict_types=1);

namespace Mooore\XcoreAttributes\Plugin;

use Magento\Customer\Api\CustomerRepositoryInterface as Subject;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Mooore\XcoreAttributes\Service\PopulateAttributes;

class CustomerRepositoryInterfacePlugin
{
    /**
     * @var PopulateAttributes
     */
    private $populateAttributes;

    public function __construct(PopulateAttributes $populateAttributes)
    {
        $this->populateAttributes = $populateAttributes;
    }

    public function afterGet(Subject $subject, CustomerInterface $customer): CustomerInterface
    {
        return $this->populateAttributes->execute($customer);
    }

    public function afterGetById(Subject $subject, CustomerInterface $customer): CustomerInterface
    {
        return $this->populateAttributes->execute($customer);
    }

    public function afterGetList(
        Subject $subject,
        SearchResultsInterface $searchResults
    ): SearchResultsInterface {
        foreach ($searchResults->getItems() as $customer) {
            $this->populateAttributes->execute($customer);
        }

        return $searchResults;
    }
}
