<?php
/**
 * Created for plugin-core-dialog
 * Date: 02.12.2020
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace Leadvertex\Plugin\Core\Dialog\Factories;

use Leadvertex\Plugin\Core\Dialog\Actions\SenderAction;
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