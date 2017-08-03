<?php
/**
 * MageSpecialist
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magespecialist.it so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2017 Skeeller srl (http://www.magespecialist.it)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace MSP\Notify\Adapter;

use Magento\Framework\Data\Form\Element\Fieldset;
use Magento\Framework\Data\Form\Element\FieldsetFactory;
use MSP\Notify\Api\AdapterInterface;
use MSP\Notify\Api\Data\NotificationInterface;
use MSP\Notify\Api\Data\NotificationTemplateInterface;
use Telegram\Bot\Api;
use Telegram\Bot\ApiFactory;

class Telegram implements AdapterInterface
{

    const ADAPTER_NAME = 'Telegram';
    const ADAPTER_CODE = 'telegram';
    const TELEGRAM_PARSE_MODE = 'HTML';

    protected $client;

    protected $telegramFactory;
    protected $fieldsetFactory;

    public function __construct(
        ApiFactory $telegramFactory,
        FieldsetFactory $fieldsetFactory
    ) {
    
        $this->telegramFactory = $telegramFactory;
        $this->fieldsetFactory = $fieldsetFactory;
    }

    /**
     * @param $token
     * @return Api
     */
    protected function createClient($token)
    {
        $this->client = $this->telegramFactory->create(['token' => $token]);

        return $this->client;
    }

    /**
     * @param null $token
     * @return Api
     */
    protected function getClient($token = null)
    {
        return $this->client;
    }

    /**
     * Adapter name to be showed on admin list
     * @return mixed
     */
    public function getAdapterName()
    {
        return static::ADAPTER_NAME;
    }

    /**
     * Adapter identifier
     * @return mixed
     */
    public function getAdapterCode()
    {
        return static::ADAPTER_CODE;
    }

    /**
     * Performs actual notification
     * @return bool true on success, false otherwise
     */
    public function notify(NotificationInterface $notification)
    {
        $params = $notification->getChannelConfiguration();

        $client = $this->createClient($params['token']);

        $response = $client->sendMessage([
            'chat_id' => $params['chat_id'],
            'text' => $notification->getMessage(),
            'parse_mode' => static::TELEGRAM_PARSE_MODE
        ]);

        return $response->getStatus() != false;
    }
}
