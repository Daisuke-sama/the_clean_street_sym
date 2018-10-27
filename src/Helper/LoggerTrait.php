<?php
/**
 * Created by Pavel Burylichau
 * Company: EPAM Systems
 * User: pavel_burylichau@epam.com
 * Date: 10/27/18
 * Time: 5:41 PM
 */


namespace App\Helper;


use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @required
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    private function log(string $message, string $type = 'info')
    {
        if ($this->logger) {
            $this->logger->$type($message);
        }
    }

}