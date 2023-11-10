<?php
/**
 * Created for plugin-core-chat
 * Date: 10/11/21 4:48 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Core\Chat\Components\Chat\Message;

use JsonSerializable;
use SalesRender\Plugin\Core\Chat\Components\Chat\Exceptions\EmptyMessageException;

class Message implements JsonSerializable
{

    protected ?string $id;
    protected ?MessageContent $content;
    protected array $attachments;

    /**
     * @param string|null $id
     * @param MessageContent|null $content
     * @param array $attachments
     * @throws EmptyMessageException
     */
    public function __construct(?string $id, ?MessageContent $content, $attachments = [])
    {
        $this->id = $id;
        $this->content = $content;
        $this->attachments = $attachments;

        if (is_null($content) && empty($this->attachments)) {
            throw new EmptyMessageException('Message should contain content or attachmetns');
        }
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getContent(): ?MessageContent
    {
        return $this->content;
    }

    /**
     * @return MessageAttachment[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'attachments' => $this->attachments,
        ];
    }
}