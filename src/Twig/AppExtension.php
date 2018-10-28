<?php

namespace App\Twig;

use App\Service\MDHelper;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /**
     * @var MDHelper
     */
    private $mdHelper;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MDHelper $mdHelper, LoggerInterface $logger)
    {
        $this->mdHelper = $mdHelper;
        $this->logger = $logger;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('cached_markdown', [$this, 'processMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    public function processMarkdown($value)
    {
        try {
            return $this->mdHelper->parse($value);
        } catch (InvalidArgumentException $e) {
            $this->logger->critical($e->getMessage());
        }
    }
}
