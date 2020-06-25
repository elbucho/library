<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\CollectionInterface;
use Elbucho\Library\Interfaces\ModelInterface;
use Elbucho\Library\Traits\MagicTrait;

abstract class AbstractModel implements ModelInterface
{
    use MagicTrait;

    /**
     * Class constructor
     *
     * @access  public
     * @param   array   $data
     * @throws  \Exception
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Return the data for this model as an array
     *
     * @access  public
     * @param   void
     * @return  array
     */
    public function toArray(): array
    {
        $return = [];

        foreach ($this->data as $key => $value) {
            if ($value instanceof ModelInterface or $value instanceof CollectionInterface) {
                $return[$key] = $value->toArray();
            } elseif ($value instanceof \DateTimeInterface) {
                $return[$key] = $value->format('Y-m-d H:i:s');
            } elseif (is_object($value)) {
                $return[$key] = serialize($value);
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }

    /**
     * Return the data for this model as a JSON string
     *
     * @access  public
     * @param   void
     * @return  string
     */
    public function toJSON(): string
    {
        return json_encode($this->toArray());
    }
}