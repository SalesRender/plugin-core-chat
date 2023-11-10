<?php
/**
 * Created for LeadVertex
 * Date: 10/15/21 7:13 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Core\Chat\SendMessageQueue;

interface ChatSenderInterface
{

    /**
     * This method should handle sending message via gateway and get care about message status, e.g. tracking message status
     * and sending actual message status via
     * @param ChatSendTask $task
     * @return mixed
     * @see \SalesRender\Plugin\Core\Chat\Components\MessageStatusSender\MessageStatusSender::send()
     */
    public function __invoke(ChatSendTask $task);

}