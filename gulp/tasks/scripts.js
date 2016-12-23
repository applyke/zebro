var gulp = require('gulp');
var sass  = require('gulp-sass');
var jshint = require('gulp-jshint');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');

// Lint Task
gulp.task('lint', function() {
    return gulp.src('assets/js/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'));
});

// Concatenate & Minify JS
gulp.task('js', function() {
    return gulp.src([
        'assets/js/jquery-3.1.1.js',
        'node_modules/material-design-lite/material.js',
        'node_modules/mdl-selectfield/src/selectfield/selectfield.js',
        'assets/js/layout.js'
    ]).pipe(sourcemaps.init({loadMaps: true}))
        .pipe(concat('all.js'))
        .pipe(rename('all.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('./', {
            sourceMappingURL: function(file) {
                return '/js/' + file.relative + '.map';
            }
        }))
        .pipe(gulp.dest('../public/js/'));
});
