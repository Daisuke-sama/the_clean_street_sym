<?php
/**
 * Created by Pavel Burylichau
 * Company: EPAM Systems
 * User: pavel_burylichau@epam.com
 * Date: 10/27/18
 * Time: 5:17 PM
 */


namespace App\Controller;


use App\Helper\LoggerTrait;
use Doctrine\Common\Annotations\Annotation\Required;
use Http\Client\Exception;
use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;

class SlackClient
{
    use LoggerTrait;

    /**
     * @var Client
     */
    private $slack;

    public function __construct(Client $slack)
    {
        $this->slack = $slack;
    }

    public function sendMsg(string $content, string $from = 'Null')
    {
        $text = $this->slack->createMessage()
            ->setText($content)
            ->withIcon(':ghost:')
            ->from($from);

        try {
            $this->slack->sendMessage($text);
        } catch (Exception $e) {
            $this->log($e->getMessage(), 'alert');
        }

        $this->log('Written: '.$content);
    }
}