<?php
/**
 * Created for plugin-core-chat
 * Date: 02.12.2020
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Chat\Factories;


use Leadvertex\Plugin\Core\Commands\CronCommand;
use Leadvertex\Plugin\Core\Chat\SendMessageQueue\ChatSendQueueHandleCommand;
use Leadvertex\Plugin\Core\Chat\SendMessageQueue\ChatSendQueueCommand;
use Symfony\Component\Console\Application;
use XAKEPEHOK\Path\Path;

class ConsoleAppFactory extends \Leadvertex\Plugin\Core\Factories\ConsoleAppFactory
{

    public function build(): Application
    {
        $this->app->add(new ChatSendQueueCommand());
        $this->app->add(new ChatSendQueueHandleCommand());

        CronCommand::addTask(
            '* * * * * ' . PHP_BINARY . ' ' . Path::root()->down('console.php') . ' chatSendQueue:queue'
        );

        return parent::build();
    }

}