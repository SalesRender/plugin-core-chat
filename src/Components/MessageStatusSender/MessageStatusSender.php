<?php
/**
 * Created for plugin-core-chat
 * Date: 10/14/21 4:39 PM
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Core\Chat\Components\MessageStatusSender;

use SalesRender\Plugin\Components\Access\Registration\Registration;
use SalesRender\Plugin\Components\Db\Components\Connector;
use SalesRender\Plugin\Components\SpecialRequestDispatcher\Components\SpecialRequest;
use SalesRender\Plugin\Components\SpecialRequestDispatcher\Models\SpecialRequestTask;
use XAKEPEHOK\EnumHelper\EnumHelper;
use XAKEPEHOK\Path\Path;

class MessageStatusSender extends EnumHelper
{
    const SENT = 'sent';
    const DELIVERED = 'delivered';
    const READ = 'read';
    const ERROR = 'error';

    public static function values(): array
    {
        return [
            self::SENT,
            self::DELIVERED,
            self::READ,
            self::ERROR,
        ];
    }

    public static function send(string $messageId, string $status): void
    {
        self::guardValidValue($status);

        $data = [
            'id' => $messageId,
            'status' => $status,
        ];

        $registration = Registration::find();
        $uri = (new Path($registration->getClusterUri()))
            ->down('companies')
            ->down(Connector::getReference()->getCompanyId())
            ->down('CRM/plugin/chat/status');

        $ttl = 300;
        $request = new SpecialRequest(
            'PATCH',
            (string) $uri,
            (string) Registration::find()->getSpecialRequestToken($data, $ttl),
            time() + $ttl,
            200,
            [404]
        );

        $dispatcher = new SpecialRequestTask($request, null, 10);
        $dispatcher->save();
    }

}