<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\FontIcons\Forms
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */

namespace SilverWare\FontIcons\Forms;

use SilverStripe\Forms\GroupedDropdownField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;

/**
 * An extension of the grouped dropdown field class for a font icon field.
 *
 * @package SilverWare\FontIcons\Forms
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */
class FontIconField extends GroupedDropdownField
{
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
     * Constructs the object upon instantiation.
     *
     * @param string $name Name of field.
     * @param string $title Title of field.
     * @param array|ArrayAccess $source A map of options used as the data source.
     * @param mixed $value Value of field.
     */
    public function __construct($name, $title = null, $source = [], $value = null)
    {
        // Define Placeholder:
        
        $this->setEmptyString(' ')->setAttribute('data-placeholder', _t(__CLASS__ . '.DROPDOWNSELECT', 'Select'));
        
        // Construct Parent:
        
        parent::__construct($name, $title, $source, $value);
    }
    
    /**
     * Answers an array of HTML attributes for the field.
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = array_merge(
            parent::getAttributes(),
            [
                'data-tag' => $this->backend->getFieldTagName(),
                'data-classes' => $this->backend->getFieldClasses(),
            ]
        );
        
        return $attributes;
    }
    
    /**
     * Answers the source options for the receiver.
     *
     * @return array|ArrayAccess
     */
    public function getSource()
    {
        if (!empty($this->source)) {
            return $this->source;
        }
        
        return $this->backend->getGroupedIcons();
    }
    
    /**
     * Answers an array of valid source values for the receiver.
     *
     * @return array
     */
    public function getSourceValues()
    {
        $values = [];
        
        foreach ($this->getSource() as $category => $icons) {
            $values = array_merge($values, array_keys($icons));
        }
        
        return $values;
    }
    
    /**
     * Answers the field type for the template.
     *
     * @return string
     */
    public function Type()
    {
        return sprintf('fonticonfield %s', parent::Type());
    }
    
    /**
     * Answers an array data object representing an individual option.
     *
     * @param mixed $title Title of group.
     * @param array $icons Icons within this group.
     *
     * @return ArrayData
     */
    protected function getFieldOption($title, $icons)
    {
        if (!is_array($icons)) {
            return parent::getFieldOption($title, $icons);
        }
        
        $options = ArrayList::create();
        
        foreach ($icons as $id => $data) {
            $options->push($this->getFontIconOption($id, $data));
        }
        
        return ArrayData::create([
            'Title' => $title,
            'Options' => $options
        ]);
    }
    
    /**
     * Answers an array data object representing an individual font icon option.
     *
     * @param string $value
     * @param array $data
     *
     * @return ArrayData
     */
    protected function getFontIconOption($value, $data)
    {
        $selected = $this->isSelectedValue($value, $this->Value());
        $disabled = $this->isDisabledValue($value);
        
        return ArrayData::create([
            'Value' => $value,
            'Title' => $data['name'],
            'Unicode' => $data['unicode'],
            'Selected' => $selected,
            'Disabled' => $disabled
        ]);
    }
}
