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

    /**
     * Find the user based on a provided key
     *
     * @access  public
     * @param   string  $key
     * @return  UserInterface
     */
    public function findByKey(string $key): ?UserInterface;
}