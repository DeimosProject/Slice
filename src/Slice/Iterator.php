<?php

namespace Deimos\Slice;

use Deimos\Helper\Helper;

class Iterator implements \Countable, \Iterator, \Serializable
{

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var []
     */
    protected $storage;

    /**
     * @return int
     */
    public function count()
    {
        return count($this->storage);
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return current($this->storage);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        return next($this->storage);
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return key($this->storage);
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return $this->key() !== null;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        reset($this->storage);
    }

    public function __sleep()
    {
        return ['storage'];
    }

    public function __wakeup()
    {
        $builder      = new \Deimos\Builder\Builder();
        $this->helper = new \Deimos\Helper\Helper($builder);
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return serialize($this->storage);
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        $this->storage = unserialize($serialized, []);
    }

}