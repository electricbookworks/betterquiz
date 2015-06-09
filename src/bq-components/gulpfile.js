
var gutil = require("gutil");
var run = require("gulp-run");
var gulpIf = require("gulp-if");
var notify = require("gulp-notify");
var concat = require("gulp-concat");
var coffee = require("gulp-coffee");
var gulp = require("gulp");
var sass = require("gulp-sass");
var uglify = require("gulp-uglify");
var rename = require("gulp-rename");
var wrap = require("gulp-wrap");;

gulp.task('default', ['sass','coffee','package'], function() {

});

gulp.task('coffee', function() {
  return gulp.src('*coffee')
        .pipe(
	      gulpIf(/[.]litcoffee$/,
	        coffee({bare:false, literate:true}),
	        coffee({bare:false, literate:false})
	      )
	    ).on("error", notify.onError(function (error) {
        	return "task 'coffee': " + error.message + " - " + error;
      	}))
        .pipe(gulp.dest(".build"))
        .pipe(gulp.dest("../../public/components/"));
});

gulp.task('js', ['coffee'], function() {
  return gulp.src(".build/*.js")
  	.pipe(concat("bq-components.js"))
  	.pipe(wrap('(function() {<%= contents %>\n})();'))
  	.pipe(gulp.dest("../../public/components/"))
  	.pipe(rename({suffix:".min"}))
  	.pipe(uglify())
  	.pipe(gulp.dest("../../public/components/"));
});

gulp.task('sass', function() {
	return gulp.src('*.scss')
		.pipe(sass({ style: 'expanded' }))
		.pipe(gulp.dest(".build"))
		.on("error", notify.onError(function (error) {
        	return "task 'sass': " + error.message;
      	}));		
});

gulp.task('package', ['sass','js'], function() {
	run('webcomponent build').exec();
});

gulp.task('watch', function() {
  gulp.watch('*.scss', ['package']);
  gulp.watch('*coffee', ['package']);
  gulp.watch('*.html', ['package']);

  gulp.watch('*.yml', ['default']);
});

