# WP Plugin Template — AI Agent Guide

This is a WordPress plugin template. When starting work on a new plugin built from this template, follow the instructions below.

## First Task: Rename the Template

Before writing any feature code, you MUST update all template references to match the new plugin. This is a search-and-replace operation across the entire project.

### What to replace

| Template value | Replace with | Example |
|---|---|---|
| `WP Plugin Template` | New plugin display name | `My Awesome Plugin` |
| `wp-plugin-template` | New plugin slug (lowercase, hyphenated) | `my-awesome-plugin` |
| `wp_plugin_template` | New plugin slug (lowercase, underscored) | `my_awesome_plugin` |
| `WPPluginTemplate` | New PHP namespace (PascalCase) | `MyAwesomePlugin` |
| `WP_PLUGIN_TEMPLATE` | New constant prefix (uppercase, underscored) | `MY_AWESOME_PLUGIN` |
| `wp-plugin-template/sample-block` | New block namespace/name | `my-awesome-plugin/sample-block` |
| `Your Name` | Author name | — |
| `https://example.com` | Author/plugin URLs | — |
| `your-vendor` | Composer vendor name | — |

### Files that need renaming

- `wp-plugin-template.php` → `{new-slug}.php` (the main plugin file)
- The folder name should also match the slug (the user handles this manually)

### Where these values appear

- `wp-plugin-template.php` — Plugin header, constants, autoloader path
- `composer.json` — Package name, description, namespace in PSR-4 autoload
- `package.json` — Package name, description
- `phpcs.xml.dist` — Ruleset name, text domain property
- `phpunit.xml.dist` — Test suite name
- `uninstall.php` — Option name
- `src/Core/Plugin.php` — Namespace, text domain in `load_textdomain()`
- `src/Core/Assets.php` — Namespace, handle prefixes, constant references
- `src/Core/Template.php` — Namespace, constant references
- `src/Admin/Admin.php` — Namespace, menu labels, setting names, option names
- `src/Frontend/Frontend.php` — Namespace
- `src/js/blocks/sample-block/block.json` — Block name, text domain
- `src/js/blocks/sample-block/*.js` — Text domain in `__()` calls
- `views/admin/settings-page.php` — Setting group name
- `tests/bootstrap.php` — Plugin file path
- `tests/SampleTest.php` — Namespace, class references
- `readme.txt` — Rewrite entirely for the new plugin (WordPress.org listing: description, installation, FAQ, changelog)
- `README.md` — Rewrite entirely for the new plugin (GitHub-facing: dev setup, architecture, usage)
- `LICENSE` — Copyright holder name

### After renaming

Run these commands to verify everything works:

```bash
composer install
npm install
npm run build
composer phpcs
```

## About the Sample Gutenberg Block

This template includes a sample Gutenberg block at `src/js/blocks/sample-block/`.

**When starting a new plugin, ask the user:**
1. Should the sample block be kept and renamed/adapted for the plugin's needs?
2. Should the sample block be removed entirely? (If so, also remove the `register_blocks()` method in `Plugin.php`)
3. Should additional blocks be created?

### How blocks work in this template

- Block source code lives in `src/js/blocks/{block-name}/`
- Each block has a `block.json` manifest, `index.js` entry, `edit.js` (editor), and `save.js` (frontend)
- `npm run build` compiles blocks to `build/blocks/{block-name}/`
- `npm run start` watches for changes and rebuilds automatically
- Blocks are registered in PHP via `Plugin::register_blocks()` using `register_block_type()` pointed at the `build/` directory
- To add a new block: create a new folder under `src/js/blocks/` with `block.json` + `index.js` + `edit.js` + `save.js`

## Architecture

### Plugin bootstrap flow

1. WordPress loads `wp-plugin-template.php` (main plugin file)
2. Constants are defined (`*_VERSION`, `*_FILE`, `*_DIR`, `*_URL`)
3. Composer autoloader is loaded (`vendor/autoload.php`)
4. `Plugin::instance()` is called → singleton boots the plugin
5. `Plugin` registers lifecycle hooks (activation, deactivation, init)
6. `Plugin` initializes components: `Assets`, `Admin`, `Frontend`

### Directory structure

```
src/
  Core/        → Plugin bootstrap, asset enqueuing, template rendering
  Admin/       → Admin pages, settings, admin-only features
  Frontend/    → Frontend hooks, shortcodes, public-facing features
  js/          → JavaScript/React source (compiled by @wordpress/scripts)
    blocks/    → Gutenberg block source files

views/         → PHP templates rendered by Template::render()
assets/        → Static files (plain CSS, JS, images) — NOT compiled
build/         → Compiled JS/CSS output from @wordpress/scripts (gitignored)
tests/         → PHPUnit + BrainMonkey unit tests
docs/          → Developer documentation and hooks guide
languages/     → Translation files (.pot, .po, .mo)
```

### Key classes

| Class | File | Purpose |
|---|---|---|
| `Plugin` | `src/Core/Plugin.php` | Singleton. Boots the plugin, registers hooks, initializes components. |
| `Assets` | `src/Core/Assets.php` | Enqueues `assets/css/main.css` and `assets/js/main.js` on the frontend. |
| `Template` | `src/Core/Template.php` | Renders PHP views from `views/` with variable passing. |
| `Admin` | `src/Admin/Admin.php` | Registers the settings page under Settings menu. |
| `Frontend` | `src/Frontend/Frontend.php` | Frontend hooks placeholder. |

### Template rendering

Use `WPPluginTemplate\Core\Template` to render view files:

```php
use WPPluginTemplate\Core\Template;

// Render and return as string.
$html = Template::render( 'sample-template', array( 'title' => 'Hello' ) );

// Render and echo directly.
Template::display( 'admin/settings-page' );
```

- Template names are relative to `views/` without the `.php` extension.
- Variables passed in the array are extracted and available as local variables in the template.
- Always escape output in template files (`esc_html()`, `esc_attr()`, `wp_kses_post()`, etc.).

### Adding new features

When adding a new feature:

1. Create a new class in the appropriate `src/` subdirectory (`Admin/`, `Frontend/`, or a new directory)
2. Use the `WPPluginTemplate\{Subdirectory}` namespace — PSR-4 autoloading handles the rest
3. Instantiate the class from `Plugin::init_components()` if it needs to register WordPress hooks
4. If it needs view templates, create them in `views/` and use `Template::render()`
5. If it needs admin-only assets, enqueue them conditionally in the class constructor

### Adding new blocks

1. Create `src/js/blocks/{block-name}/` with: `block.json`, `index.js`, `edit.js`, `save.js`
2. Add `register_block_type( WP_PLUGIN_TEMPLATE_DIR . 'build/blocks/{block-name}' )` in `Plugin::register_blocks()`
3. Run `npm run build` (or `npm run start` for watch mode)

## Versioning

This plugin follows [Semantic Versioning 2.0.0](https://semver.org/).

**When completing a development round**, update the version in ALL of these locations:

1. **Main plugin file** (`wp-plugin-template.php`) — the `Version:` line in the plugin header comment
2. **Main plugin file** (`wp-plugin-template.php`) — the `WP_PLUGIN_TEMPLATE_VERSION` constant
3. **`readme.txt`** — the `Stable tag:` header AND add a changelog entry under `== Changelog ==`

All version values MUST always match. Use semantic versioning:

- **MAJOR** (X.0.0) — Incompatible API changes, breaking changes to hooks or public methods
- **MINOR** (0.X.0) — New features added in a backward-compatible way
- **PATCH** (0.0.X) — Backward-compatible bug fixes

Examples:
- Adding a new admin settings field → minor bump (1.0.0 → 1.1.0)
- Fixing a bug in an existing feature → patch bump (1.1.0 → 1.1.1)
- Renaming a public hook or changing method signatures → major bump (1.1.1 → 2.0.0)

## Developer Documentation

The `docs/` folder is the documentation hub for developers and AI agents who need to **use** or **integrate with** this plugin.

### When to create documentation

After implementing any feature that exposes public functionality — hooks, shortcodes, REST API endpoints, template tags, or public methods — create or update documentation in `docs/`.

### Required: Hooks Guide

Create a `docs/hooks.md` file that documents **every action and filter** the plugin fires or consumes. This is critical for other plugins and AI agents to integrate with this plugin.

For each hook, document:

```markdown
### `{plugin_slug}_hook_name`

**Type:** action | filter
**Location:** `src/Path/To/File.php`
**Since:** 1.0.0

Description of what the hook does and when it fires.

**Parameters:**
- `$param1` (string) — Description.
- `$param2` (array) — Description.

**Example:**
\`\`\`php
add_action( '{plugin_slug}_hook_name', function( $param1, $param2 ) {
    // Integration code.
}, 10, 2 );
\`\`\`
```

### Additional documentation files

Create additional files in `docs/` as the plugin grows:

- `docs/hooks.md` — All actions and filters (required)
- `docs/rest-api.md` — REST API endpoints, if any
- `docs/shortcodes.md` — Shortcodes and their attributes, if any
- `docs/settings.md` — Plugin settings and options schema

Keep documentation focused, code-heavy, and aimed at developers who need to integrate. Avoid duplicating what is already in the code comments.

## Design & Admin UI

### Use the frontend-design skill

When implementing UI for this plugin — whether admin pages or frontend-facing components — use the **`frontend-design` Claude skill** (`/frontend-design`). This skill produces polished, production-grade interfaces that avoid generic AI aesthetics. Invoke it for:

- Admin settings pages and dashboards
- Frontend output (shortcodes, blocks, widgets)
- Any visible UI component the plugin renders

### Prefer React for admin pages

When building admin pages, **prefer modern React-based UI** over traditional PHP-rendered forms. This approach provides:

- A modern, responsive user experience
- Consistent look and feel with the WordPress block editor
- Access to the `@wordpress/components` library (buttons, panels, toggles, etc.)
- Client-side validation and dynamic interactions

Implementation pattern:
1. Create a minimal PHP page that outputs a mount point: `<div id="plugin-slug-admin"></div>`
2. Build the admin UI as a React app in `src/js/admin/`
3. Use `@wordpress/api-fetch` for settings CRUD via the REST API
4. Use `@wordpress/components` for UI components that match WordPress admin styling
5. Register a REST API route to read/write plugin settings
6. Enqueue the compiled admin script only on the plugin's admin page

The template ships with a basic Settings API page as a starting point. When the plugin needs a richer admin experience, migrate to this React approach.

## Testing

### Philosophy: lightweight guardrails, not comprehensive coverage

Tests exist as a **fast sanity check** — a 60ms safety net that verifies classes are wired up correctly after refactoring or adding features. They are NOT meant to comprehensively test every code path or to validate WordPress behavior.

**Keep tests minimal and focused.** Do not:
- Create PHP interfaces just to "improve testability" — concrete classes are fine, BrainMonkey mocks at the function level without needing dependency injection or interfaces
- Write tests for trivial methods (getters, simple wrappers around a single WP function)
- Let the test suite grow into indirectly testing WordPress behavior
- Aim for high code coverage percentages — test what matters, skip what doesn't

The right question before writing a test: "Would this test catch a real mistake during development?" If the answer is no, skip it.

### Approach: Unit tests with BrainMonkey

This plugin uses [BrainMonkey](https://giuseppe-mazzapica.gitbook.io/brain-monkey) + [Mockery](https://github.com/mockery/mockery) for unit testing.

- **Assume WordPress functions work correctly** — do not integration-test WordPress itself.
- **Test only plugin methods** — verify that your classes call the right WP functions with the right arguments.
- **Mock WP function outputs** — use BrainMonkey to stub/mock any WordPress function your code calls.
- **Keep tests fast** — no WordPress installation, no database, no HTTP. Tests run in milliseconds.

### How it works

BrainMonkey automatically stubs the WordPress hook API (`add_action`, `add_filter`, `do_action`, `apply_filters`, etc.) so you can test hook registration without loading WordPress.

For other WP functions, stub them explicitly:

```php
use Brain\Monkey\Functions;

// Bulk stubs — return fixed values.
Functions\stubs( array(
    'get_option'       => array(),
    'current_user_can' => true,
    'plugin_dir_path'  => '/path/to/plugin/',
) );

// Stub translation and escaping functions (return first argument).
Functions\stubTranslationFunctions();
Functions\stubEscapeFunctions();

// Expectations — assert a WP function is called correctly.
Functions\expect( 'register_setting' )
    ->once()
    ->with( 'settings_group', 'option_name', \Mockery::type( 'array' ) );
```

### Test file structure

```
tests/
  TestCase.php     → Base class (extends PHPUnit, sets up BrainMonkey)
  SampleTest.php   → Example tests demonstrating patterns
  Admin/           → Tests for src/Admin/ classes
  Core/            → Tests for src/Core/ classes
  Frontend/        → Tests for src/Frontend/ classes
```

All test classes MUST extend `WPPluginTemplate\Tests\TestCase` (not PHPUnit's TestCase directly).

### Running tests

```bash
composer test
```

### What to test

- **Hook registration** — verify that constructors call `add_action`/`add_filter` with the right hooks and callbacks.
- **Data processing** — verify sanitization, validation, and transformation logic with actual branching or edge cases.
- **Conditional logic** — verify non-trivial branching paths (capability checks, option-dependent behavior).

### What NOT to test

- WordPress core behavior (database queries, hook execution order, HTTP API)
- Third-party plugin compatibility
- Whether `wp_enqueue_style` actually loads a CSS file
- Simple pass-through methods that just call one WP function
- Constructor wiring that is obvious from reading the code

## Coding Standards

- **PHP**: WordPress Coding Standards (WordPress-Extra ruleset). Run `composer phpcs` to check.
- **JavaScript**: WordPress ESLint rules via `@wordpress/scripts`. Run `npm run lint:js`.
- **CSS**: WordPress Stylelint rules via `@wordpress/scripts`. Run `npm run lint:css`.
- **Indentation**: Tabs (not spaces) for PHP, JS, and CSS.
- **PHP naming**: `snake_case` for functions and variables, `PascalCase` for class names.
- **Filenames**: PascalCase for PHP class files (PSR-4), kebab-case for everything else.
- **Text domain**: Always use the plugin slug in `__()`, `_e()`, `esc_html__()`, etc.
- **Escaping**: Always escape output — `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`.
- **Nonces**: Use nonces for form submissions and AJAX requests.
- **Capabilities**: Check `current_user_can()` before privileged operations.

## Plugin Check (PCP) Compliance

This plugin targets compatibility with [Plugin Check (PCP)](https://wordpress.org/plugins/plugin-check/), the official WordPress.org plugin validation tool. A GitHub Actions workflow runs PCP automatically on every push and PR.

Follow these rules to pass PCP checks. Violations caught late are expensive to fix — follow them from the start:

### Escaping (late escaping)

All output MUST be escaped **immediately** before rendering. Never echo a variable without escaping, even if it was sanitized earlier:

```php
// WRONG — escaped too early, or not at all.
$title = esc_html( $raw_title );
echo "<h2>$title</h2>";

// CORRECT — escaped at the point of output.
echo '<h2>' . esc_html( $raw_title ) . '</h2>';
```

Use `esc_html_e()` / `esc_html__()` / `esc_attr_e()` / `esc_attr__()` for translated strings instead of `_e()` / `__()` followed by manual escaping.

### Internationalization (i18n)

- Every user-facing string MUST use a translation function with the plugin text domain.
- Add translator comments for strings with placeholders:

```php
/* translators: %s: user display name */
printf( esc_html__( 'Hello, %s!', 'wp-plugin-template' ), esc_html( $user->display_name ) );
```

### Script loading strategy

Always enqueue scripts with a loading strategy (`defer` or `async`) and in the footer. Do NOT pass just `true` as the last argument to `wp_enqueue_script()`:

```php
wp_enqueue_script(
    'handle',
    $url,
    array(),
    $version,
    array(
        'in_footer' => true,
        'strategy'  => 'defer',
    )
);
```

### Database queries

- Use WordPress API functions (`get_option`, `get_posts`, `WP_Query`) instead of direct `$wpdb` queries whenever possible.
- When direct queries are necessary, ALWAYS use `$wpdb->prepare()` for parameterized queries.

### Sanitization

- Sanitize ALL input from `$_POST`, `$_GET`, `$_REQUEST`, and `$_SERVER` with appropriate functions (`sanitize_text_field()`, `absint()`, `sanitize_email()`, etc.).
- Use `register_setting()` with a `sanitize_callback` — never save raw option values.

### Prefixing

- All global functions, constants, and variables MUST use the plugin prefix.
- PSR-4 namespaced classes satisfy this requirement automatically.
- Be careful with standalone helper functions outside namespaces — always prefix them.

### Files and headers

- The `readme.txt` must have valid headers: `Stable tag`, `Tested up to`, `Requires at least`, `Requires PHP`, `License`.
- `Stable tag` in `readme.txt` MUST match `Version` in the plugin header.
- Never ship `.DS_Store`, `.git/`, `.claude/`, `.env`, or other dev/system files — `build-zip.sh` handles this.

## Build Commands

| Command | Purpose |
|---|---|
| `composer install` | Install PHP dependencies + autoloader |
| `npm install` | Install JS dependencies |
| `npm run build` | Compile blocks for production |
| `npm run start` | Watch mode — rebuild on changes |
| `composer phpcs` | Check PHP coding standards |
| `composer phpcbf` | Auto-fix PHP coding standard issues |
| `npm run lint:js` | Lint JavaScript files |
| `npm run lint:css` | Lint CSS files |
| `composer test` | Run PHPUnit tests (BrainMonkey) |
| `./build-zip.sh` | Build production .zip in `dist/` |
| `./build-zip.sh --clean` | Remove the `dist/` directory |

## Releasing

### Building the production zip

Run `./build-zip.sh` to create a distributable `.zip` file in `dist/`. The script:

1. Installs production-only PHP dependencies (no dev packages)
2. Compiles JavaScript/CSS assets (`npm run build`)
3. Copies only production files into the zip
4. Restores dev dependencies afterward

The zip contains **only what WordPress needs to run the plugin** — no tests, no source JS, no config files, no dev tooling.

**What goes into the zip:**

| Included | Excluded |
|---|---|
| `{slug}.php`, `uninstall.php`, `index.php` | `composer.json`, `package.json`, `*.lock` |
| `src/` (PHP classes only) | `src/js/` (uncompiled React source) |
| `build/` (compiled blocks) | `tests/`, `docs/` |
| `assets/` (CSS, JS, images) | `node_modules/`, `.git/` |
| `views/`, `languages/` | `phpcs.xml.dist`, `phpunit.xml.dist` |
| `vendor/` (autoloader, no dev) | `CLAUDE.md`, `.claude/`, `build-zip.sh` |
| `LICENSE`, `README.md`, `readme.txt` | `.gitignore`, `.env` |

### Publishing a GitHub release

After building the zip, create a GitHub release:

```bash
# 1. Make sure the version is updated and committed.
# 2. Build the zip.
./build-zip.sh

# 3. Create a git tag.
git tag v1.0.0

# 4. Push the tag.
git push origin v1.0.0

# 5. Create the GitHub release with the zip attached.
gh release create v1.0.0 dist/{plugin-slug}-1.0.0.zip \
    --title "v1.0.0" \
    --notes "Release notes here."
```

### Release checklist

Before releasing, verify:

1. Version is updated in the plugin header, `*_VERSION` constant, AND `readme.txt` `Stable tag` (see [Versioning](#versioning))
2. `readme.txt` has a changelog entry for the new version
3. `composer phpcs` passes with no errors
4. `composer test` passes
5. `npm run build` completes without errors
6. The plugin works correctly on a test WordPress installation
7. `docs/hooks.md` is up to date with any new or changed hooks
