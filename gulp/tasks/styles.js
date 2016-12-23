var gulp = require('gulp');
var sass = require('gulp-sass');
var cssnano = require('gulp-cssnano');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');
var rename = require('gulp-rename');

// Compile Sass
gulp.task('sass', function() {
    return gulp.src('assets/scss/app.scss')
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(sass())
        .pipe(autoprefixer("last 2 version", "> 5%", "ie 8", "ie 7"))
        .pipe(rename('all.min.css'))
        .pipe(cssnano())
        .pipe(sourcemaps.write('./', {
            sourceMappingURL: function(file) {
                return '/css/' + file.relative + '.map';
            }
        }))
        .pipe(gulp.dest('../public/css/'));
});

