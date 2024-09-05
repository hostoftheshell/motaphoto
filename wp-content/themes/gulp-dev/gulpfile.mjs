import gulp from 'gulp';
import * as dartSass from 'sass';
import gulpSass from 'gulp-sass';
import autoprefixer from 'gulp-autoprefixer';
import concat from 'gulp-concat';
import uglify from 'gulp-uglify';
import cleanCSS from 'gulp-clean-css';
import sourcemaps from 'gulp-sourcemaps';
import browserSync from 'browser-sync';
import rename from 'gulp-rename';
import plumber from 'gulp-plumber';
import notify from 'gulp-notify';

const sass = gulpSass(dartSass);

// Paths
const paths = {
    styles: {
        src: 'src/scss/main.scss',
        srcToWatch: 'src/scss/**/*.scss',
        dest: '../motaphoto/assets/css'
    },
    scripts: {
        all: 'src/js/**/*.js',
        main: 'src/js/main/**/*.js',
        jquery: 'src/js/jquery/**/*.js',
        frontPage: 'src/js/front-page/**/*.js',
        singlePage: 'src/js/single-photography/**/*.js',
        dest: '../motaphoto/assets/js',
        mainDest: '../motaphoto/assets/js/main',
        jqueryDest: '../motaphoto/assets/js/jquery',
        frontPageDest: '../motaphoto/assets/js/front-page',
        singlePageDest: '../motaphoto/assets/js/single-photography'
    },
    php: {
        src: '**/*.php'
    }
};

// Compile SCSS into CSS
function styles() {
    return gulp.src(paths.styles.src)
        .pipe(plumber({ errorHandler: notify.onError("Error: <%= error.message %>") }))
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(autoprefixer())
        .pipe(cleanCSS())
        .pipe(rename({ suffix: '.min' }))
        .pipe(rename({ basename: 'theme', extname: '.css' }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.styles.dest))
        .pipe(browserSync.stream());
}

// Minify and concatenate main JS
function mainScripts() {
    return gulp.src(paths.scripts.main)
        .pipe(plumber({ errorHandler: notify.onError("Error: <%= error.message %>") }))
        .pipe(sourcemaps.init())
        .pipe(concat('main.js'))
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(paths.scripts.mainDest))
        .pipe(browserSync.stream());
}

// Minify and concatenate jQuery scripts
function jqueryScripts() {
    return gulp.src(paths.scripts.jquery)
        .pipe(plumber({ errorHandler: notify.onError("Error: <%= error.message %>") }))
        .pipe(sourcemaps.init())
        .pipe(concat('jquery.js'))
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(paths.scripts.jqueryDest))
        .pipe(browserSync.stream());
}

// Minify and concatenate front-page scripts
function frontPageScripts() {
    return gulp.src(paths.scripts.frontPage)
        .pipe(plumber({ errorHandler: notify.onError("Error: <%= error.message %>") }))
        .pipe(sourcemaps.init())
        .pipe(concat('front-page.js'))
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(paths.scripts.frontPageDest))
        .pipe(browserSync.stream());
}

// Minify and concatenate single-page scripts
function singlePageScripts() {
    return gulp.src(paths.scripts.singlePage)
        .pipe(plumber({ errorHandler: notify.onError("Error: <%= error.message %>") }))
        .pipe(sourcemaps.init())
        .pipe(concat('single-photography.js'))
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(paths.scripts.singlePageDest))
        .pipe(browserSync.stream());
}

// Combine all JavaScript tasks
const scripts = gulp.series(
    gulp.parallel(mainScripts, jqueryScripts, frontPageScripts, singlePageScripts)
);

// Watch files for changes
function watch() {
    browserSync.init({
        proxy: "http://localhost:10053",
        notify: false
    });
    gulp.watch(paths.styles.srcToWatch, styles);
    gulp.watch(paths.scripts.all, scripts);
    gulp.watch(paths.php.src).on('change', browserSync.reload);
}

// Define complex tasks
const build = gulp.series(gulp.parallel(styles, scripts));
const dev = gulp.series(build, watch);

// Export tasks
export {
    styles,
    scripts,
    watch,
    build,
    dev
};

// Default task
export default dev;
