"use strict";

const { src, dest, series, watch } = require("gulp");
const babelify = require("babelify");
const browserify = require("browserify");
const browserSync = require("browser-sync").create();
const gulpAutoprefixer = require("gulp-autoprefixer");
const gulpRename = require("gulp-rename");
const gulpSass = require("gulp-sass");
const minifyStream = require("minify-stream"); // Minify uses Terser (uglify) for JS: https://github.com/terser/terser
const vinylSourceStream = require("vinyl-source-stream");

/**
 * Source path mapping.
 */
const paths = {
  scripts: {
    src: ["./assets/src/js/theme.js"],
    dest: "assets/dist/js/",
  },
  styles: {
    src: ["./assets/src/scss/**/*.scss"],
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
  browserify({
    extensions: [".jsx", ".js"],
    debug: true,
    cache: {},
    packageCache: {},
    fullPaths: true,
    entries: paths.scripts.src,
  })
    .transform(
      babelify.configure({
        presets: ["babel-preset-env"],
        ignore: ["node_modules"],
      })
    )
    .bundle()
    .on("error", function (err) {
      console.log("Error : " + err.message);
    })
    .pipe(
      minifyStream({
        ecma: "es2015",
        mangle: true,
        output: {
          comments: "some",
        },
      })
    )
    .pipe(vinylSourceStream("theme.min.js"))
    .pipe(dest("./assets/dist/js"));

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
    .pipe(gulpAutoprefixer("last 1 version", "> 1%"))
    .pipe(gulpRename({ suffix: ".min" }))
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

/**
 * Exports.
 */
exports.build = build;
exports.default = build;
exports.js = js;
exports.sass = sass;
exports.serve = serve;
exports.watch = watcher;
