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

namespace MSP\Notify\Model\Template\Config;

use Magento\Framework\Config\ConverterInterface;

class Converter implements ConverterInterface
{

    const DEFAULT_BLOCK = 'MSP\Notify\Block\Notification\Template';
    /**
     * {@inheritdoc}
     */
    public function convert($source)
    {
        $result = [];
        /** @var \DOMNode $templateNode */
        foreach ($source->getElementsByTagName('template') as $templateNode) {
            if ($templateNode->nodeType != XML_ELEMENT_NODE) {
                continue;
            }
            $templateId = $templateNode->attributes->getNamedItem('id')->nodeValue;
            $templateLabel = $templateNode->attributes->getNamedItem('label')->nodeValue;
            $templateFile = $templateNode->attributes->getNamedItem('file')->nodeValue;
            $templateModule = $templateNode->attributes->getNamedItem('module')->nodeValue;

            $templateBlock = static::DEFAULT_BLOCK;

            if (!is_null($templateNode->attributes->getNamedItem('block'))) {
                $templateBlock = $templateNode->attributes->getNamedItem('block')->nodeValue;
            }

            $result[$templateId] = [
                'label' => $templateLabel,
                'file' => $templateFile,
                'module' => $templateModule,
                'block' => $templateBlock,
            ];
        }
        return $result;
    }
}
