<?php
/**
 * Created for plugin-core-dialog
 * Date: 10/5/21 7:33 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Dialog\Actions;

use Exception;
use Leadvertex\Plugin\Core\Actions\SpecialRequestAction;
use Leadvertex\Plugin\Core\Dialog\Components\Dialog\Dialog;
use Leadvertex\Plugin\Core\Dialog\Components\Dialog\Exceptions\EmptyMessageContentException;
use Leadvertex\Plugin\Core\Dialog\Components\Dialog\Exceptions\EmptyMessageException;
use Leadvertex\Plugin\Core\Dialog\SendMessageQueue\DialogSendTask;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Throwable;

class SenderAction extends SpecialRequestAction
{

    protected function handle(array $body, ServerRequest $request, Response $response, array $args): Response
    {
        try {
            $dialog = Dialog::parseFromArray($body);
        } catch (EmptyMessageException|EmptyMessageContentException $exception) {
            return $response->withJson([
                'code' => 405,
                'message' => $exception->getMessage(),
            ], 405);
        } catch (Throwable $throwable) {
            return $response->withJson([
                'code' => 405,
                'message' => 'Can not parse dialog & message data',
            ], 405);
        }

        try {
            $queue = new DialogSendTask($dialog);
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