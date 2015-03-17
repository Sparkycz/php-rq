<?php

namespace PhpRQ;

/**
 * @author Jakub Chábek <jakub.chabek@heureka.cz>
 *
 * @method ClientInterface|\Predis\Pipeline\Pipeline|array pipeline()
 */
interface ClientInterface extends \Predis\ClientInterface
{

    /**
     * @param string $queue      Name of the queue
     * @param string $processing Name of the processing queue
     * @param string $timeouts   Name of the hash which holds the creation times of each processing queue
     * @param int    $size       Size of the chunk we want to fetch. It's better to keep it low (e.g. 100)
     * @param float  $time       Current time used identify the expired items so that they can be re-enqueued
     *
     * @return array Returns $size (or less, if not available) items from the pool
     */
    public function queueGet($queue, $processing, $timeouts, $size, $time);

    /**
     * @param mixed  $processing Name of the processing queue
     * @param string $timeouts   Name of the hash which holds the creation times of each processing queue
     * @param mixed  $item       Item we want to ACK
     */
    public function queueAck($processing, $timeouts, $item);

    /**
     * @param string $queue      Name of the queue
     * @param mixed  $processing Name of the processing queue
     * @param string $timeouts   Name of the hash which holds the creation times of each processing queue
     * @param mixed  $item       Item we want to reject
     */
    public function queueReject($queue, $processing, $timeouts, $item);

    /**
     * @param string $queue      Name of the queue
     * @param string $processing Name of the processing queue
     * @param string $timeouts   Name of the hash which holds the creation times of each processing queue
     */
    public function queueReEnqueue($queue, $processing, $timeouts);

    /**
     * @param string $queue Name of the queue
     * @param string $set   Name of the set which guarantees uniqueness of the queue
     * @param mixed  $item  Item we want to add into unique queue
     */
    public function uniqueQueueAdd($queue, $set, $item);

    /**
     * @param string $queue      Name of the queue
     * @param string $set        Name of the set which guarantees uniqueness of the queue
     * @param string $processing Name of the processing queue
     * @param string $timeouts   Name of the hash which holds the creation times of each processing queue
     * @param int    $size       Size of the chunk we want to fetch. It's better to keep it low (e.g. 100)
     * @param float  $time       Current time used identify the expired items so that they can be re-enqueued
     *
     * @return array Returns $size (or less, if not available) items from the queue
     */
    public function uniqueQueueGet($queue, $set, $processing, $timeouts, $size, $time);

    /**
     * @param mixed  $processing Name of the processing queue
     * @param string $timeouts   Name of the hash which holds the creation times of each processing queue
     * @param mixed  $item       Item we want to ACK
     */
    public function uniqueQueueAck($processing, $timeouts, $item);

    /**
     * @param string $queue      Name of the queue
     * @param string $set        Name of the set which guarantees uniqueness of the queue
     * @param mixed  $processing Name of the processing queue
     * @param string $timeouts   Name of the hash which holds the creation times of each processing queue
     * @param mixed  $item       Item we want to reject
     */
    public function uniqueQueueReject($queue, $set, $processing, $timeouts, $item);

    /**
     * @param string $queue      Name of the queue
     * @param string $set        Name of the set which guarantees uniqueness of the queue
     * @param string $processing Name of the processing queue
     * @param string $timeouts   Name of the hash which holds the creation times of each processing queue
     */
    public function uniqueQueueReEnqueue($queue, $set, $processing, $timeouts);

    /**
     * @param string $pool   Name of the pool
     * @param int    $size   Size of the chunk we want to fetch. It's better to keep it low (e.g. 100)
     * @param int    $until  Timestamp until which we want to fetch items. This should be equal to time() or
     *                       something similar. This is used to prevent processing the items too often.
     * @param int    $ackTTL Number of seconds after which the item is considered as "not processed correctly"
     *                       and thus may be processed again by another process.
     *
     * @return array Returns $size (or less, if not available) items from the pool
     */
    public function poolGet($pool, $size, $until, $ackTTL);

    /**
     * @param string $pool           Name of the pool
     * @param mixed  $item           Item we want to ACK
     * @param int    $processAgainAt Timestamp at which the item should be (= may be) processed again.
     *                               This timestamp may of course be changed by pushing the item into pool again.
     *
     * @return bool True if item was ACKed, false otherwise (someone re-added item into pool before we ACKed it).
     */
    public function poolAck($pool, $item, $processAgainAt);

    /**
     * @param string $pool Name of the pool
     * @param mixed  $item Item we want to ACK
     *
     * @return bool True if item was removed, false otherwise (same as for the ACK method).
     */
    public function poolRemove($pool, $item);

}
