<?php
/**
 * Created for plugin-core-chat
 * Date: 10/5/21 7:33 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Chat\Actions;

use Exception;
use Leadvertex\Plugin\Core\Actions\SpecialRequestAction;
use Leadvertex\Plugin\Core\Chat\Components\Chat\Chat;
use Leadvertex\Plugin\Core\Chat\Components\Chat\Exceptions\EmptyMessageContentException;
use Leadvertex\Plugin\Core\Chat\Components\Chat\Exceptions\EmptyMessageException;
use Leadvertex\Plugin\Core\Chat\SendMessageQueue\ChatSendTask;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Throwable;

class SenderAction extends SpecialRequestAction
{

    protected function handle(array $body, ServerRequest $request, Response $response, array $args): Response
    {
        try {
            $chat = Chat::parseFromArray($body);
        } catch (EmptyMessageException|EmptyMessageContentException $exception) {
            return $response->withJson([
                'code' => 405,
                'message' => $exception->getMessage(),
            ], 405);
        } catch (Throwable $throwable) {
            return $response->withJson([
                'code' => 405,
                'message' => 'Can not parse chat & message data',
            ], 405);
        }

        try {
            $queue = new ChatSendTask($chat);
            $queue->save();
        } catch (Exception $exception) {
            return $response->withJson([
                'code' => 500,
                'message' => 'Error while sending message via gateway',
            ], 500);
        }

        return $response->withStatus(202);
    }

    public function getName(): string
    {
        return 'send';
    }
}