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

namespace MSP\Notify\Ui\Component\Listing\Channel;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $id = "X";
                if (isset($item["channel_id"])) {
                    $id = $item["channel_id"];
                }
                $item[$name]["edit"] = [
                    "href" => $this->getContext()->getUrl(
                        "adminhtml/channel/edit",
                        ["id" => $id]
                    ),
                    "label" => __("Edit")
                ];

                $item[$name]["delete"] = [
                    "href" => $this->getContext()->getUrl(
                        "adminhtml/channel/delete",
                        ["id" => $id]
                    ),
                    "label" => __("Delete")
                ];
            }
        }

        return $dataSource;
    }
}
