<?php

namespace Deimos\Slice;

use Deimos\Helper\Helper;

class Slice extends Iterator implements \ArrayAccess
{

    /**
     * Slice constructor.
     *
     * @param Helper      $helper
     * @param array       $storage
     * @param array|Slice $parameters
     */
    public function __construct(Helper $helper, array $storage, $parameters = null)
    {
        $this->helper  = $helper;
        $this->storage = $storage;

        if ($parameters !== null)
        {
            $this->walk($parameters);
        }
    }

    /**
     * @param Slice|array $slice
     */
    protected function walk($slice)
    {
        if (is_array($slice))
        {
            $slice = new Slice($this->helper, $slice);
        }

        array_walk_recursive($this->storage, function (&$value) use ($slice)
        {
            if ($value{0} === '%' && $value{strlen($value) - 1} === '%')
            {
                $path  = substr($value, 1, -1);
                $value = $slice->getData($path);
            }
        });
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->storage;
    }

    /**
     * @param array $storage
     *
     * @return static
     */
    public function setData(array $storage)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * @param string $path
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getData($path, $default = null)
    {
        return $this->helper->arr()->get(
            $this->storage,
            $path,
            $default
        );
    }

    /**
     * @param string $path
     *
     * @return mixed
     * @throws \Deimos\Helper\Exceptions\ExceptionEmpty
     */
    public function getRequired($path)
    {
        return $this->helper->arr()->getRequired($this->storage, $path);
    }

    /**
     * @param string $path
     *
     * @return Slice
     * @throws \Deimos\Helper\Exceptions\ExceptionEmpty
     */
    public function getSlice($path)
    {
        return (clone  $this)
            ->setData($this->getRequired($path));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->helper->json()->encode($this->storage);
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return $this->storage;
    }

    /**
     * @return string
     */
    public function export()
    {
        return var_export($this->storage, true);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return $this->getData($offset) !== null;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset)
        {
            $this->storage[] = $value;
        }
        else
        {
            $path = explode('.', $offset);
            $last = array_pop($path);
            $row  = &$this->storage;

            foreach ($path as $iterator)
            {
                $row = &$row[$iterator];
            }

            $row[$last] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        $path = explode('.', $offset);
        $last = array_pop($path);
        $row  = &$this->storage;

        foreach ($path as $iterator)
        {
            $row = &$row[$iterator];
        }

        unset($row[$last]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        return $this->getData($offset);
    }

}
