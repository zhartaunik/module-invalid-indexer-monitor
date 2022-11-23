<?php

declare(strict_types=1);

namespace PerfectCode\InvalidIndexerMonitor\Console\Command;

use PerfectCode\InvalidIndexerMonitor\Cron\DisableEndedCatalogRule as DisableEndedCatalogRuleAlias;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DisableEndedCatalogRule extends Command
{
    /**
     * @param DisableEndedCatalogRuleAlias $disableEndedCatalogRule
     * @param string|null $name
     */
    public function __construct(
        private readonly DisableEndedCatalogRuleAlias $disableEndedCatalogRule,
        string $name = null,
    ) {
        parent::__construct($name);
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
