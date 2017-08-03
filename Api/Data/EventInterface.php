<?php
/**
 * Automatically created by MageSpecialist CodeMonkey
 * https://github.com/magespecialist/m2-MSP_CodeMonkey
 */

namespace MSP\Notify\Api\Data;

interface EventInterface
{
    const ID = 'event_id';
    const LINKED_EVENTS = 'linked_events';
    const CHANNEL_ID = 'channel_id';

    /**
     * Get value for event_id
     * @return int
     */
    public function getId();

    /**
     * Get value for linked_events
     * @return array
     */
    public function getLinkedEvents();

    /**
     * Get value for channel_id
     * @return int
     */
    public function getChannelId();

    /**
     * get Channel Instance
     * @return ChannelInterface
     */
    public function getChannel();

    /**
     * Set value for event_id
     * @param int $value
     * @return $this
     */
    public function setId($value);

    /**
     * Set value for linked_events
     * @param string $value
     * @return $this
     */
    public function setLinkedEvents($value);

    /**
     * Set value for channel_id
     * @param int $value
     * @return $this
     */
    public function setChannelId($value);
}
