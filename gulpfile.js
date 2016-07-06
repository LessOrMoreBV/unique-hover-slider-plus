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
    gulp.src('./sass/stylesheet.scss')
    .pipe(plumber({errorHandler: errorAlert}))
    .pipe(compass({
        css: 'assets',
        sass: 'sass',
        image: 'assets/images'
    }))
    .pipe(rename('stylesheet.min.css'))
    .pipe(minifyCss())
    .pipe(gulp.dest('./assets/'))
    .pipe(notify({ title: 'SCSS', message: 'Compiled and minified' }));
});

// Compile JS and minify.
gulp.task('scripts', function() {
    return gulp.src([
        './js/app.js',
        './js/helpers.js',
        './js/vendor/contact.js',
        './js/vendor/maps.js',
        './js/vendor/jquery.home-slider.js',
        './js/vendor/slick.min.js',
        './js/vendor/jquery.project-slider.js',
        './js/vendor/jquery.vertical-slider.js',
        './js/vendor/jquery.services.js'
    ])
    .pipe(plumber({errorHandler: errorAlert}))
    .pipe(concat('script.js'))
    .pipe(gulp.dest('./assets/'))
    .pipe(rename('script.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('./assets/'))
    .pipe(notify({ title: 'Javascript', message: 'Compiled and minified' }));
});

// Gulp watch, keep watching while programming.
gulp.task('watch', function() {
    gulp.watch('sass/**/*.scss', ['compass']);
    gulp.watch('js/**/*.js', ['scripts']);
});

gulp.task('default', function() {});
