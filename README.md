# SilverWare Font Icons Module

Provides a new form field and database field type for referencing a font icon. Intended to be used with
[SilverWare][silverware], however this module can also be installed into a regular
[SilverStripe v4][silverstripe-framework] project. A backend for [Font Awesome][font-awesome] is provided
with the module.

## Contents

- [Background](#background)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Issues](#issues)
- [To-Do](#to-do)
- [Contribution](#contribution)
- [Attribution](#attribution)
- [Maintainers](#maintainers)
- [License](#license)

## Background

Font icons are neat. They look great on all devices, and are really easy to reference via HTML and CSS.
However, font icon libraries such as [Font Awesome][font-awesome] provide a staggering amount of icons,
and we can't really expect users to remember the icon names, codes, or have to refer to a cheatsheet.

We could just provide a standard select field listing all of the icons on offer, but users need to know
what the icons look like. Enter `FontIconField` provided by this module. An extension of the SilverStripe
`GroupedDropdownField` class, it provides a searchable list of icons provided by the configured backend,
along with a preview of each icon.

## Requirements

- [SilverStripe Framework v4][silverstripe-framework]

## Installation

Installation is via [Composer][composer]:

```
$ composer require praxisnetau/silverware-font-icons
```

## Configuration

As with all SilverStripe modules, configuration is via YAML. The SilverStripe dependency injector is
used to configure the font icon backend. Font Awesome is configured by default:

```yaml
SilverStripe\Core\Injector\Injector:
  FontIconBackend:
    class: SilverWare\FontIcons\Backends\FontAwesomeBackend
    properties:
      version: 4.7.0
      classes:
        icon: fa-%s
        list-item: fa-li
        fixed-width: fa-fw
```

You can specify the version of Font Awesome in use by changing the `version` property. Upon flush, the
backend will download a full list of icons from GitHub and cache the results until the next flush.

## Usage

### Field Type

To make use of font icons in your code, you can reference the field type in your `$db` array:

```php
use SilverStripe\ORM\DataObject;

class MyObject extends DataObject
{
    private static $db = [
        'Icon' => 'FontIcon'
    ];
}
```

You can also `use` the field type within your class file, and reference the field type directly:

```php
use SilverStripe\ORM\DataObject;
use SilverWare\FontIcons\ORM\FieldType\DBFontIcon;

class MyObject extends DataObject
{
    private static $db = [
        'Icon' => DBFontIcon::class
    ];
}
```

### Form Field

Within your `getCMSFields` method, create a `FontIconField` to allow the user to select an icon:

```php
FontIconField::create('Icon', $this->fieldLabel('Icon'));
```

Don't forget to first `use` the field in the header of your class file:

```php
use SilverWare\FontIcons\Forms\FontIconField;
```

### Data Extension

The module also comes with a data extension class to easily add font icons to data objects within
SilverStripe. The extension adds a `FontIcon` database field, and a new Icon tab within the CMS
containing a `FontIconField`.

To apply the extension to your class, use YAML configuration:

```yaml
MyNamespace\MyObject:
  extensions:
    - SilverWare\FontIcons\Extensions\FontIconExtension
```

## Issues

Please use the [GitHub issue tracker][issues] for bug reports and feature requests.

## To-Do

- Tests

## Contribution

Your contributions are gladly welcomed to help make this project better.
Please see [contributing](CONTRIBUTING.md) for more information.

## Attribution

- Makes use of [Font Awesome][font-awesome] by [Dave Gandy](https://github.com/davegandy).
- Special thanks to [@hailwood](https://github.com/hailwood) for providing a sane example of how to parse
  the source icons from Font Awesome with his [SilverStripe v3 module][silverstripe-fontawesome].

## Maintainers

[![Colin Tucker](https://avatars3.githubusercontent.com/u/1853705?s=144)](https://github.com/colintucker) | [![Praxis Interactive](https://avatars2.githubusercontent.com/u/1782612?s=144)](http://www.praxis.net.au)
---|---
[Colin Tucker](https://github.com/colintucker) | [Praxis Interactive](http://www.praxis.net.au)

## License

[BSD-3-Clause](LICENSE.md) &copy; Praxis Interactive

[silverware]: https://github.com/praxisnetau/silverware
[composer]: https://getcomposer.org
[silverstripe-framework]: https://github.com/silverstripe/silverstripe-framework
[font-awesome]: http://fontawesome.io
[silverstripe-fontawesome]: https://github.com/hailwood/silverstripe-fontawesome
[issues]: https://github.com/praxisnetau/silverware-font-icons/issues
