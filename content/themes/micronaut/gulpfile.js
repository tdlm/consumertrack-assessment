"use strict";

const { src, dest, series, watch } = require("gulp");
const autoprefixer = require("gulp-autoprefixer");
const babel = require("gulp-babel");
const browserSync = require("browser-sync").create();
const gulpSass = require("gulp-sass");
const rename = require("gulp-rename");
const terser = require("gulp-terser");

const paths = {
  scripts: {
    src: ["./assets/src/js/*.js"],
    dest: "assets/dist/js/",
  },
  styles: {
    src: ["./assets/src/scss/*.scss"],
    dest: "assets/dist/css/",
  },
  php: {
    src: ["./**/*.php"],
  },
};

/**
 * Process JavaScript files.
 * @param {function} cb Callback function.
 */
function js(cb) {
  src(paths.scripts.src, { sourcemaps: false })
    .pipe(
      babel({
        presets: ["@babel/preset-env"],
      })
    )
    .pipe(terser())
    .pipe(rename({ suffix: ".min" }))
    .pipe(dest(paths.scripts.dest));

  cb();
}

/**
 * Process SASS files.
 * @param {function} cb Callback function.
 */
function sass(cb) {
  src(paths.styles.src)
    .pipe(
      gulpSass({ outputStyle: "compressed" }).on("error", gulpSass.logError)
    )
    .pipe(autoprefixer("last 1 version", "> 1%"))
    .pipe(rename({ suffix: ".min" }))
    .pipe(dest(paths.styles.dest));
  cb();
}

/**
 * Primary watcher file.
 * @param {function} cb Callback function.
 */
function watcher(cb) {
  watch([...paths.scripts.src, ...paths.styles.src], series(js, sass));
  cb();
}

/**
 * BrowserSync serve.
 * @param {function} cb Callback function.
 */
function serve(cb) {
  browserSync.init({
    proxy: "localhost",
    notify: false,
  });

  watch([...paths.scripts.src, ...paths.styles.src], series(js, sass));
  watch([...paths.scripts.src, ...paths.styles.src, ...paths.php.src]).on(
    "change",
    browserSync.reload
  );

  cb();
}

/**
 * Build files.
 * @param {function} cb Callback function.
 */
function build(cb) {
  js(cb);
  sass(cb);
  cb();
}

exports.build = build;
exports.default = build;
exports.js = js;
exports.sass = sass;
exports.serve = serve;
exports.watch = watcher;
