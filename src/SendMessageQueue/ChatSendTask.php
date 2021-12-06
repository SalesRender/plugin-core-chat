<?php
/**
 * Created for plugin-core-chat
 * Date: 10/14/21 6:15 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Chat\SendMessageQueue;

use Leadvertex\Plugin\Components\Queue\Models\Task\Task;
use Leadvertex\Plugin\Components\Queue\Models\Task\TaskAttempt;
use Leadvertex\Plugin\Core\Chat\Components\Chat\Chat;

class ChatSendTask extends Task
{

    protected Chat $chat;

    public function __construct(Chat $chat)
    {
        parent::__construct(new TaskAttempt(100, 10));
        $this->chat = $chat;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function getAttempt(): TaskAttempt
    {
        return $this->attempt;
    }

    protected static function beforeWrite(array $data): array
    {
        $data = parent::beforeWrite($data);
        $data['chat'] = json_encode($data['chat']);
        return $data;
    }

    protected static function afterRead(array $data): array
    {
        $data = parent::afterRead($data);
        $chat = Chat::parseFromArray(json_decode($data['chat'], true));
        $data['chat'] = $chat;
        return $data;
    }

    public static function schema(): array
    {
        return array_merge(parent::schema(), [
            'chat' => ['TEXT', 'NOT NULL'],
        ]);
    }
}