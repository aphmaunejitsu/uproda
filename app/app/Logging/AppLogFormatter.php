<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class AppLogFormatter
{
    /**
     * Customize the given Monolog instance.
     *
     * @param  \Monolog\Logger  $monolog
     * @return void
     */
    public function __invoke($monolog)
    {
        //$jsonFormatter = new JsonFormatter();
        $jsonFormatter = new JsonLineFormatter();
        $introspectionProcessor = new IntrospectionProcessor(
            Logger::DEBUG,
            [],
            4
        );

        foreach ($monolog->getHandlers() as $handler) {
            $handler->setFormatter($jsonFormatter);
            $handler->pushProcessor($introspectionProcessor);
        }
    }
}
