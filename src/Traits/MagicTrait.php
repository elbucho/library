<?php

namespace Elbucho\Library\Traits;

trait MagicTrait
{
    /**
     * Protected data
     *
     * @access  protected
     * @param   array
     */
    protected $data = [];

    /**
     * Magic setter
     *
     * @access  public
     * @param   string  $key
     * @param   mixed   $value
     */
    public function __set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Magic getter
     *
     * @access  public
     * @param   string  $key
     * @return  mixed
     */
    public function __get(string $key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * Magic isset
     *
     * @access  public
     * @param   string  $key
     * @return  bool
     */
    public function __isset(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Magic unset function
     *
     * @access  public
     * @param   string  $key
     * @return  void
     */
    public function __unset(string $key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }
}