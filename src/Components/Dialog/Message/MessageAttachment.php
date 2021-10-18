<?php
/**
 * Created for plugin-core-dialog
 * Date: 10/11/21 5:55 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Dialog\Components\Dialog\Message;

use JsonSerializable;
use XAKEPEHOK\EnumHelper\EnumHelper;

class MessageAttachment extends EnumHelper implements JsonSerializable
{

    const FILE = 'file';
    const IMAGE = 'image';
    const VOICE = 'voice';

    protected string $name;
    protected string $uri;
    protected string $type;

    public function __construct(string $name, string $uri, string $type)
    {
        self::guardValidValue($type);
        $this->name = $name;
        $this->uri = $uri;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public static function values(): array
    {
        return [
            self::FILE,
            self::IMAGE,
            self::VOICE,
        ];
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'uri' => $this->uri,
            'type' => $this->type,
        ];
    }
}