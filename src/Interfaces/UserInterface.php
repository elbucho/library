<?php

namespace Elbucho\Library\Interfaces;

interface UserInterface
{
    /**
     * Get the user's unique key
     *
     * @access  public
     * @param   void
     * @return  mixed
     */
    public function getIndexKey();
}