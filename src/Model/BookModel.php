<?php

namespace Elbucho\Library\Model;

class BookModel extends AbstractModel
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
        return strtolower($this->{'title'}) . '_' . $this->{'publisher_id'};
    }
}