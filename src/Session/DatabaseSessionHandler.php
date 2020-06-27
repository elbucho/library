<?php

namespace Elbucho\Library\Session;
use Elbucho\Database\Database;

class DatabaseSessionHandler implements \SessionHandlerInterface
{
    /**
     * Database object
     *
     * @access  private
     * @var     Database
     */
    private $database;

    /**
     * Class constructor
     *
     * @access  public
     * @param   Database    $database
     * @return  \SessionHandlerInterface
     */
    public function __construct(Database $database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * Close the session
     * @link https://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4
     */
    public function close()
    {
        return true;
    }

    /**
     * Destroy a session
     * @link https://php.net/manual/en/sessionhandlerinterface.destroy.php
     * @param string $session_id The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4
     */
    public function destroy($session_id)
    {
        $this->database->exec('
            DELETE FROM
                sessions
            WHERE
                `key` = ?
        ', array($session_id));

        return true;
    }

    /**
     * Cleanup old sessions
     * @link https://php.net/manual/en/sessionhandlerinterface.gc.php
     * @param int $maxlifetime <p>
     * Sessions that have not updated for
     * the last maxlifetime seconds will be removed.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4
     */
    public function gc($maxlifetime)
    {
        $lastDate = new \DateTime('now');
        $lastDate->sub(new \DateInterval(sprintf(
            'P%ds',
            $maxlifetime
        )));

        $this->database->exec('
            DELETE IGNORE FROM
                sessions
            WHERE
                last_accessed_at < ? 
        ', array($lastDate->format('Y-m-d H:i:s')));

        return true;
    }

    /**
     * Initialize session
     * @link https://php.net/manual/en/sessionhandlerinterface.open.php
     * @param string $save_path The path where to store/retrieve the session.
     * @param string $name The session name.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * Read session data
     * @link https://php.net/manual/en/sessionhandlerinterface.read.php
     * @param string $session_id The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4
     */
    public function read($session_id)
    {
        $return = $this->database->query('
            SELECT
                data
            FROM
                sessions
            WHERE
                `key` = ?
        ', array($session_id));

        if (empty($return[0])) {
            return '';
        }

        return base64_decode($return[0]['data']);
    }

    /**
     * Write session data
     * @link https://php.net/manual/en/sessionhandlerinterface.write.php
     * @param string $session_id The session id.
     * @param string $session_data <p>
     * The encoded session data. This data is the
     * result of the PHP internally encoding
     * the $_SESSION superglobal to a serialized
     * string and passing it as this parameter.
     * Please note sessions use an alternative serialization method.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4
     */
    public function write($session_id, $session_data)
    {
        $now = new \DateTimeImmutable('now');

        $this->database->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );

        $this->database->exec('
            REPLACE INTO
                sessions (
                    `key`,
                    `data`,
                    last_accessed_at
                )        
            VALUES
                (
                    :key,
                    :data,
                    :lastAccessed
                )
        ', array(
            'key'           => $session_id,
            'data'          => base64_encode($session_data),
            'lastAccessed'  => $now->format('Y-m-d H:i:s')
        ));

        return true;
    }
}