<?php
/**
 * Created for plugin-core-dialog
 * Date: 10/11/21 6:06 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Dialog\Components\Dialog\Message;

use JsonSerializable;
use Leadvertex\Plugin\Core\Dialog\Components\Dialog\Exceptions\EmptyMessageContentException;

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