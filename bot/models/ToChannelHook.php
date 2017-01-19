<?php
namespace Slack_project\bot\models;

use PhpSlackBot\Webhook\BaseWebhook;

class ToChannelHook extends BaseWebhook
{
    public function configure()
    {
        $this->setName('tochannel');
    }

    public function execute($payload, $context)
    {
        $payload['channel'] = $this->getChannelIdFromChannelName($payload['channel']);
        $this->getClient()->send(json_encode($payload));
    }
}