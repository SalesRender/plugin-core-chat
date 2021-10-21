<?php
/**
 * Created for plugin-core-dialog
 * Date: 02.12.2020
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Dialog\Factories;


use Leadvertex\Plugin\Core\Dialog\SendMessageQueue\DialogSendQueueHandleCommand;
use Leadvertex\Plugin\Core\Dialog\SendMessageQueue\DialogSendQueueCommand;
use Symfony\Component\Console\Application;

class ConsoleAppFactory extends \Leadvertex\Plugin\Core\Factories\ConsoleAppFactory
{

    public function build(): Application
    {
        $this->app->add(new DialogSendQueueCommand());
        $this->app->add(new DialogSendQueueHandleCommand());
        return parent::build();
    }

}