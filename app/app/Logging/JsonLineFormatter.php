<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;

class JsonLineFormatter extends LineFormatter
{
    public function format($record): string
    {
        $json_line = [
           'time'    => $record['datetime']->format(\Datetime::ISO8601),
           'level'   => $record['level_name'],
           'message' => $record['message'],
           'channel' => $record['channel'],
           'context' => $record['context'],
           'extra'   => $record['extra'],
        ];
        return json_encode($json_line) . "\n";
    }
}
