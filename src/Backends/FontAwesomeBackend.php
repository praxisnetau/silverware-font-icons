<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\FontIcons\Backends
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */

namespace SilverWare\FontIcons\Backends;

use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Flushable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\View\ArrayData;
use SilverWare\FontIcons\Interfaces\FontIconBackend;
use Symfony\Component\Yaml\Yaml;

/**
 * An implementation of the font icon backend interface for Font Awesome.
 *
 * @package SilverWare\FontIcons\Backends
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */
class FontAwesomeBackend implements FontIconBackend, Flushable
{
    /**
     * Defines the source URL used to obtain icon data.
     *
     * @var string
     */
    protected $source = 'https://raw.githubusercontent.com/FortAwesome/Font-Awesome/{version}/src/icons.yml';
    
    /**
     * An array of identifiers mapped to the equivalent stylesheet class names.
     *
     * @var array
     */
    protected $classes = [];
    
    /**
     * Defines the version of Font Awesome in use.
     *
     * @var string
     */
    protected $version;
    
    /**
     * Clears the font icon cache upon flush.
     *
     * @return void
     */
    public static function flush()
    {
        self::cache()->clear();
    }
    
    /**
     * Answers the cache object.
     *
     * @return CacheInterface
     */
    public static function cache()
    {
        return Injector::inst()->get(CacheInterface::class . '.FontIconCache');
    }
    
    /**
     * Defines the value of the source attribute.
     *
     * @param string $source
     *
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = (string) $source;
        
        return $this;
    }
    
    /**
     * Answers the value of the source attribute.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Defines the value of the classes attribute.
     *
     * @param array $classes
     *
     * @return $this
     */
    public function setClasses($classes)
    {
        $this->classes = (array) $classes;
        
        return $this;
    }
    
    /**
     * Answers the value of the classes attribute.
     *
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }
    
    /**
     * Defines the value of the version attribute.
     *
     * @param string $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = (string) $version;
        
        return $this;
    }
    
    /**
     * Answers the value of the version attribute.
     *
     * @return stringh
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * Answers the tag name to use for icons within the field.
     *
     * @return string
     */
    public function getFieldTagName()
    {
        return 'i';
    }
    
    /**
     * Answers the classes to use for icons within the field.
     *
     * @return string
     */
    public function getFieldClasses()
    {
        return 'fa fa-fw fa-{value}';
    }
    
    /**
     * Answers a font icon tag with the given class names and optional color.
     *
     * @param string $classNames
     * @param string $color
     *
     * @return DBHTMLText
     */
    public function getTag($classNames, $color = null)
    {
        return ArrayData::create([
            'ClassNames' => $classNames,
            'Color' => $color
        ])->renderWith(sprintf('%s\Tag', self::class));
    }
    
    /**
     * Answers the class name for the specified identifier.
     *
     * @param string $identifier
     * @param array $args
     *
     * @return string
     */
    public function getClassName($identifier, $args = [])
    {
        if (isset($this->classes[$identifier])) {
            return $args ? vsprintf($this->classes[$identifier], $args) : $this->classes[$identifier];
        }
    }
    
    /**
     * Answers an associative array of icon IDs mapped to icon names.
     *
     * @return array
     */
    public function getIcons()
    {
        // Initialise:
        
        $icons = [];
        
        // Iterate Grouped Icons:
        
        foreach ($this->getGroupedIcons() as $name => $group) {
            
            foreach ($group as $id => $icon) {
                
                if (!isset($icons[$id])) {
                    $icons[$id] = isset($icon['name']) ? $icon['name'] : $id;
                }
                
            }
            
        }
        
        // Sort Icons by Key:
        
        ksort($icons);
        
        // Answer Icons:
        
        return $icons;
    }
    
    /**
     * Answers an array of icons grouped into their respective categories.
     *
     * @return array
     */
    public function getGroupedIcons()
    {
        // Answer Cached Icons (if available):
        
        if ($icons = self::cache()->get($this->getCacheKey())) {
            return $icons;
        }
        
        // Initialise:
        
        $icons = [];
        
        // Parse Icon Source Data:
        
        $data = Yaml::parse($this->getSourceData());
        
        // Build Icon Groups:
        
        if (isset($data['icons'])) {
            
            foreach ($data['icons'] as $icon) {
                
                foreach ($icon['categories'] as $category) {
                    
                    // Create Category Array:
                    
                    if (!isset($icons[$category])) {
                        $icons[$category] = [];
                    }
                    
                    // Create Icon Element:
                    
                    $icons[$category][$icon['id']] = [
                        'name' => $icon['name'],
                        'unicode' => $icon['unicode']
                    ];
                    
                }
                
            }
            
        }
        
        // Sort Icons by Group:
        
        ksort($icons);
        
        // Sort Icon Groups by Name:
        
        foreach ($icons as &$group) {
            uasort($group, function ($a, $b) {
                return strcasecmp($a['name'], $b['name']);
            });
        }
        
        // Store Icons in Cache:
        
        self::cache()->set($this->getCacheKey(), $icons);
        
        // Answer Grouped Icons:
        
        return $icons;
    }
    
    /**
     * Answers the key used with the cache.
     *
     * @return string
     */
    public function getCacheKey()
    {
        return sprintf('font-awesome-%s', $this->version);
    }
    
    /**
     * Answers the source URL including the desired version.
     *
     * @return string
     */
    public function getSourceURL()
    {
        return str_replace('{version}', $this->getSourceVersion(), $this->getSource());
    }
    
    /**
     * Answers the source version.
     *
     * @return string
     */
    public function getSourceVersion()
    {
        return preg_match('/^[0-9.]+$/', $this->version) ? sprintf('v%s', $this->version) : $this->version;
    }
    
    /**
     * Answers the icon data from the source URL.
     *
     * @return string
     */
    public function getSourceData()
    {
        return file_get_contents($this->getSourceURL());
    }
}
