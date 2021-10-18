<?php
/**
 * Created for plugin-core-dialog
 * Date: 10/14/21 6:12 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Dialog\SendMessageQueue;

use Leadvertex\Plugin\Components\Queue\Commands\QueueCommand;
use Medoo\Medoo;

class DialogSendQueueCommand extends QueueCommand
{

    public function __construct()
    {
        parent::__construct(
            'dialogSendQueue',
            $_ENV['LV_PLUGIN_DIALOG_SEND_QUEUE_LIMIT'] ?? 100,
            25
        );
    }

    protected function findModels(): array
    {
        DialogSendTask::freeUpMemory();
        return DialogSendTask::findByCondition([
            'OR' => [
                'attemptAt' => null,
                'attemptAt[<=]' => Medoo::raw('(:time - <attemptTimeout>)', [':time' => time()]),
            ],
            'id[!]' => array_keys($this->processes),
            "ORDER" => ["createdAt" => "ASC"],
            'LIMIT' => $this->limit
        ]);
    }
}