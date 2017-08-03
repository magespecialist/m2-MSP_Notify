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

namespace MSP\Notify\Api;

use Magento\Framework\Data\Form\Element\Fieldset;
use MSP\Notify\Api\Data\NotificationInterface;
use MSP\Notify\Api\Data\NotificationTemplateInterface;

interface AdapterInterface
{

    /**
     * Adapter name to be showed on admin list
     * @return mixed
     */
    public function getAdapterName();

    /**
     * Adapter identifier
     * @return mixed
     */
    public function getAdapterCode();

    /**
     * Performs actual notification
     * @return bool true on success, false otherwise
     */
    public function notify(NotificationInterface $notification);
}
