<?php

namespace Elbucho\Library\Traits;

trait CollectionTrait
{
    /**
     * Collection of models
     *
     * @access  protected
     * @var     array
     */
    protected $models = [];

    /**
     * Current position
     *
     * @access  protected
     * @var     int
     */
    protected $pointer;

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->models[$this->key()];
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        ++$this->pointer;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        $keys = array_keys($this->models);

        return $keys[$this->pointer];
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        if ($this->pointer >= count($this->models)) {
            return false;
        }

        return isset($this->models[$this->key()]);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * Return a count of members in this collection
     *
     * @access  public
     * @param   void
     * @return  int
     */
    public function count(): int
    {
        return count($this->models);
    }
}