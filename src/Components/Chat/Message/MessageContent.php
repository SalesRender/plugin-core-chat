<?php
/**
 * Created for plugin-core-chat
 * Date: 10/11/21 6:06 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Core\Chat\Components\Chat\Message;

use JsonSerializable;
use SalesRender\Plugin\Core\Chat\Components\Chat\Exceptions\EmptyMessageContentException;

class MessageContent implements JsonSerializable
{

    const PLAIN_TEXT = 'text';
    const MARKDOWN = 'markdown';
    const HTML = 'html';

    protected string $text;
    protected string $format;

    /**
     * @param string $text
     * @param string $format
     * @throws EmptyMessageContentException
     */
    public function __construct(string $text, string $format)
    {
        $text = trim($text);

        if (empty($text)) {
            throw new EmptyMessageContentException('Message text should not be empty');
        }

        $this->text = $text;
        $this->format = $format;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function jsonSerialize(): array
    {
        return [
            'text' => $this->text,
            'format' => $this->format
        ];
    }

    public static function values(): array
    {
        return [
            self::PLAIN_TEXT,
            self::MARKDOWN,
            self::HTML
        ];
    }
}