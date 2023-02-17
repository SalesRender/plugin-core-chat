<?php
/**
 * Created for plugin-core-chat
 * Date: 10/14/21 6:12 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Chat\SendMessageQueue;

use Leadvertex\Plugin\Components\Queue\Commands\QueueCommand;
use Medoo\Medoo;

class ChatSendQueueCommand extends QueueCommand
{

    public function __construct()
    {
        parent::__construct(
            'chatSendQueue',
            $_ENV['LV_PLUGIN_CHAT_SEND_QUEUE_LIMIT'] ?? 100,
            25
        );
    }

    protected function findModels(): array
    {
        ChatSendTask::freeUpMemory();
        $condition = [
            'OR' => [
                'attemptLastTime' => null,
                'attemptLastTime[<=]' => Medoo::raw('(:time - <attemptInterval>)', [':time' => time()]),
            ],
            "ORDER" => ["createdAt" => "ASC"],
            'LIMIT' => $this->limit
        ];

        $processes = array_keys($this->processes);
        if (!empty($processes)) {
            $condition['id[!]'] = $processes;
        }

        return ChatSendTask::findByCondition($condition);
    }
}