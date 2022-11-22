<?php

declare(strict_types=1);

namespace PerfectCode\InvalidIndexerMonitor\Console\Command;

use Magento\Framework\App\ObjectManagerFactory;
use Magento\Indexer\Console\Command\AbstractIndexerCommand;
use Magento\Indexer\Model\Indexer\CollectionFactory;
use PerfectCode\InvalidIndexerMonitor\Cron\DisableEndedCatalogRule as DisableEndedCatalogRuleAlias;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DisableEndedCatalogRule extends AbstractIndexerCommand
{
    /**
     * @param ObjectManagerFactory $objectManagerFactory
     * @param DisableEndedCatalogRuleAlias $disableEndedCatalogRule
     * @param CollectionFactory|null $collectionFactory
     */
    public function __construct(
        ObjectManagerFactory $objectManagerFactory,
        private readonly DisableEndedCatalogRuleAlias $disableEndedCatalogRule,
        CollectionFactory $collectionFactory = null,
    ) {
        parent::__construct($objectManagerFactory, $collectionFactory);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('indexer:catalogrule:disable')->setDescription('Disable all ended catalog rules.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->disableEndedCatalogRule->execute();
    }
}
