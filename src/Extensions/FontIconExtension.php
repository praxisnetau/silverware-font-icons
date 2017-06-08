<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\FontIcons\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */

namespace SilverWare\FontIcons\Extensions;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Tab;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBField;
use SilverWare\FontIcons\Forms\FontIconField;

/**
 * A data extension class which allows extended objects to use font icons.
 *
 * @package SilverWare\FontIcons\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */
class FontIconExtension extends DataExtension
{
    /**
     * Maps field names to field types for the extended object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'FontIcon' => 'FontIcon'
    ];
    
    /**
     * Maps field and method names to the class names of casting objects.
     *
     * @var array
     * @config
     */
    private static $casting = [
        'FontIconTag' => 'HTMLFragment'
    ];
    
    /**
     * Defines the summary fields of this object.
     *
     * @var array
     * @config
     */
    private static $summary_fields = [
        'FontIconTagCMS'
    ];
    
    /**
     * Defines the injector dependencies for this object.
     *
     * @var array
     * @config
     */
    private static $dependencies = [
        'backend' => '%$FontIconBackend'
    ];
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields List of CMS fields from the extended object.
     *
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Insert Icon Tab:
        
        $fields->insertAfter(
            Tab::create(
                'Icon',
                $this->owner->fieldLabel('Icon')
            ),
            'Main'
        );
        
        // Create Icon Fields:
        
        $fields->addFieldsToTab(
            'Root.Icon',
            [
                FontIconField::create(
                    'FontIcon',
                    $this->owner->fieldLabel('FontIcon')
                )
            ]
        );
    }
    
    /**
     * Updates the field labels of the extended object.
     *
     * @param array $labels Array of field labels from the extended object.
     *
     * @return void
     */
    public function updateFieldLabels(&$labels)
    {
        $labels['Icon'] = $labels['FontIcon'] = $labels['FontIconTagCMS'] = _t(__CLASS__ . '.ICON', 'Icon');
    }
    
    /**
     * Answers a string of font icon class names for the extended object.
     *
     * @return string
     */
    public function getFontIconClass()
    {
        return Convert::raw2att(implode(' ', array_filter($this->owner->getFontIconClassNames())));
    }
    
    /**
     * Answers an array of font icon class names for the extended object.
     *
     * @return array
     */
    public function getFontIconClassNames()
    {
        $classes = [];
        
        if ($this->owner->FontIcon) {
            
            if ($this->owner->FontIconListItem) {
                $classes[] = $this->backend->getClassName('list-item');
            }
            
            if ($this->owner->FontIconFixedWidth) {
                $classes[] = $this->backend->getClassName('fixed-width');
            }
            
            $classes[] = $this->backend->getClassName('icon', [$this->owner->FontIcon]);
            
        }
        
        return $classes;
    }
    
    /**
     * Answers true to enable list item mode.
     *
     * @return boolean
     */
    public function getFontIconListItem()
    {
        return false;
    }
    
    /**
     * Answers true to enable fixed width mode.
     *
     * @return boolean
     */
    public function getFontIconFixedWidth()
    {
        return false;
    }
    
    /**
     * Answers true if the extended object has a font icon defined.
     *
     * @return boolean
     */
    public function hasFontIcon()
    {
        return (boolean) $this->owner->FontIcon;
    }
    
    /**
     * Renders the font icon tag for the HTML template.
     *
     * @return string
     */
    public function getFontIconTag()
    {
        return $this->hasFontIcon() ? $this->backend->getTag($this->owner->FontIconClass) : null;
    }
    
    /**
     * Renders the font icon tag for the CMS interface.
     *
     * @return string
     */
    public function getFontIconTagCMS()
    {
        return DBField::create_field('HTMLFragment', $this->getFontIconTag());
    }
}
