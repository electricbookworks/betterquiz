var
	gulp = require('gulp'),
	concat = require('gulp-concat'),
	coffee = require('gulp-coffee'),
	uglify =require('gulp-uglify'),
	rename = require('gulp-rename'),
	watch = require('gulp-watch'),
	notify = require('gulp-notify'),
	hoganCompiler = require('gulp-hogan-compile'),
	gutil = require('gulp-util'),
	gulpIf = require('gulp-if'),
	wrapper = require('gulp-wrapper'),
	merge = require('gulp-merge');

gulp.task('coffee', function() {
	return gulp.src('src/coffee/**/*coffee')
	  	.pipe(
	      gulpIf(/[.]litcoffee$/, 
	        coffee({bare:true, literate:true}), 
	        coffee({bare:true, literate:false})
	      )
	    )
		.pipe(concat('_coffee.js'))
		.pipe(gulp.dest('public/js'))
		.on('error', notify.onError({message:'build'}));			;		
});

gulp.task('merge', ['coffee'], function() {
	return gulp.src(['public/js/_*.js'])
		.pipe(concat('admin.js'))
		.pipe(wrapper({
			header: "(function() {\n",
			footer: "\n})();"
		}))
		.pipe(gulp.dest('public/js'))
		.pipe(rename({suffix:".min"}))
		.pipe(uglify())
		.pipe(gulp.dest('public/js'));
});

gulp.task('mustache', function() {
  return merge(
	  gulp.src('src/mustache/**/*.html')
	        .pipe(hoganCompiler('_mustache.js', {wrapper:false}))
	  ,
	  gulp.src('src/mustache/MTemplate.coffee')
	  	.pipe(coffee({bare:true, literate:false}))
	  )
  	  .pipe(concat('_mustache.js'))
  	  .pipe(wrapper({
  	  	header: "(function() {\n",
  	  	footer: "\n})();"
  	  }))
      .pipe(gulp.dest('public/js'));	
});

gulp.task('watch', function() {
	gulp.watch('src/coffee/**/*coffee', ['merge']);
	// gulp.watch(['src/mustache/**/*.html','src/mustache/MTemplate.coffee'], ['merge']);
});