<?php

namespace Elbucho\Library\Model;

class BookCategoryModel extends AbstractModel
{
    /**
     * Return the index key for this model
     *
     * @access  public
     * @param void
     * @return  string
     */
    public function getIndexKey(): string
    {
        return $this->{'bookId'} . '_' . $this->{'categoryId'};
    }
}