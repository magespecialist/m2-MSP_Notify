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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Page\FaviconInterface;
use Maknz\Slack\Client;
use Maknz\Slack\ClientFactory;
use MSP\Notify\Api\AdapterInterface;
use MSP\Notify\Api\Data\NotificationInterface;

class Slack implements AdapterInterface
{


    const ADAPTER_NAME = 'Slack';
    const ADAPTER_CODE = 'slack';

    const DEFAULT_ICON = ':bear:';
    const DEFAULT_NAME = 'Magento';
    const DEFAULT_COLOR = "#333333";

    protected $client;

    protected $scopeConfig;
    protected $favicon;
    protected $clientFactory;
    protected $fieldsetFactory;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        FaviconInterface $favicon,
        ClientFactory $clientFactory
    ) {
    
        $this->scopeConfig = $scopeConfig;
        $this->favicon = $favicon;
        $this->clientFactory = $clientFactory;
    }

    /**
     * @return \Maknz\Slack\Client
     */
    protected function getClient(NotificationInterface $notification)
    {

        $config = $notification->getChannelConfiguration();

        if (is_null($this->client)) {
            $settings = [
                'username' => $this->getUserName($config),
                'channel' => $config['channel'],
                'icon' => $this->getIcon($config)
            ];

//            $this->client = $this->clientFactory->create(['endpoint' => $config['webhook'], 'attributes' => $settings]);
            $this->client = new Client($config['webhook'], $settings); //must use new because of strange ObjectManager behaviour
        }

        return $this->client;
    }

    /**
     * @return string
     */
    protected function getIcon($config)
    {

        if (isset($config['icon']) && !empty($config['icon'])) {
            return $config['icon'];
        }


        return static::DEFAULT_ICON;
    }

    protected function getUserName($config)
    {
        if (isset($config['name']) && !empty($config['name'])) {
            return $config['name'];
        }

        return static::DEFAULT_NAME . ' @ ' . $this->scopeConfig->getValue('general/store_information/name');
    }

    protected function getColor($config)
    {
        if (isset($config['color']) && !empty($config['color'])) {
            return $config['color'];
        }

        return static::DEFAULT_COLOR;
    }

    /**
     * { @inheritdoc }
     */
    public function notify(NotificationInterface $notification)
    {
        $config = $notification->getChannelConfiguration();

        $this->getClient($notification)->attach([
            'fallback' => $notification->getMessage(),
            'text' => $notification->getMessage(),
            'color' => $this->getColor($config),
        ])->send();

        return true; //slack library returns anything, assume success.
    }

    /**
     * { @inheritdoc }
     */
    public function getAdapterName()
    {
        return static::ADAPTER_NAME;
    }

    /**
     * { @inheritdoc }
     */
    public function getAdapterCode()
    {
        return static::ADAPTER_CODE;
    }
}
