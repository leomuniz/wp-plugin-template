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

Copy this template into a new directory, then use an AI agent (Claude Code) to build your plugin. The `CLAUDE.md` file guides the agent through renaming the template, structuring code, writing tests, and following WordPress standards — you don't need to mention any of that in your prompt.

### What to include in your prompt

Focus on **what the plugin does** and **how you'd architect it**. The template handles coding standards, testing approach, file structure, and build tooling automatically.

**Your prompt should cover:**

- **Plugin name and purpose** — what does it do, who is it for?
- **Data storage decisions** — `wp_options` vs custom tables vs post meta vs transients (and why)
- **User-facing features** — shortcodes, blocks, widgets, frontend output
- **Admin features** — settings pages, dashboard widgets, admin columns
- **API choices** — REST API vs admin-ajax, external API integrations
- **Scheduled tasks** — WP-Cron jobs, data cleanup, cache invalidation
- **Integration points** — other plugins it should work with, hooks to expose

**You can skip (the template handles these):**

- Coding standards and linting setup
- Test framework and testing approach
- File structure and namespacing
- Asset compilation and build tooling
- Template rendering system
- Plugin activation/deactivation boilerplate
- How to produce the final distributable zip

### Prompt template

```
I want to create a WordPress plugin called "{Plugin Name}".

{What the plugin does — 2-3 sentences.}

Architecture:
- {Data storage: where and how data is stored}
- {Features: shortcodes, blocks, REST endpoints, admin pages, etc.}
- {Integrations: other plugins, external APIs, WP-Cron jobs, etc.}
- {Any other technical decisions you'd make as the developer}

This plugin should be built on top of the plugin template in this repo.
Follow the instructions in CLAUDE.md to rename the template first,
then implement the features.
```

### Example

```
I want to create a WordPress plugin called "Event Tracker".

It tracks custom events on the frontend (button clicks, form submissions)
and displays analytics in an admin dashboard.

Architecture:
- Store events in a custom database table (high volume, not suitable for post meta)
- Use the REST API for the frontend JS to send events (POST /events)
- Cache aggregated stats in transients with 1 hour expiry
- Admin dashboard page: total events, events per day chart, top 10 events by count
- [event_tracker_summary] shortcode: displays total count for a given event name
- Settings page: toggle tracking on/off, set data retention period in days
- WP-Cron job to purge events older than the retention period (daily)
- Expose a filter `event_tracker_before_save` so other plugins can modify event data

This plugin should be built on top of the plugin template in this repo.
Follow the instructions in CLAUDE.md to rename the template first,
then implement the features.
```

The more architectural context you provide, the better the output. Think of yourself as the architect and the AI as the builder — you decide *what* and *how*, the template defines the building code.

## License

MIT License. See [LICENSE](LICENSE) for details.
