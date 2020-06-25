<?php

namespace Elbucho\Library\Model;

class AuthorModel extends AbstractModel
{
    /**
     * Return the index key for this model
     *
     * @access  public
     * @param   void
     * @return  string
     */
    public function getIndexKey(): string
    {
        return strtolower($this->{'lastName'} . '_' . $this->{'firstName'});
    }
}