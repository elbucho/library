<?php

namespace Elbucho\Library\Model;
use Elbucho\Library\Interfaces\CollectionInterface;
use Elbucho\Library\Interfaces\ModelInterface;
use Elbucho\Library\Traits\MagicTrait;
use Respect\Validation\Exceptions\ComponentException;

abstract class AbstractModel implements ModelInterface
{
    use MagicTrait;

    /**
     * Rules Collection
     *
     * @access  public
     * @var     TableRuleCollection
     */
    public $rules;

    /**
     * Class constructor
     *
     * @access  public
     * @param   array   $data
     * @throws  \Exception
     */
    public function __construct(array $data = [])
    {
        $this->rules = $this->getRules();

        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * Magic setter override function
     *
     * @access  public
     * @param   string  $key
     * @param   mixed   $value
     * @return  void
     */
    public function __set(string $key, $value)
    {
        /* @var TableRuleModel $rule */
        $rule = $this->rules->findModelByKey($key);

        if (is_null($rule)) {
            return;
        }

        if ($rule->{'rules'}->validate($value)) {
            $this->data[$key] = $value;
        }
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

    /**
     * Determine whether this model is valid
     *
     * @access  public
     * @param   void
     * @return  bool
     */
    public function isValid(): bool
    {
        foreach ($this->rules as $rule) {
            if ($rule->{'isRequired'}) {
                if (empty($this->data[$rule->{'key'}])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Set the table's rules
     *
     * @abstract
     * @access  protected
     * @param   void
     * @return  TableRuleCollection
     * @throws  ComponentException
     */
    protected abstract function getRules(): TableRuleCollection;
}