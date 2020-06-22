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
    protected $pointer = 0;

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->models[$this->pointer];
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
        return $this->pointer;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return isset($this->models[$this->pointer]);
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * Add a model to the collection
     *
     * @access  public
     * @param   ModelInterface
     * @return  CollectionInterface
     */
}