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

namespace MSP\Notify\Block\Notification;

use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\FileSystem;
use MSP\Notify\Api\AdapterInterface;

/**
 * Class Template
 *
 * This is a replacement of standard Magento Template classes,
 * because msp notify events can be triggered outside area contexts.
 * This is a simplified version of Magento Php Template class implementation.
 *
 * @package MSP\Notify\Block\Notification
 */
class Template implements BlockInterface
{
    private $template;
    private $event;
    private $object;

    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    private $reader;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $file;

    public function __construct(
        \Magento\Framework\Module\Dir\Reader $reader,
        \Magento\Framework\Filesystem\Io\File $file,
        $template,
        $event,
        $object
    ) {
        $this->template = $template;
        $this->event = $event;
        $this->object = $object;
        $this->reader = $reader;
        $this->file = $file;
    }

    /**
     * Get triggering event
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get object payload
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    public function getFileName()
    {
        list($module, $filePath) = \Magento\Framework\View\Asset\Repository::extractModule(
            FileSystem::normalizePath($this->template)
        );
        if ($module) {
            $params['module'] = $module;
        }

        $templatePath = $this->reader->getModuleDir('',$module). '/' . AdapterInterface::TEMPLATE_MODULE_DIR;

        $fullPath = $templatePath . '/' . $filePath;
        return  ($this->file->fileExists($fullPath, true)) ? $fullPath : '';
    }

    /**
     * Produce and return block's html output
     * @return string
     * @throws \Exception
     */
    public function toHtml()
    {
        $fileName = $this->getFileName();
        if (!$fileName) {
            return '';
        }

        // @codingStandardsIgnoreStart
        ob_start();
        try {
            $block = $this;
            include $fileName;
        } catch (\Exception $exception) {
            ob_end_clean();
        }

        $output = ob_get_clean();
        // @codingStandardsIgnoreEnd

        return $output;

    }
}
