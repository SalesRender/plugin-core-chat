<?php
/**
 * Created for LeadVertex
 * Date: 10/15/21 7:10 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Core\Chat\SendMessageQueue;

use SalesRender\Plugin\Components\Db\Components\Connector;
use SalesRender\Plugin\Components\Queue\Commands\QueueHandleCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class ChatSendQueueHandleCommand extends QueueHandleCommand
{

    private static ChatSenderInterface $sender;

    public static function config(ChatSenderInterface $sender): void
    {
        self::$sender = $sender;
    }

    public function __construct()
    {
        parent::__construct('chatSendQueue');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ChatSendTask $task */
        $task = ChatSendTask::findById($input->getArgument('id'));
        if (is_null($task)) {
            $output->writeln("<error>Task with passed id was not found</error>");
            return Command::INVALID;
        }

        if ($task->getPluginReference()) {
            Connector::setReference($task->getPluginReference());
        }

        try {
            $sender = self::$sender;
            $sender($task);
            $task->delete();
            return Command::SUCCESS;
        } catch (Throwable $throwable) {
            $output->writeln("<error>{$throwable->getMessage()}</error>");
            $output->writeln("<error>{$throwable->getTraceAsString()}</error>");
            $task->getAttempt()->attempt($throwable->getMessage());
        }

        if ($task->getAttempt()->isSpent()) {
            $task->delete();
        } else {
            $task->save();
        }

        return Command::FAILURE;
    }

}