<?php namespace XoopsModules\Alumni;

/**
 * Alumni Management System - Helper Class
 *
 * @copyright   XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 * @version     1.0.0
 */

/**
 * Class Helper
 */
class Helper extends \Xmf\Module\Helper
{
    public $debug;

    /**
     * Constructor
     *
     * @param bool $debug Debug mode
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
        $moduleDirName = \basename(\dirname(__DIR__));
        parent::__construct($moduleDirName);
    }

    /**
     * Get singleton instance
     *
     * @param bool $debug Debug mode
     * @return Helper
     */
    public static function getInstance(bool $debug = false): self
    {
        static $instance;
        if (null === $instance) {
            $instance = new static($debug);
        }
        return $instance;
    }

    /**
     * Get module directory name
     *
     * @return string
     */
    public function getDirname(): string
    {
        return $this->dirname;
    }

    /**
     * Get module handler
     *
     * @param string $name Handler name (e.g., 'profile', 'event')
     * @return object Handler object
     * @throws \RuntimeException
     */
    public function getHandler($name)
    {
        $class = __NAMESPACE__ . '\\' . \ucfirst($name) . 'Handler';
        if (!\class_exists($class)) {
            throw new \RuntimeException("Class '$class' not found");
        }
        $db = \XoopsDatabaseFactory::getDatabaseConnection();
        $helper = self::getInstance();
        $ret = new $class($db, $helper);
        $this->addLog("Getting handler '$name'");
        return $ret;
    }
}
