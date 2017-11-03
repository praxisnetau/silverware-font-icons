<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\FontIcons\Interfaces
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */

namespace SilverWare\FontIcons\Interfaces;

/**
 * An interface for a font icon backend implementation.
 *
 * @package SilverWare\FontIcons\Interfaces
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */
interface FontIconBackend
{
    /**
     * Answers a font icon tag with the given class names.
     *
     * @param string $classNames
     *
     * @return string
     */
    public function getTag($classNames);
    
    /**
     * Answers the class name for the given identifier.
     *
     * @param string $identifier
     * @param array $args
     *
     * @return string
     */
    public function getClassName($identifier, $args = []);
    
    /**
     * Answers an associative array of icon IDs mapped to icon names.
     *
     * @return array
     */
    public function getIcons();
    
    /**
     * Answers an array of icons grouped into their respective categories.
     *
     * @return array
     */
    public function getGroupedIcons();
    
    /**
     * Answers the tag name to use for icons within the field.
     *
     * @return string
     */
    public function getFieldTagName();
    
    /**
     * Answers the classes to use for icons within the field.
     *
     * @return string
     */
    public function getFieldClasses();
}
