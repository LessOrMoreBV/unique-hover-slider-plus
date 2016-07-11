// Including plugins
var gulp = require('gulp'),
    minifyCss = require("gulp-minify-css"),
    sass = require("gulp-sass"),
    compass = require("gulp-compass"),
    rename = require('gulp-rename'),
    watch = require("gulp-watch"),
    concat = require("gulp-concat"),
    uglify = require("gulp-uglify"),
    notify = require("gulp-notify"),
    plumber = require("gulp-plumber");

function errorAlert(error){
	notify.onError({
        title: "Oops",
        message: "<%= error.message %>",
        sound: "Sosumi"
    })(error); // Error Notification
	console.log(error.toString()); // Prints Error to Console
	this.emit("end"); // End function
};

// Compile SCSS to CSS and minify.
gulp.task('compass', function() {
    gulp.src('./source/sass/stylesheet.scss')
    .pipe(plumber({errorHandler: errorAlert}))
    .pipe(compass({
        css: 'assets/css',
        sass: 'source/sass',
        image: 'assets/images'
    }))
    .pipe(rename('stylesheet.min.css'))
    .pipe(minifyCss())
    .pipe(gulp.dest('./assets/css/'))
    .pipe(notify({ title: 'SCSS', message: 'Compiled and minified' }));
});

// Compile JS and minify.
gulp.task('scripts', function() {
    return gulp.src([
        './source/js/app.js'
    ])
    .pipe(plumber({errorHandler: errorAlert}))
    .pipe(concat('script.js'))
    .pipe(gulp.dest('./assets/js/'))
    .pipe(rename('script.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./assets/js/'))
    .pipe(notify({ title: 'Javascript [app]', message: 'Compiled and minified' }));
});

// Compile vendor JS and minify.
gulp.task('vendor', function() {
    return gulp.src([
        './source/js/vendor/ResizeSensor.js',
        './source/js/vendor/ElementQueries.js'
    ])
    .pipe(plumber({errorHandler: errorAlert}))
    .pipe(concat('vendor.js'))
    .pipe(gulp.dest('./assets/js/'))
    .pipe(rename('vendor.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./assets/js/'))
    .pipe(notify({ title: 'Javascript [vendor]', message: 'Compiled and minified' }));
});

// Gulp watch, keep watching while programming.
gulp.task('watch', function() {
    gulp.watch('source/sass/**/*.scss', ['compass']);
    gulp.watch('source/js/**/*.js', ['scripts']);
});

gulp.task('default', function() {});
