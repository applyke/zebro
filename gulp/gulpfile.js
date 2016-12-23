// Include gulp
var gulp = require('gulp');
var fs = require('fs');

fs.readdirSync('./tasks').forEach(function (task) {
    require('./tasks/' + task);
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('assets/js/**/*.js', ['lint', 'js']);
    gulp.watch('assets/scss/**/*.scss', ['sass']);
});

// Default Task
gulp.task('default', ['lint', 'js' ,'sass']);