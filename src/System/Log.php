<?php


namespace SilverStripers\Out\System;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SilverStripe\Control\Director;
use SilverStripe\Core\Environment;

class Log
{

    const OUT_ECHO = 'OUT_ECHO';

    private static $logger = null;

    /**
     * @return Logger
     * @throws \Exception
     */
    private static function logger()
    {
        if (!self::$logger) {
            if (($path = Environment::getEnv('LOGGER_PATH'))) {
                $logger = new Logger('latitude');
                $logger->pushHandler(new StreamHandler(
                    implode(DIRECTORY_SEPARATOR, [BASE_PATH, $path])
                ));
                self::$logger = $logger;
            } else {
                self::$logger = self::OUT_ECHO;
            }
        }
        return self::$logger;
    }

    public static function printLn($message)
    {
        $logger = self::logger();
        if ($logger == self::OUT_ECHO) {
            echo sprintf(
                "%s%s",
                $message,
                Director::is_cli() ? "\n" : '<br>'
            );
        } else {
            $logger->log(Logger::DEBUG, $message);
        }
    }
}
