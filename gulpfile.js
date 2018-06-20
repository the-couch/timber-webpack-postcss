var gulp = require('gulp')
var livereload = require('gulp-livereload')

gulp.task('watch', function () {
  livereload.listen()

  gulp.watch('sass/**/*.scss', function () {
    gulp.run('sass')
  })

  gulp.watch([
    './assets/index.js',
    './assets/main.css',
    './templates/*.twig',
    './templates/**/*.twig'
  ], function (event) {
    livereload.changed(event)
  })
})

gulp.task('default', ['watch'])
