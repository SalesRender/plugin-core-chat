<?php
/**
 * Created for LeadVertex
 * Date: 10/15/21 7:13 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Dialog\SendMessageQueue;

interface DialogSenderInterface
{

    /**
     * This method should handle sending message via gateway and get care about message status, e.g. tracking message status
     * and sending actual message status via
     * @see \Leadvertex\Plugin\Core\Dialog\Components\MessageStatusSender\MessageStatusSender::send()
     * @param DialogSendTask $task
     * @return mixed
     */
    public function __invoke(DialogSendTask $task);

}