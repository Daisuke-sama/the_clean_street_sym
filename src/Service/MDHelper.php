<?php
/**
 * Created by Pavel Burylichau
 * Company: EPAM Systems
 * User: pavel_burylichau@epam.com
 * Date: 10/23/18
 * Time: 6:54 PM
 */


namespace App\Service;


use Michelf\MarkdownInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\Security;

class MDHelper
{
    /**
     * @var MarkdownInterface
     */
    private $markdown;
    /**
     * @var AdapterInterface
     */
    private $cache;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var bool
     */
    private $isDebug;
    /**
     * @var Security
     */
    private $security;

    /**
     * MDHelper constructor.
     * @param MarkdownInterface $markdown
     * @param AdapterInterface $cache
     * @param LoggerInterface $logger
     * @param LoggerInterface $markdownLogger
     * @param bool $isDebug
     * @param Security $security
     */
    public function __construct(MarkdownInterface $markdown, AdapterInterface $cache, LoggerInterface $logger, LoggerInterface $markdownLogger, bool $isDebug, Security $security)
    {
        $this->markdown = $markdown;
        $this->cache = $cache;
        $this->logger = $markdownLogger;
        $this->isDebug = $isDebug;
        $this->security = $security;
    }

    /**
     * @param string $source
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function parse(string $source): string
    {
        if (stripos($source, 'ab') !== false) {
            $this->logger->info('They are talking again!', [
                'user' => $this->security->getUser(),
            ]);
        }

        if ($this->isDebug) {
            return $this->markdown->transform($source);
        }

        $item = $this->cache->getItem('markdown_'.md5($source));
        if(!$item->isHit()) {
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }

        if (strpos($source, 'my') !== false) {
            $this->logger->info('The word "my" has been logged.');
        }

        return $item->get();
    }
}
