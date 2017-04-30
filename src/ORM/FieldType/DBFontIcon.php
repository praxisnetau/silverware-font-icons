<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\FontIcons\ORM\FieldType
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */

namespace SilverWare\FontIcons\ORM\FieldType;

use SilverStripe\ORM\Connect\MySQLDatabase;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBField;
use SilverWare\FontIcons\Forms\FontIconField;

/**
 * A database field used to store a font icon reference.
 *
 * @package SilverWare\FontIcons\ORM\FieldType
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-font-icons
 */
class DBFontIcon extends DBField
{
    /**
     * Maps field and method names to the class names of casting objects.
     *
     * @var array
     * @config
     */
    private static $casting = [
        'Tag' => 'HTMLFragment'
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
     * Adds the field to the underlying database.
     *
     * @return void
     */
    public function requireField()
    {
        // Obtain Charset and Collation:
        
        $charset   = MySQLDatabase::config()->charset;
        $collation = MySQLDatabase::config()->collation;
        
        // Define Field Specification:
        
        $spec = [
            'type' => 'varchar',
            'parts' => [
                'datatype' => 'varchar',
                'precision' => 64,
                'collate' => $collation,
                'character set' => $charset,
                'arrayValue' => $this->arrayValue
            ]
        ];
        
        // Require Database Field:
        
        DB::require_field($this->tableName, $this->name, $spec);
    }
    
    /**
     * Answers a form field instance for automatic form scaffolding.
     *
     * @param string $title Title of the field instance.
     * @param array $params Array of extra parameters.
     *
     * @return FontIconField
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        return FontIconField::create($this->name, $title);
    }
    
    /**
     * Answers the font icon tag for the HTML template.
     *
     * @return string
     */
    public function getTag()
    {
        return $this->backend->getTag($this->backend->getClassName('icon', $this->getValue()));
    }
}
