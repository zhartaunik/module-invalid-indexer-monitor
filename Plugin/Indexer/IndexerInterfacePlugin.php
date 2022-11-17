<?php

declare(strict_types=1);

namespace PerfectCode\InvalidIndexerMonitor\Plugin\Indexer;

use Magento\Framework\Indexer\IndexerInterface;
use Psr\Log\LoggerInterface;

class IndexerInterfacePlugin
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Put into log file the place from was changed indexer state to 'invalid'.
     *
     * @param IndexerInterface $subject
     * @return void
     */
    public function beforeInvalidate(IndexerInterface $subject): void
    {
        $this->logger->warning(sprintf('This index became invalid: %s', $subject->getViewId()));
        ob_start();
        debug_print_backtrace();
        $this->logger->warning(ob_get_clean());
    }
}
