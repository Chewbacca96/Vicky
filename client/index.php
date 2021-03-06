<?php
namespace Vicky;

use Vicky\client\modules\Jira\JiraWebhook;
use Vicky\client\modules\Jira\JiraToSlackBotConverter;
use Vicky\client\modules\Slack\SlackBotSender;

require dirname(__DIR__).'/vendor/autoload.php';
$config = require (isset($argv[1])) ? $argv[1] : '/etc/vicky/clientConfig.php';

ini_set('log_errors', 'On');
ini_set('error_log', $config['error_log']);
ini_set('max_execution_time', 0);
date_default_timezone_set('Europe/Moscow');

$botClient = new SlackBotSender(
    $config['curlOpt']['url'],
    $config['curlOpt']['auth']
);

$jiraWebhook = new JiraWebhook();

JiraWebhook::getEmitter();
JiraWebhook::setConverter('JiraToSlack', new JiraToSlackBotConverter());

$jiraWebhook->addListener('priority.Blocker', function ($event, $data) use ($botClient) {
    $message = JiraWebhook::convert('JiraToSlack', $data);
    $message = "!!! {$message}";
    //$this->toChannel('#general', $message);
    $botClient->toUser('chewbacca', $message);
});

$jiraWebhook->run();
