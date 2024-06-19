<p align="center"><a href="https://plank.co"><img src="art/contentable.png" width="100%"></a></p>

<p align="center">
<a href="https://packagist.org/packages/plank/contentable"><img src="https://img.shields.io/packagist/php-v/plank/contentable?color=%23fae370&label=php&logo=php&logoColor=%23fff" alt="PHP Version Support"></a>
<a href="https://github.com/plank/contentable/actions?query=workflow%3Arun-tests"><img src="https://img.shields.io/github/actions/workflow/status/plank/contentable/run-tests.yml?branch=main&&color=%23bfc9bd&label=run-tests&logo=github&logoColor=%23fff" alt="GitHub Workflow Status"></a>
<a href="https://codeclimate.com/github/plank/contentable/test_coverage"><img src="https://img.shields.io/codeclimate/coverage/plank/contentable?color=%23ff9376&label=test%20coverage&logo=code-climate&logoColor=%23fff" /></a>
<a href="https://codeclimate.com/github/plank/contentable/maintainability"><img src="https://img.shields.io/codeclimate/maintainability/plank/contentable?color=%23528cff&label=maintainablility&logo=code-climate&logoColor=%23fff" /></a>
</p>

# Laravel Contentable

⚠️ This package is currently in development and is not ready for production use. ⚠️

This package allows for models to be built up dynamically by attaching `Content` to it. It's intended use is to allow for 
creating a module system that plays nicely with Laravel Nova (via Repeaters, or other block editing systems) to create user
defined pages. 

Considerations have been made to keep this package compatible with other packages in the Plank ecosystem such as [Snapshots](https://github.com/plank/snapshots).
It also has been architected to allow for explicit linking between modules and other entities within an application.  

## Table of Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [Configuration](#configuration)
- [Usage](#usage)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)
- [Security Vulnerabilities](#security-vulnerabilities)

&nbsp;

## Installation

You can install the package via composer:

```bash
composer require plank/contentable
```

## Quick Start

Once the installation has completed, to begin using the package:

1. Add the `HasContent` trait and `Contentable` interface to any model you'd like to attach content too.
2. Add the `CanRender` trait and `Renderable` interface to any models that will act as "Modules".
3. Implement the missing `Renderable` interface methods, specifically the `renderHtml()` method. Optionally add a `$renderableFields` class property, listing all fields that should be accessed by the module.

## Configuration

The package's configuration file is located at `config/contentable.php`. If you did not publish the config file during installation, you can publish the configuration file using the following command:

```bash
php artisan vendor:publish --tag=contentable-config
# Be sure to run any associated migrations
php artisan migrate
```

&nbsp;

## Usage

### Building a Module system

Contentable's main purpose is to ease the building of a page builder style experience while maintaining explicit relationships
between `Renderable` models and any other arbitrary models within the application. This enables easily building things like
"Callout" modules, that can link to a concrete record in the database.

#### Define Modules

To take advantage of the above approach, any modules that are distinct from each other must be defined as their own models.
Eg, you might define a simple "Text" module (that is, a section on a page that displays a title and body text) like so:

```php
<?php

namespace App\Models;

use App\Models\Contracts\Renderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Concerns\CanRender;

class TextModule extends Model implements Renderable
{
    use CanRender;
    use HasFactory;
    
    protected $guarded = ['id'];

    protected array $renderableFields = ['title', 'body'];
    
    public function renderHtml(): string
    {
        return "<h2>{$this->title}</h2>" +
                "<p>{$this->body}</p>";
    }
}
```

Of course, associated migrations, factories, etc... would need to be generated as well.

#### Define Contentables

Once modules have been created, though, they can then be attached to some `Contentable`, such as a `Page` model
eg:
```php
<?php

namespace App\Models;

use App\Models\Concerns\HasContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plank\Contentable\Contracts\Contentable;


class Page extends Model implements Contentable
{
    use HasContent;
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
```

Such a page can now have any number of modules attached to it, and rendered. This allows most Laravel oriented content management
systems to simply relate a module to a `Page` via a (polymorphic) relationship field. 

You can even get creative and use repeater style fields to build up a page's content in a more editorial fashion!

### Layouts
Layouts enable the user to control the "window dressing" around the modules that are laid out on a page. Effectively, a
layout represents the template used by a `Contentable` model (or any other model for that matter).

Layouts natively support Blade templates and Inertia pages.

Layouts are created automatically when Blade / JS files are created in the appropriate locations. 
By default, these locations are `resources/views/layouts` for Blade templates or `resources/js/Pages/Layouts` for Inertia based systems.

Calling the function `php artisan contentable:sync` will scan these directories 

### Layoutables

Typically, any model with that fulfils the `Conentable` contract will also want to be `Layoutable`. This allows
users to have full control over the display modes on a page. The above `Page` model can be extended as so to add Layout functionality:

```php
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasLayouts;
use Plank\Contentable\Contracts\Contentable;
use Plank\Contentable\Contracts\Layoutable;

class Page extends Model implements Contentable, Layoutable
{
    // ...
    use HasLayouts;
    
    /**
     * (Optional: Allows for layouts to be model specific)
     * Restrict the Page layouts to ones strictly in the Pages folder
     */
    protected static $globalLayouts = false;
}
```
Contentable exposes a function that make it easy to have a particular instance of a model use its set layout. Simply call `->layout()` on it, and pass that to the chosen render function.

eg: 
```php
public function show(Page $page): \Illuminate\View\View
{
    return view($page->layout())->with(compact('page'));
}
```

&nbsp;

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

&nbsp;

## Credits

- [Kurt Friars](https://github.com/kfriars)
- [Massimo Triassi](https://github.com/m-triassi)
- [All Contributors](../../contributors)

&nbsp;

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

&nbsp;

## Security Vulnerabilities

If you discover a security vulnerability within siren, please send an e-mail to [security@plankdesign.com](mailto:security@plankdesign.com). All security vulnerabilities will be promptly addressed.
