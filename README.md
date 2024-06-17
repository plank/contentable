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
```

&nbsp;

## Usage


### Layouts

### Layoutables

### LayoutData

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
