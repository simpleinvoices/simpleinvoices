<?php

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LegacyLogger
{
    public const INFO = 'info';
    public const DEBUG = 'debug';
    public const ERROR = 'error';

    private Logger $logger;

    public function __construct(string $filePath)
    {
        $this->logger = new Logger('simpleinvoices');
        $handler = new StreamHandler($filePath);
        $handler->setFormatter(new LineFormatter("[%datetime%] %level_name%: %message% %context%\n", null, true, true));
        $this->logger->pushHandler($handler);
    }

    public function log(string $message, string $level = self::INFO, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log($message, self::INFO, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log($message, self::ERROR, $context);
    }
}
