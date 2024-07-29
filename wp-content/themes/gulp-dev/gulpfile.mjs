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
        src: 'src/js/**/*.js',
        dest: '../motaphoto/assets/js'
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

// Minify and concatenate JS
function scripts() {
    return gulp.src(paths.scripts.src)
        .pipe(plumber({ errorHandler: notify.onError("Error: <%= error.message %>") }))
        .pipe(sourcemaps.init())
        .pipe(concat('main.js'))
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(rename({ extname: '.js' }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.scripts.dest))
        .pipe(browserSync.stream());
}

// Watch files for changes
function watch() {
    browserSync.init({
        proxy: "http://localhost:10053",
        notify: false
    });
    gulp.watch(paths.styles.srcToWatch, styles);
    gulp.watch(paths.scripts.src, scripts);
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