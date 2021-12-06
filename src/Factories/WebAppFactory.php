<?php
/**
 * Created for plugin-core-chat
 * Date: 02.12.2020
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Chat\Factories;

use Leadvertex\Plugin\Core\Chat\Actions\SenderAction;
use Slim\App;

class WebAppFactory extends \Leadvertex\Plugin\Core\Factories\WebAppFactory
{

    public function build(): App
    {
        $this->addCors();
        $this->addSpecialRequestAction(new SenderAction());
        return parent::build();
    }

}