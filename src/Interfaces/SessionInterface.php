<?php

namespace Elbucho\Library\Interfaces;

interface SessionInterface
{
    /**
     * Set a key / value pair
     *
     * @access  public
     * @param   string  $key
     * @param   mixed   $value
     * @return  void
     */
    public function set(string $key, $value);

    /**
     * Get a value from the current session
     *
     * @access  public
     * @param   string  $key
     * @return  mixed
     */
    public function get(string $key);

    /**
     * Return the current user from the session
     *
     * @access  public
     * @param   void
     * @return  UserInterface
     */
    public function getUser(): UserInterface;

    /**
     * Unset a key from the session
     *
     * @access  public
     * @param   string  $key
     * @return  void
     */
    public function unset(string $key);

    /**
     * Delete the active session
     *
     * @access  public
     * @param   void
     * @return  void
     */
    public function delete();

    /**
     * Create a new session for the given user
     *
     * @static
     * @access  public
     * @param   UserInterface   $user
     * @return  SessionInterface
     */
    public static function new(UserInterface $user): SessionInterface;
}