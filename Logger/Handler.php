<?php

declare(strict_types=1);

namespace PerfectCode\InvalidIndexerMonitor\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;

class Handler extends Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::WARNING;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/indexer.log';
}
