<?php
/**
 * Created for plugin-core-dialog
 * Date: 30.11.2020
 * @author Timur Kasumov (XAKEPEHOK)
 */

use Leadvertex\Plugin\Components\Db\Components\Connector;
use Leadvertex\Plugin\Components\Form\Autocomplete\AutocompleteRegistry;
use Leadvertex\Plugin\Components\Form\Form;
use Leadvertex\Plugin\Components\Info\Developer;
use Leadvertex\Plugin\Components\Info\Info;
use Leadvertex\Plugin\Components\Info\PluginType;
use Leadvertex\Plugin\Components\Settings\Settings;
use Leadvertex\Plugin\Components\Translations\Translator;
use Leadvertex\Plugin\Core\Dialog\Components\Sender\SenderInterface;
use Leadvertex\Plugin\Core\Dialog\Actions\SenderAction;
use Leadvertex\Plugin\Core\Dialog\SendMessageQueue\DialogSendQueueHandleCommand;
use Medoo\Medoo;
use XAKEPEHOK\Path\Path;

# 0. Configure environment variable in .env file, that placed into root of app

# 1. Configure DB (for SQLite *.db file and parent directory should be writable)
Connector::config(new Medoo([
    'database_type' => 'sqlite',
    'database_file' => Path::root()->down('db/database.db')
]));

# 2. Set plugin default language
Translator::config('ru_RU');

# 3. Configure info about plugin
Info::config(
    new PluginType(PluginType::DIALOG),
    fn() => Translator::get('info', 'Plugin name'),
    fn() => Translator::get('info', 'Plugin markdown description'),
    [
        "contactType" => "uri",
        "capabilities" => [
            "subject" => true,
            "typing" => false,
            "messages" => [
                "formats" => ["text", "html", "markdown"],
                "incoming" => true,
                "outgoing" => true,
                "writeFirst" => true,
                "statuses" => [
                    "sent",
                    "delivered",
                    "read",
                    "error"
                ]
            ],
            "attachments" => ["files", "images", "voice"]
        ]
    ],
    new Developer(
        'Your (company) name',
        'support.for.plugin@example.com',
        'example.com',
    )
);

# 4. Configure settings form
Settings::setForm(fn() => new Form());

# 5. Configure form autocompletes (or remove this block if dont used)
AutocompleteRegistry::config(function (string $name) {
//    switch ($name) {
//        case 'status': return new StatusAutocomplete();
//        case 'user': return new UserAutocomplete();
//        default: return null;
//    }
});

# 6. Configure DialogQueueHandleCommand
DialogSendQueueHandleCommand::config(new DialogSenderInterface());

# 7. If plugin receive messages via gateway from:
# - webhook: create any custom action that implement \Leadvertex\Plugin\Core\Actions\ActionInterface and add it by
# extends WebAppFactory or in `public/index.php`. In your action your should get webhook data and convert it into
# \Leadvertex\Plugin\Core\Dialog\Components\Dialog\Dialog, after that call Dialog::send()
#
# - API: create any custom console command @see https://symfony.com/doc/current/components/console.html and add it by
# extends ConsoleAppFactory or in `console.php`. Also, you should add your command in cron. Your command should get data
# from gateway API, convert it into \Leadvertex\Plugin\Core\Dialog\Components\Dialog\Dialog and call Dialog::send()