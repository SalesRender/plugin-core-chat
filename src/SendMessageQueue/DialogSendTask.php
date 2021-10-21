<?php
/**
 * Created for plugin-core-dialog
 * Date: 10/14/21 6:15 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Dialog\SendMessageQueue;

use Leadvertex\Plugin\Components\Queue\Models\Task\Task;
use Leadvertex\Plugin\Components\Queue\Models\Task\TaskAttempt;
use Leadvertex\Plugin\Core\Dialog\Components\Dialog\Dialog;

class DialogSendTask extends Task
{

    protected Dialog $dialog;

    public function __construct(Dialog $dialog)
    {
        parent::__construct(new TaskAttempt(100, 10));
        $this->dialog = $dialog;
    }

    public function getDialog(): Dialog
    {
        return $this->dialog;
    }

    public function getAttempt(): TaskAttempt
    {
        return $this->attempt;
    }

    protected static function beforeWrite(array $data): array
    {
        $data = parent::beforeWrite($data);
        $data['dialog'] = json_encode($data['dialog']);
        return $data;
    }

    protected static function afterRead(array $data): array
    {
        $data = parent::afterRead($data);
        $dialog = Dialog::parseFromArray(json_decode($data['dialog'], true));
        $data['dialog'] = $dialog;
        return $data;
    }

    public static function schema(): array
    {
        return array_merge(parent::schema(), [
            'dialog' => ['TEXT', 'NOT NULL'],
        ]);
    }
}