<?php
/**
 * File: Version1.php
 *
 * NOTE: Fixes have been included in this file; look for "W3TC FIX".
 */
namespace W3TCL\Minify;
/**
 * Class Minify_Controller_Version1
 *
 * @package Minify
 */

/**
 * Controller class for emulating version 1 of minify.php (mostly a proof-of-concept)
 *
 * <code>
 * Minify::serve('Version1');
 * </code>
 *
 * @package Minify
 * @author  Stephen Clay <steve@mrclay.org>
 */
class Minify_Controller_Version1 extends Minify_Controller_Base
{

    /**
     * Set up groups of files as sources
     *
     * @param  array $options controller and Minify options
     * @return array Minify options
     */
    public function setupSources($options)
    {
        // PHP insecure by default: realpath() and other FS functions can't handle null bytes.
        if (isset($_GET['files'])) {
            $_GET['files'] = str_replace("\x00", '', (string) sanitize_text_field(wp_unslash($_GET['files'])));
        }

        self::_setupDefines();
        if (MINIFY_USE_CACHE) {
            $cacheDir = defined('MINIFY_CACHE_DIR')
                ? MINIFY_CACHE_DIR
                : '';
            Minify::setCache($cacheDir);
        }
        $options['badRequestHeader'] = 'HTTP/1.0 404 Not Found';
        $options['contentTypeCharset'] = MINIFY_ENCODING;

        // The following restrictions are to limit the URLs that minify will
        // respond to. Ideally there should be only one way to reference a file.
        $files = isset($_GET['files']) ? sanitize_text_field(wp_unslash($_GET['files'])) : '';
        if (! isset($files)
            // verify at least one file, files are single comma separated,
            // and are all same extension
            || ! preg_match('/^[^,]+\\.(css|js)(,[^,]+\\.\\1)*$/', $files, $m)
            // no "//" (makes URL rewriting easier)
            || strpos($files, '//') !== false
            // no "\"
            || strpos($files, '\\') !== false
            // no "./"
            || preg_match('/(?:^|[^\\.])\\.\\//', $files)
        ) {
            return $options;
        }

        $files = explode(',', $files);
        if (count($files) > MINIFY_MAX_FILES) {
            return $options;
        }

        // W3TC FIX: Override $_SERVER['DOCUMENT_ROOT'] if enabled in settings.
        $docroot = \W3TC\Util_Environment::document_root();

        // strings for prepending to relative/absolute paths
        $prependRelPaths = dirname(isset($_SERVER['SCRIPT_FILENAME']) ? sanitize_text_field(wp_unslash($_SERVER['SCRIPT_FILENAME'])) : '')
            . DIRECTORY_SEPARATOR;
        $prependAbsPaths = $docroot;

        $goodFiles = array();
        $hasBadSource = false;

        $allowDirs = isset($options['allowDirs'])
            ? $options['allowDirs']
            : MINIFY_BASE_DIR;

        foreach ($files as $file) {
            // prepend appropriate string for abs/rel paths
            $file = ($file[0] === '/' ? $prependAbsPaths : $prependRelPaths) . $file;
            // make sure a real file!
            $file = realpath($file);
            // don't allow unsafe or duplicate files
            if (parent::_fileIsSafe($file, $allowDirs)
                && !in_array($file, $goodFiles)
            ) {
                $goodFiles[] = $file;
                $srcOptions = array(
                    'filepath' => $file
                );
                $this->sources[] = new Minify_Source($srcOptions);
            } else {
                $hasBadSource = true;
                break;
            }
        }
        if ($hasBadSource) {
            $this->sources = array();
        }
        if (! MINIFY_REWRITE_CSS_URLS) {
            $options['rewriteCssUris'] = false;
        }
        return $options;
    }

    private static function _setupDefines()
    {
        // W3TC FIX: Override $_SERVER['DOCUMENT_ROOT'] if enabled in settings.
        $docroot = \W3TC\Util_Environment::document_root();

        $defaults = array(
            'MINIFY_BASE_DIR' => realpath($docroot)
            ,'MINIFY_ENCODING' => 'utf-8'
            ,'MINIFY_MAX_FILES' => 16
            ,'MINIFY_REWRITE_CSS_URLS' => true
            ,'MINIFY_USE_CACHE' => true
        );
        foreach ($defaults as $const => $val) {
            if (! defined($const)) {
                define($const, $val);
            }
        }
    }
}
