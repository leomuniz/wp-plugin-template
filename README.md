# WP Plugin Template

A WordPress plugin template for rapid plugin development. Clone this template and customize it to build your own plugin.

## Requirements

- PHP 7.4+
- WordPress 6.0+
- Composer
- Node.js 18+

## Setup

```bash
# Install PHP dependencies (autoloader + dev tools).
composer install

# Install JavaScript dependencies.
npm install

# Build the Gutenberg block.
npm run build
```

## Development

```bash
# Start the block build watcher (rebuilds on file changes).
npm run start

# Run PHP Code Sniffer.
composer phpcs

# Auto-fix coding standard issues.
composer phpcbf

# Lint JavaScript.
npm run lint:js

# Lint CSS.
npm run lint:css

# Run unit tests (PHPUnit + BrainMonkey — no WordPress installation required).
composer test
```

## File Structure

```
wp-plugin-template/
├── wp-plugin-template.php   # Main plugin entry point
├── uninstall.php            # Cleanup on plugin deletion
├── assets/
│   ├── css/main.css         # Frontend stylesheet (plain CSS)
│   ├── js/main.js           # Frontend script (plain JS)
│   └── img/                 # Plugin images
├── build/                   # Compiled block output (gitignored)
├── src/
│   ├── Core/
│   │   ├── Plugin.php       # Singleton bootstrap class
│   │   ├── Assets.php       # Script/style enqueuing
│   │   └── Template.php     # View template rendering
│   ├── Admin/
│   │   └── Admin.php        # Admin settings page
│   ├── Frontend/
│   │   └── Frontend.php     # Frontend hooks
│   └── js/
│       └── blocks/          # Gutenberg block source (React)
│           └── sample-block/
├── views/                   # PHP view templates
│   ├── admin/
│   │   └── settings-page.php
│   └── sample-template.php
├── tests/                   # PHPUnit + BrainMonkey unit tests
├── docs/                    # Developer documentation and hooks guide
├── languages/               # Translation files (.pot/.po/.mo)
├── composer.json            # PHP dependencies + PSR-4 autoloading
├── package.json             # JS dependencies + build scripts
├── phpcs.xml.dist           # WordPress Coding Standards config
└── phpunit.xml.dist         # PHPUnit config
```

## Template Rendering

Use the `Template` class to render PHP view files with variables:

```php
use WPPluginTemplate\Core\Template;

// Returns rendered HTML as a string.
$html = Template::render( 'sample-template', array(
    'title'   => 'Hello World',
    'content' => '<p>Some content here.</p>',
) );

// Outputs rendered HTML directly.
Template::display( 'sample-template', array(
    'title'   => 'Hello World',
    'content' => '<p>Some content here.</p>',
) );
```

## Creating a New Plugin from This Template

See `CLAUDE.md` for detailed instructions on how to adapt this template for a new plugin.

## License

MIT License. See [LICENSE](LICENSE) for details.
