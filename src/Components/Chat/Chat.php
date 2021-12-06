<?php
/**
 * Created for plugin-core-chat
 * Date: 10/11/21 4:48 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Chat\Components\Chat;

use JsonSerializable;
use Leadvertex\Plugin\Components\Access\Registration\Registration;
use Leadvertex\Plugin\Components\Db\Components\Connector;
use Leadvertex\Plugin\Components\SpecialRequestDispatcher\Components\SpecialRequest;
use Leadvertex\Plugin\Components\SpecialRequestDispatcher\Models\SpecialRequestTask;
use Leadvertex\Plugin\Core\Chat\Components\Chat\Exceptions\EmptyMessageContentException;
use Leadvertex\Plugin\Core\Chat\Components\Chat\Exceptions\EmptyMessageException;
use Leadvertex\Plugin\Core\Chat\Components\Chat\Message\Message;
use Leadvertex\Plugin\Core\Chat\Components\Chat\Message\MessageAttachment;
use Leadvertex\Plugin\Core\Chat\Components\Chat\Message\MessageContent;
use LogicException;
use XAKEPEHOK\Path\Path;

class Chat implements JsonSerializable
{

    protected ?string $id = null;
    protected string $contact;
    protected ?string $subject = null;
    protected Message $message;

    public function __construct(?string $id, string $contact, ?string $subject, Message $message)
    {
        $this->id = $id;
        $this->contact = $contact;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getContact(): string
    {
        return $this->contact;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function jsonSerialize(): array
    {
        return [
            'chat' => [
                'id' => $this->id,
                'contact' => $this->contact,
                'subject' => $this->subject,
            ],
            'message' => $this->message->jsonSerialize(),
        ];
    }


    /**
     * @param array $chat
     * @return static
     * @throws EmptyMessageContentException
     * @throws EmptyMessageException
     */
    public static function parseFromArray(array $chat): self
    {
        $content = null;
        if ($chat['message']['content']) {
            $content = new MessageContent(
                $chat['message']['content']['text'],
                $chat['message']['content']['format'],
            );
        }

        $attachments = [];
        foreach ($chat['message']['attachments'] as $attachment) {
            $attachments[] = new MessageAttachment(
                $attachment['name'],
                $attachment['uri'],
                $attachment['type'],
            );
        }

        return new Chat(
            $chat['chat']['id'] ?? null,
            $chat['chat']['contact'],
            $chat['chat']['subject'],
            new Message(
                $chat['message']['id'] ?? null,
                $content,
                $attachments
            )
        );
    }

    public function send(): void
    {
        $data = $this->jsonSerialize();
        if (isset($data['id']) || isset($data['message']['id'])) {
            throw new LogicException('Chat or message with non-null id can not be send');
        }

        unset($data['id']);
        unset($data['message']['id']);

        $registration = Registration::find();
        $uri = (new Path($registration->getClusterUri()))
            ->down('companies')
            ->down(Connector::getReference()->getCompanyId())
            ->down('CRM/plugin/chat/incoming');

        $ttl = 300;
        $request = new SpecialRequest(
            'PUT',
            (string) $uri,
            (string) Registration::find()->getSpecialRequestToken($data, $ttl),
            time() + $ttl,
            201,
            [405]
        );

        $dispatcher = new SpecialRequestTask($request, null, 5);
        $dispatcher->save();
    }
}