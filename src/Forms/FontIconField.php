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

use SilverStripe\Control\HTTPRequest;
use SilverStripe\View\ArrayData;
use SilverWare\Select2\Forms\Select2AjaxField;

/**
 * An extension of the Select2 Ajax field class for a font icon field.
 *
 * @package SilverWare\FontIcons\Forms
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */
class FontIconField extends Select2AjaxField
{
    /**
     * Defines the allowed actions for this field.
     *
     * @var array
     * @config
     */
    private static $allowed_actions = [
        'search'
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
     * Answers the field type for the template.
     *
     * @return string
     */
    public function Type()
    {
        return sprintf('fonticonfield %s', parent::Type());
    }
    
    /**
     * Answers the source items for the field.
     *
     * @return array|ArrayAccess
     */
    public function getSource()
    {
        // Answer Custom Source:
        
        if ($source = $this->source) {
            return $source;
        }
        
        // Answer Default Source:
        
        return $this->backend->getIcons();
    }
    
    /**
     * Answers an HTTP response containing JSON results matching the given search parameters.
     *
     * @param HTTPRequest $request
     *
     * @return HTTPResponse
     */
    public function search(HTTPRequest $request)
    {
        // Detect Ajax:
        
        if (!$request->isAjax()) {
            return;
        }
        
        // Initialise:
        
        $data = [];
        
        // Filter Icons:
        
        if ($term = $request->getVar('term')) {
            
            // Create Groups Array:
            
            $groups = [];
            
            // Iterate Icon Groups:
            
            foreach ($this->backend->getGroupedIcons() as $group => $icons) {
                
                // Create Children Array:
                
                $children = [];
                
                // Iterate Icons in Group:
                
                foreach ($icons as $id => $icon) {
                    
                    if (stripos($id, $term) !== false) {
                        $children[] = $this->getResultData($this->getIconData($id));
                    }
                    
                }
                
                // Create Result Group (if children defined):
                
                if (!empty($children)) {
                    
                    $groups[] = [
                        'text' => $group,
                        'children' => $children
                    ];
                    
                }
                
            }
            
            // Define Results:
            
            $data['results'] = $groups;
            
        }
        
        // Answer JSON Response:
        
        return $this->respond($data);
    }
    
    /**
     * Answers the result format defined for the receiver.
     *
     * @return string
     */
    public function getFormatResult()
    {
        return $this->formatResult ? $this->formatResult : $this->getFormatDefault();
    }
    
    /**
     * Answers the selection format defined for the receiver.
     *
     * @return string
     */
    public function getFormatSelection()
    {
        return $this->formatSelection ? $this->formatSelection : $this->getFormatDefault();
    }
    
    /**
     * Answers the default format for the receiver.
     *
     * @return string
     */
    protected function getFormatDefault()
    {
        return sprintf('<span><%1$s class="%2$s"></%1$s> $Title</span>', $this->FormatTag, $this->FormatClass);
    }
    
    /**
     * Answers the tag for the default format.
     *
     * @return string
     */
    protected function getFormatTag()
    {
        return $this->backend->getFieldTagName();
    }
    
    /**
     * Answers the class for the default format.
     *
     * @return string
     */
    protected function getFormatClass()
    {
        return str_replace('{value}', '$ID', $this->backend->getFieldClasses());
    }
    
    /**
     * Answers the record identified by the given value.
     *
     * @param mixed $id
     *
     * @return ViewableData
     */
    protected function getValueRecord($id)
    {
        return $this->getIconData($id);
    }
    
    /**
     * Answers an array data object for the given icon ID.
     *
     * @param string $id
     *
     * @return ArrayData
     */
    protected function getIconData($id)
    {
        return ArrayData::create(['ID' => $id, 'Title' => $id]);
    }
}
