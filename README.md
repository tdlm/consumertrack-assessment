# ConsumerTrack Assessment

## Micronaut

Micronaut is a micro theme running on Dockerized WordPress.

---

**Table of Contents**

- [Dependencies](#dependencies) - Please verify you are ready to run Micronaut before proceeding.
- [Installation](#installation) - Quick setup and installation instructions!
- [Development](#development) - How to develop on the theme.
  - [BrowserSync](#browsersync) - How to use BrowserSync.
  - [JavaScript](#javascript) - How to compile your JavaScript.
  - [SASS CSS](#sass-css) - How to compile your SASS/SCSS.
  - [Watcher](#watcher) - How to use the watcher.
- [Available Bin Commands](#available-bin-commands) - Commands available to manage Docker/WordPress.
- [TODOS](#todos) - Action items to get this setup/theme to v0.0.1.

---

## Dependencies

- In order to run this, you must first install and configure [Docker](https://www.docker.com/get-started).

- `docker-compose` v1.25.5+

- [npm](https://www.npmjs.com/get-npm) v6.14.5+

> NOTE: This runs on port `80`. Please disable any other servers listening on this port before attempting to run the installation!

## Installation

1. Clone the repository

   ```bash
   git clone git@github.com:tdlm/consumertrack-assessment.git
   ```

1. CD into the directory

   ```bash
   cd consumertrack-assessment
   ```

1. Run the setup script and grab a cup of coffee ☕. This might take a while.

   ```bash
   ./bin/setup.sh
   ```

1. 🎉 Success 🎉

   If all went well, then when the script is finished running, visit the site on http://localhost. You should see a list of posts and then a list of movies!

## Development

The micronaut theme included with this repo comes with a series of Gulp commands that will help to facilitate easy dev!

To get started, CD into the theme directory and fire up one of the available commands:

- `npx gulp` (alias for build)
- `npx gulp build` (Runs JS, SASS commands in sequence)
- `npx gulp js` (Runs theme.js through Babel, then Terser, then renames to theme.min.js)
- `npx gulp sass` (Runs theme.scss through gulp-sass w/ compression, autoprefixer, then renames to theme.min.css)
- `npx gulp serve` (Runs BrowserSync)
- `npx gulp watch` (Runs watcher to recompile JS/SCSS files on save)

### BrowserSync

Browser sync enables you to reload the browser when relevant files change. Once you've fired up the main WordPress Docker instance using the `Installation` instructions above, you can run the `npx gulp serve` command (or just `gulp serve` if you have Gulp installed globally).

```bash
$ cd content/themes/micronaut
$ npx gulp serve
[01:53:03] Starting 'serve'...
[01:53:03] Finished 'serve' after 27 ms
[Browsersync] Proxying: http://localhost
[Browsersync] Access URLs:
 ------------------------------------
       Local: http://localhost:3000
    External: http://192.168.1.5:3000
 ------------------------------------
          UI: http://localhost:3001
 UI External: http://localhost:3001
 ------------------------------------
```

Once this is running, any changes to the theme files (PHP, JS, SCSS) under `content/themes/micronaut` will result in a fresh compile and browser refresh.

### Watcher

If you don't feel like using `BrowserSync`, you're free to let Gulp watch your files for changes and you can refresh the browser whenever you see fit.

Any time the watcher sees changes to either JavaSript or SASS (scss) files under the `content/themes/micronaut/assets/src/` folder, it will trigger a recompile, but will not trigger a reload browser-side. If you want to run this, run `npx gulp watch` (or just `gulp watch` if you have Gulp installed globally).

```bash
$ cd content/themes/micronaut
$ npx gulp watch
[02:00:54] Starting 'watch'...
[02:00:54] Finished 'watch' after 18 ms
[02:01:08] Starting 'js'...
[02:01:08] Finished 'js' after 13 ms
[02:01:08] Starting 'sass'...
[02:01:08] Finished 'sass' after 3.55 ms
[02:01:13] Starting 'js'...
[02:01:13] Finished 'js' after 10 ms
[02:01:13] Starting 'sass'...
[02:01:13] Finished 'sass' after 3.09 ms
```

### JavaScript

The theme's JavaScript files live under `content/themes/micronaut/assets/src/js/`, and if you want to manually compile changes to them, just cd into the theme directory and run the `npx gulp js` command (or just `gulp js` if you have Gulp installed globally).

```bash
$ cd content/themes/micronaut
$ npx gulp js
[01:55:33] Starting 'js'...
[01:55:33] Finished 'js' after 8.84 ms
```

### SASS CSS

> NOTE: This theme extends Bootstrap.css

The theme's SASS files live under `content/themes/micronaut/assets/src/scss/`, and if you want to manually compile changes to them, just cd into the theme directory and run the `npx gulp sass` command (or just `gulp sass` if you have Gulp installed globally).

```bash
$ cd content/themes/micronaut
$ npx gulp sass
[01:58:28] Starting 'sass'...
[01:58:28] Finished 'sass' after 8.61 ms
```

## Available Bin Commands

- `./bin/bash.sh` (Gives you a prompt in the php-fpm container running WordPress)
- `./bin/destroy.sh` (Destroys Docker VM and non-repository files/directories)
- `./bin/restart.sh` (Restarts Docker)
- `./bin/setup.sh` (Full installation from ground up)
- `./bin/start.sh` (Starts Docker)
- `./bin/stop.sh` (Stops Docker)
- `./bin/test.sh` (Runs PHPCS against plugin/theme files)

## TODOS:

- [x] Create an empty public repository
- [x] Create initial commit of blank WordPress install
- [x] Install ACF/CPT UI Plugins
- [x] Create micro WordPress Theme
- [x] Create "Movie" custom post type
- [x] Create "Genre" custom taxonomy
- [x] Add field called "Year"
- [x] Add field called "Director"
- [x] Add index.php section to use main loop to display posts
- [x] Add secondary query using WP_Query to display Movies
- [x] Add Bootstrap 4
- [x] Make posts and movies display on a Bootstrap Grid
- [x] Bonus: Install Gulp 4 in theme
- [x] Bonus: Enable SASS compilation with CSS minification
- [x] Bonus: Enable ES6 compilation with Babel and JS Uglify (Terser) on theme
- [x] Bonus: Create task on automation layer to implement BrowserSync
