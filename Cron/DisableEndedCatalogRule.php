<?php

declare(strict_types=1);

namespace PerfectCode\InvalidIndexerMonitor\Cron;

use Exception;
use Magento\CatalogRule\Model\Rule;
use Magento\CatalogRule\Model\ResourceModel\Rule as ResourceModel;
use Magento\CatalogRule\Model\ResourceModel\Rule\Collection;
use Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;

/**
 * There is a class \Magento\CatalogRule\Cron\DailyCatalogUpdate which invalidates all indexes
 * based on active CatalogRules. This job considers even those rules which are already ended.
 *
 * We are unable to exclude ended rules because when the rule is ended we need to recalculate prices
 * for rules which were involved into the calculation.
 *
 * That is why we decided to inactivate those catalog rules which were ended more than 1 days ago.
 * The goal is following:
 * Monday 0:00      | The Rule was ended.
 * Monday 1:00      | Executed cron job `catalogrule_apply_all` which invalidates ~7 indexes
 * If re-indexation is disabled by cron - then indexes should be processed manually.
 * Tuesday          | Nothing happens with the catalogrule. This is reserved day
 * Tuesday 1:00     | Again cronjob `catalogrule_apply_all` which invalidates ~7 indexes
 * If re-indexation is disabled by cron - then indexes should be processed manually.
 * Wednesday 0:30   | The rule becomes inactive BY CURRENT CLASS
 * Wednesday 1:00   | No index invalidation happens anymore.
 */
class DisableEndedCatalogRule
{
    /**
     * @param ResourceModel $resourceModel
     * @param RuleCollectionFactory $collectionFactory
     * @param State $state
     */
    public function __construct(
        private readonly ResourceModel $resourceModel,
        private readonly RuleCollectionFactory $collectionFactory,
        private readonly State $state,
    ) {}

    /**
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addIsActiveFilter();
        $collection->getSelect()->where('DATE_SUB(CURDATE(), INTERVAL 1 DAY) > to_date');
        /** @var Rule $catalogRule */
        foreach ($collection as $catalogRule) {
            $catalogRule->setIsActive(0);
            $this->state->emulateAreaCode(Area::AREA_ADMINHTML, [$this->resourceModel, 'save'], [$catalogRule]);
        }
    }
}
