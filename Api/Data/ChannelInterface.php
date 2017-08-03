<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Api\Data;

use MSP\Notify\Api\AdapterInterface;

interface ChannelInterface
{
    const ID = 'channel_id';
    const ADAPTER_CODE = 'adapter_code';
    const ADAPTER_CONFIGURATION = 'adapter_configuration';
    const NAME = 'name';

    /**
     * Get value for channel_id
     * @return int
     */
    public function getId();

    /**
     * Get value for adapter_code
     * @return string
     */
    public function getAdapterCode();

    /**
     * Get value for adapter_configuration
     * @return string
     */
    public function getAdapterConfiguration();

    /**
     * Get adapter instance
     * @return AdapterInterface
     */
    public function getAdapterInstance();

    /**
     * Get channel name
     * @return string
     */
    public function getName();

    /**
     * Set value for channel_id
     * @param int $value
     * @return $this
     */
    public function setId($value);

    /**
     * Set value for adapter_code
     * @param string $value
     * @return $this
     */
    public function setAdapterCode($value);

    /**
     * Set value for adapter_configuration
     * @param string $value
     * @return $this
     */
    public function setAdapterConfiguration($value);

    /**
     * Set channel name
     * @param string $value
     * @return $this
     */
    public function setName($value);
}
