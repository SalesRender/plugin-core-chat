<?php
/**
 * Created for LeadVertex
 * Date: 10/15/21 7:10 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Dialog\SendMessageQueue;

use Leadvertex\Plugin\Components\Queue\Commands\QueueHandleCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class DialogSendQueueHandleCommand extends QueueHandleCommand
{

    private static DialogSenderInterface $sender;

    public static function config(DialogSenderInterface $sender): void
    {
        self::$sender = $sender;
    }

    public function __construct()
    {
        parent::__construct('dialogSendQueue');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var DialogSendTask $task */
        $task = DialogSendTask::findById($input->getArgument('id'));
        if (is_null($task)) {
            return Command::INVALID;
        }

        try {
            $sender = self::$sender;
            $sender($task);
        } catch (Throwable $throwable) {
            return Command::FAILURE;
        } finally {
            $task->getAttempt()->attempt('');
            $task->save();
        }

        return Command::SUCCESS;
    }

}