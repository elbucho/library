<?php

namespace Elbucho\Library\Interfaces;

interface ModelInterface
{
    /**
     * Magic setter
     *
     * @access  public
     * @param   string  $key
     * @param   mixed   $value
     */
    public function __set(string $key, $value);

    /**
     * Magic getter
     *
     * @access  public
     * @param   string  $key
     * @return  mixed
     */
    public function __get(string $key);

    /**
     * Magic isset
     *
     * @access  public
     * @param   string  $key
     * @return  bool
     */
    public function __isset(string $key): bool;

    /**
     * Magic unset function
     *
     * @access  public
     * @param   string  $key
     * @return  void
     */
    public function __unset(string $key);

    /**
     * Return the index key for this model
     *
     * @access  public
     * @param   void
     * @return  string
     */
    public function getIndexKey(): string;
}