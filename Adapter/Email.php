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
use Magento\Framework\Data\Form\Element\Fieldset;
use Magento\Framework\Data\Form\Element\FieldsetFactory;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\Message;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\MessageInterfaceFactory;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\TransportInterfaceFactory;
use MSP\Notify\Api\AdapterInterface;
use MSP\Notify\Api\Data\NotificationInterface;

class Email implements AdapterInterface
{

    const ADAPTER_NAME = "Email";
    const ADAPTER_CODE = 'email';

    /**
     * @var FieldsetFactory
     */
    private $fieldsetFactory;
    /**
     * @var TransportInterfaceFactory
     */
    private $transportInterfaceFactory;
    /**
     * @var SenderResolverInterface
     */
    private $senderResolver;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var MessageInterfaceFactory
     */
    private $messageFactory;

    /**
     * Email constructor.
     */
    public function __construct(
        FieldsetFactory $fieldsetFactory,
        TransportInterfaceFactory $transportInterfaceFactory,
        MessageInterfaceFactory $messageInterfaceFactory,
        SenderResolverInterface $senderResolver,
        ScopeConfigInterface $scopeConfig
    ) {
    
        $this->fieldsetFactory = $fieldsetFactory;
        $this->transportInterfaceFactory = $transportInterfaceFactory;
        $this->senderResolver = $senderResolver;
        $this->scopeConfig = $scopeConfig;
        $this->messageFactory = $messageInterfaceFactory;
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
        $config = $notification->getChannelConfiguration();

        $sender = $this->scopeConfig->getValue('contact/email/recipient_email');
        try {
            $sender = $this->senderResolver->resolve($sender);
        } catch (MailException $e) {
            $sender = ['email' => "test@example.org", 'name' => "sender"];
        }

        $message = $this->messageFactory->create();

        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($config['email']);
        $message->setMessageType(MessageInterface::TYPE_HTML);
        $message->setBody($notification->getMessage());
        $message->setSubject($config['subject']);

        $transport = $this->transportInterfaceFactory->create(['message' => $message]);
        
        try {
            $transport->sendMessage();
            return true;
        } catch (MailException $e) {
            return false;
        }
    }
}
