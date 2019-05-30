var gulp         = require("gulp"),
    sass         = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    concat       = require('gulp-concat'),
    uglify       = require('gulp-uglify'),
    fsCache      = require('gulp-fs-cache'),
    rename       = require("gulp-rename"),
    browserSync  = require('browser-sync'),
    wpPot        = require('gulp-wp-pot'),
    dos2unix     = require('gulp-dos2unix-js'),
    zip          = require('gulp-zip'),
    del          = require('del');

// Get project name from json file.
var jsonData = require('./package.json');

// Project variables.
var $plugin_name    = jsonData.name,
    $plugin_version = jsonData.version,
    $packDest       = '/home/alen/Documents/'+ $plugin_name + '/',
    $packTemp       = $packDest + $plugin_name;


// Configure browsersync.
gulp.task('browser-sync', function() {
    var files = [
        './assets/css/scss/micemade-elements.scss',
        './**/*.php'
    ];
    // Initialize BrowserSync with a PHP server
    browserSync.init(files, {
        proxy: 'http://clothy.loc/'
    });
    
    gulp.watch(
        [
            'assets/css/scss/**/*.scss',
        ], gulp.series('styles')
    );

});

// Process main css file(s) - style.scss and other scss files
gulp.task(
    'styles', function () {
    return gulp.src('assets/css/scss/**/*.scss')
        .pipe(
            sass({
                'outputStyle' : 'compressed'
            })
            .on('error', sass.logError)
        )
        .pipe(autoprefixer({
            browsers: ['> 1%', 'last 3 versions', 'Firefox >= 20', 'iOS >=7'],
            cascade: false
        }))
        .pipe(gulp.dest('./assets/css'))
        .pipe(browserSync.stream()) //Possible use in future uncomment if neccessary

});

// Process JS files (concatenate and uglify)
gulp.task('scripts', function() {
    var jsFsCache = fsCache('.tmp/jscache'); // save cache to .tmp/jscache
    return gulp.src('assets/js/lib/**/*.js')
      .pipe(concat('micemade-elements.js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(jsFsCache)
        .pipe(uglify())
        .pipe(jsFsCache.restore)
        .pipe(gulp.dest('./assets/js'))

        .pipe(browserSync.stream());
})

// Generate translation .pot file from all php files
gulp.task('makepot', function () {
    return gulp.src('**/*.php')
        .pipe(wpPot( {
            domain: $plugin_name,
            package: $plugin_name,
            team: 'Micemade <alen@micemade.com>'
        } ))
        .pipe(gulp.dest('./languages/'+ $plugin_name +'.pot'));
});

// dos2unix.
gulp.task('eol', function () {
    return gulp.src(
    [
        //$packTemp + '/**/*.{css,js,php}',
        $packTemp + '/**/**',
    ])
    .pipe( dos2unix() ) // This defaults to {feedback: false, write: false}
    .pipe( gulp.dest( $packTemp ) )
  });
// end dos2unix

// Copy all files to destination
gulp.task('copy', function() {
    return gulp.src([
        './**',
        '!./node_modules', 
        '!./node_modules/**',
        '!./gulpfile.js',
        '!./package.json',
        '!./package-lock.json',
        '!./js/tmp', 
        '!./js/tmp/**',
        '!./**/*.db' // remove Windows Thumbs.db files
    ])
        .pipe(gulp.dest( $packTemp ));
})

// #######################################
// The DEFAULT task will process sass,
// run browser-sync and start watchin for changes
//
gulp.task( 'default', gulp.series( 'browser-sync' ) );
//
// #######################################


// Process JS SCRIPTS,
// run browser-sync 'serve' and start watchin for changes
gulp.task( 'js', 
    gulp.series( 
        'scripts',
        'browser-sync', 
        function() {
            gulp.watch(
                'assets/js/lib/**/*.js',
                gulp.series('scripts')
            );
        }
    )
);

// Zip the [$plugin_name] folder in pack desitnation
gulp.task('zipit', function() {
	return gulp.src( $packTemp + '**/**' )
	.pipe(zip( $plugin_name + '.' + $plugin_version +'.zip'))
	.pipe( gulp.dest( $packDest ) )
});
// Delete tempoarary folder ( copied theme folder in $packDest directory )
gulp.task('clean-temp', function () {
	del(
		$packTemp,
		{force: true }
	);
});


// PACK EVERYTHING FOR INSTALLATION READY WP THEME ZIP
gulp.task(
    'pack',
    gulp.series(
        'makepot',
        'styles',
        'scripts',
        'copy',
        'eol',
        'zipit',
        'clean-temp'
    )
);

// Additional useful tasks
// RUN CSS AND JS FILES
gulp.task(
    'cssjs',
    gulp.series(
        'styles',
        'scripts'
    )
);
