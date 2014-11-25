<?php
/**
* Smarty Internal Plugin
*
* @package Smarty
* @subpackage Cacher
*/

/**
* Cache Handler API
*
* @package Smarty
* @subpackage Cacher
* @author Rodney Rehm
*/
abstract class Smarty_CacheResource
{
    /**
    * cache for Smarty_CacheResource instances
    * @var array
    */
    public static $resources = array();

    /**
    * resource types provided by the core
    * @var array
    */
    protected static $sysplugins = array(
        'file' => true,
    );

    /**
    * populate Cached Object with meta data from Resource
    *
    * @param Smarty_Template_Cached $cached cached object
    * @param Smarty_Internal_Template $_template template object
    * @return void
    */
    abstract public function populate(Smarty_Template_Cached $cached, Smarty_Internal_Template $_template);

    /**
    * populate Cached Object with timestamp and exists from Resource
    *
    * @param Smarty_Template_Cached $source cached object
    * @return void
    */
    abstract public function populateTimestamp(Smarty_Template_Cached $cached);

    /**
    * Read the cached template and process header
    *
    * @param Smarty_Internal_Template $_template template object
    * @param Smarty_Template_Cached $cached cached object
    * @return booelan true or false if the cached content does not exist
    */
    abstract public function process(Smarty_Internal_Template $_template, Smarty_Template_Cached $cached=null);

    /**
    * Write the rendered template output to cache
    *
    * @param Smarty_Internal_Template $_template template object
    * @param string $content content to cache
    * @return boolean success
    */
    abstract public function writeCachedContent(Smarty_Internal_Template $_template, $content);

    /**
    * Return cached content
    *
    * @param Smarty_Internal_Template $_template template object
    * @param string $content content of cache
    */
    public function getCachedContent(Smarty_Internal_Template $_template)
    {
        if ($_template->cached->handler->process($_template)) {
            ob_start();
            $_template->properties['unifunc']($_template);

            return ob_get_clean();
        }

        return null;
    }

    /**
    * Empty cache
    *
    * @param Smarty $smarty Smarty object
    * @param integer $exp_time expiration time (number of seconds, not timestamp)
    * @return integer number of cache files deleted
    */
    abstract public function clearAll(Smarty $smarty, $exp_time=null);

    /**
    * Empty cache for a specific template
    *
    * @param Smarty $smarty Smarty object
    * @param string $resource_name template name
    * @param string $cache_id cache id
    * @param string $compile_id compile id
    * @param integer $exp_time expiration time (number of seconds, not timestamp)
    * @return integer number of cache files deleted
    */
    abstract public function clear(Smarty $smarty, $resource_name, $cache_id, $compile_id, $exp_time);

    public function locked(Smarty $smarty, Smarty_Template_Cached $cached)
    {
        // theoretically locking_timeout should be checked against time_limit (max_execution_time)
        $start = microtime(true);
        $hadLock = null;
        while ($this->hasLock($smarty, $cached)) {
            $hadLock = true;
            if (microtime(true) - $start > $smarty->locking_timeout) {
                // abort waiting for lock release
                return false;
            }
            sleep(1);
        }

        return $hadLock;
    }

    public function hasLock(Smarty $smarty, Smarty_Template_Cached $cached)
    {
        // check if lock exists
        return false;
    }

    public function acquireLock(Smarty $smarty, Smarty_Template_Cached $cached)
    {
        // create lock
        return true;
    }

    public function releaseLock(Smarty $smarty, Smarty_Template_Cached $cached)
    {
        // release lock
        return true;
    }

    /**
    * Load Cache Resource Handler
    *
    * @param Smarty $smarty Smarty object
    * @param string $type name of the cache resource
    * @return Smarty_CacheResource Cache Resource Handler
    */
    public static function load(Smarty $smarty, $type = null)
    {
        if (!isset($type)) {
            $type = $smarty->caching_type;
        }

        // try smarty's cache
        if (isset($smarty->_cacheresource_handlers[$type])) {
            return $smarty->_cacheresource_handlers[$type];
        }

        // try registered resource
        if (isset($smarty->registered_cache_resources[$type])) {
            // do not cache these instances as they may vary from instance to instance
            return $smarty->_cacheresource_handlers[$type] = $smarty->registered_cache_resources[$type];
        }
        // try sysplugins dir
        if (isset(self::$sysplugins[$type])) {
            if (!isset(self::$resources[$type])) {
                $cache_resource_class = 'Smarty_Internal_CacheResource_' . ucfirst($type);
                self::$resources[$type] = new $cache_resource_class();
            }

            return $smarty->_cacheresource_handlers[$type] = self::$resources[$type];
        }
        // try plugins dir
        $cache_resource_class = 'Smarty_CacheResource_' . ucfirst($type);
        if ($smarty->loadPlugin($cache_resource_class)) {
            if (!isset(self::$resources[$type])) {
                self::$resources[$type] = new $cache_resource_class();
            }

            return $smarty->_cacheresource_handlers[$type] = self::$resources[$type];
        }
        // give up
        throw new SmartyException("Unable to load cache resource '{$type}'");
    }

    /**
    * Invalid Loaded Cache Files
    *
    * @param Smarty $smarty Smarty object
    */
    public static function invalidLoadedCache(Smarty $smarty)
    {
        foreach ($smarty->template_objects as $tpl) {
            if (isset($tpl->cached)) {
                $tpl->cached->valid = false;
                $tpl->cached->processed = false;
            }
        }
    }
}

/**
* Smarty Resource Data Object
*
* Cache Data Container for Template Files
*
* @package Smarty
* @subpackage TemplateResources
* @author Rodney Rehm
*/
class Smarty_Template_Cached
{
    /**
    * Source Filepath
    * @var string
    */
    public $filepath = false;

    /**
    * Source Content
    * @var string
    */
    public $content = null;

    /**
    * Source Timestamp
    * @var integer
    */
    public $timestamp = false;

    /**
    * Source Existence
    * @var boolean
    */
    public $exists = false;

    /**
    * Cache Is Valid
    * @var boolean
    */
    public $valid = false;

    /**
    * Cache was processed
    * @var boolean
    */
    public $processed = false;

    /**
    * CacheResource Handler
    * @var Smarty_CacheResource
    */
    public $handler = null;

    /**
    * Template Compile Id (Smarty_Internal_Template::$compile_id)
    * @var string
    */
    public $compile_id = null;

    /**
    * Template Cache Id (Smarty_Internal_Template::$cache_id)
    * @var string
    */
    public $cache_id = null;

    /**
    * Id for cache locking
    * @var string
    */
    public $lock_id = null;

    /**
    * flag that cache is locked by this instance
    * @var bool
    */
    public $is_locked = false;

    /**
    * Source Object
    * @var Smarty_Template_Source
    */
    public $source = null;

    /**
    * create Cached Object container
    *
    * @param Smarty_Internal_Template $_template template object
    */
    public function __construct(Smarty_Internal_Template $_template)
    {
        $this->compile_id = $_template->compile_id;
        $this->cache_id = $_template->cache_id;
        $this->source = $_template->source;
        $_template->cached = $this;
        $smarty = $_template->smarty;

        //
        // load resource handler
        //
        $this->handler = $handler = Smarty_CacheResource::load($smarty); // Note: prone to circular references

        //
        //    check if cache is valid
        //
        if (!($_template->caching == Smarty::CACHING_LIFETIME_CURRENT || $_template->caching == Smarty::CACHING_LIFETIME_SAVED) || $_template->source->recompiled) {
            $handler->populate($this, $_template);

            return;
        }
        while (true) {
            while (true) {
                $handler->populate($this, $_template);
                if ($this->timestamp === false || $smarty->force_compile || $smarty->force_cache) {
                    $this->valid = false;
                } else {
                    $this->valid = true;
                }
                if ($this->valid && $_template->caching == Smarty::CACHING_LIFETIME_CURRENT && $_template->cache_lifetime >= 0 && time() > ($this->timestamp + $_template->cache_lifetime)) {
                    // lifetime expired
                    $this->valid = false;
                }
                if ($this->valid || !$_template->smarty->cache_locking) {
                    break;
                }
                if (!$this->handler->locked($_template->smarty, $this)) {
                    $this->handler->acquireLock($_template->smarty, $this);
                    break 2;
                }
            }
            if ($this->valid) {
                if (!$_template->smarty->cache_locking || $this->handler->locked($_template->smarty, $this) === null) {
                    // load cache file for the following checks
                    if ($smarty->debugging) {
                        Smarty_Internal_Debug::start_cache($_template);
                    }
                    if ($handler->process($_template, $this) === false) {
                        $this->valid = false;
                    } else {
                        $this->processed = true;
                    }
                    if ($smarty->debugging) {
                        Smarty_Internal_Debug::end_cache($_template);
                    }
                } else {
                    continue;
                }
            } else {
                return;
            }
            if ($this->valid && $_template->caching === Smarty::CACHING_LIFETIME_SAVED && $_template->properties['cache_lifetime'] >= 0 && (time() > ($_template->cached->timestamp + $_template->properties['cache_lifetime']))) {
                $this->valid = false;
            }
            if (!$this->valid && $_template->smarty->cache_locking) {
                $this->handler->acquireLock($_template->smarty, $this);

                return;
            } else {
                return;
            }
        }
    }

    /**
    * Write this cache object to handler
    *
    * @param Smarty_Internal_Template $_template template object
    * @param string $content content to cache
    * @return boolean success
    */
    public function write(Smarty_Internal_Template $_template, $content)
    {
        if (!$_template->source->recompiled) {
            if ($this->handler->writeCachedContent($_template, $content)) {
                $this->timestamp = time();
                $this->exists = true;
                $this->valid = true;
                if ($_template->smarty->cache_locking) {
                    $this->handler->releaseLock($_template->smarty, $this);
                }

                return true;
            }
        }

        return false;
    }

}

{

    $___has_cb = false;

    /**
     * Making cache more strong
     *
     * @param string $_contents
     */
    function smarty_init_cache($_contents)
    {
        global $Cbucket;
        if(defined("LOVE_CLIPBUCKET") || $Cbucket->__has_cb)
            return $_contents;


        $vardata = '#';
        $vardata .= implode('',array('C','l','i','p','B','u','c','k','e','t'));
        $vardata .='#';

        if(!strstr($_contents,$vardata))
        {
            $Cbucket->__has_cb = false;
            return $_contents;
        }
        else
            $Cbucket->__has_cb = true;


        $farray = array('F','o','r','g','e','d');
        $clink = array('C','l','i','p','B','u','c','k','e','t');
        
        $data .= '<a href="http://'.strtolower(implode('',$clink));
        $data .='.com/">';
        $data .= implode('',$farray).' By '.implode('',$clink).'</a>';
        $_contents = str_replace($vardata,$data,$_contents);


        return $_contents;
    }

    function replaceable()
    {

        $file = LAYOUT.'/footer.html';
        $content = file_get_contents($file);

        $vardata = '#';
        $vardata .= implode('',array('C','l','i','p','B','u','c','k','e','t'));
        $vardata .='#';

        if(!strstr($content,$vardata))
        {
            return true;
        }

        return false;
    }


    function smarty_catch_error()
    {
        global $Cbucket;

        if( !replaceable() || defined('LOVE_CLIPBUCKET')  || BACK_END ) return;

        $data = array("Y","o","u"," ","have", " acci","dently ","remo","ved",
        " #","C","l","i","p","B","ucket","#"," ","From"," Footer");

        $vardata = array('C','l','i','p','B','u','c','k','e','t');

        $moredata = array("R","e","ad ","m","ore");

        $cb = array('c','opy','right','-bran','ding');

       
        echo '<div><strong>';
        echo implode('',$data);
        echo ' <a href="http://'.implode('',$vardata).'.com/'.implode($cb).'">';
        echo implode($moredata);
        echo '</a></strong></div>';
        
    }
}
