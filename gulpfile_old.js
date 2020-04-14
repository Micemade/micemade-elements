var gulp         = require( "gulp" ),
	sass         = require( 'gulp-sass' ),
	autoprefixer = require( 'gulp-autoprefixer' ),
	concat       = require( 'gulp-concat' ),
	uglify       = require( 'gulp-uglify' ),
	fsCache      = require( 'gulp-fs-cache' ),
	rename       = require( "gulp-rename" ),
	browserSync  = require( 'browser-sync' ),
	wpPot        = require( 'gulp-wp-pot' ),
	dos2unix     = require( 'gulp-dos2unix-js' ),
	zip          = require( 'gulp-zip' ),
	del          = require( 'del' );

// Get project name from json file.
const jsonData = require( './package.json' );

// Project variables.
// var $plugin_name    = jsonData.name,
// 	$plugin_version = jsonData.version,
// 	$packDest       = '/home/alen/Documents/' + $plugin_name + '/',
// 	$packTemp       = $packDest + $plugin_name;

// Project vars.
const project = {
	pluginName: jsonData.name,
	pluginVersion: jsonData.version,
	get packDest() { return '/home/alen/Documents/' + this.pluginName + '/'; },
	get packTemp() { return this.packDest + this.pluginName }
}

// Build config vars.
const config = {
	jsDir:    'assets/js/lib/**/*.js', // Javascript files.
	scssDir:  'assets/css/scss/**/*.scss', // SCSS files.
	cssDir:   './assets/css/scss/micemade-elements.scss', // Main CSS file.
	phpFiles: './**/*.php', // All the PHP files.
	cssDest:  './assets/css', // Destination for CSS.
	jsDest:   './assets/js', // Destintion for JS.
	watchCSS: './assets/css/scss/**/*', // Watch CSS.
	watchJS:  './assets/js/lib/**/*' // Watch JS.
}

// Configure browserSync.
/* gulp.task(
	'browser-sync',
	function() {
		var files = [
			config.cssDir,
			config.phpFiles
		];
		// Initialize BrowserSync with a PHP server
		browserSync.init(
			files,
			{
				proxy: 'http://clothy.loc/'
			}
		);
		gulp.watch(
			[
			config.scssDir,
			],
			gulp.series( 'styles' )
		);
	}
); */
//############################################################################
// BrowserSync
function browserSync(done) {
	browserSync.init({
		injectChanges: true,
		server: {
			baseDir: "./clothy/"
			//baseDir: "/var/www/html/clothy/"
		},
		proxy: 'http://clothy.loc/',
		port: 3000
	});
	done();
}
// BrowserSync Reload
function browserSyncReload(done) {
	browserSync.reload();
	done();
}
// Clean(?) task
function clean() {

} 
// JS task
function js() {
	var jsFsCache = fsCache( '.tmp/jscache' ); // save cache to .tmp/jscache
	return gulp
	.src( config.jsDir )
	.pipe( concat( 'micemade-elements.js' ) )
	.pipe( rename( {suffix: '.min'} ) )
	.pipe( jsFsCache )
	.pipe(uglify())
	.pipe( jsFsCache.restore )
	.pipe( gulp.dest( config.jsDest ) )
	.pipe( browserSync.stream() );
} 
// CSS task
function css() {
	return gulp
	.src( config.scssDir )
	.pipe( sass({ outputStyle: "compressed" } ) )
	.pipe( gulp.dest( config.cssDest ) )
	.pipe( rename({ suffix: ".min" }) )
	.pipe(
		autoprefixer(
			{
				browsers: ['> 1%', 'last 3 versions', 'Firefox >= 20', 'iOS >=7'],
				cascade: false
			}
		)
	)
	.pipe(gulp.dest( config.cssDest ))
	//.pipe(browserSync.stream());
}
// Watch files
function watchFiles() {
	gulp.watch( config.watchCSS, css ).on('change', browserSync.reload);
	gulp.watch( config.watchJS, gulp.series(js));
	//gulp.series( browserSyncReload );
}
const build = gulp.series( clean, gulp.parallel( css, js ) );
const watch = gulp.parallel( watchFiles, browserSync );

// export tasks
exports.css = css;
exports.js = js;
exports.build = build;
exports.watch = watch;
exports.default = build;

//############################################################################

// Process main css file(s) - style.scss and other scss files
gulp.task(
	'styles',
	function () {
		return gulp.src( 'assets/css/scss/**/*.scss' )
		.pipe(
			sass(
				{
					'outputStyle' : 'compressed'
				}
			)
			.on( 'error', sass.logError )
		)
		.pipe(
			autoprefixer(
				{
					browsers: ['> 1%', 'last 3 versions', 'Firefox >= 20', 'iOS >=7'],
					cascade: false
				}
			)
		)
		.pipe( gulp.dest( './assets/css' ) )
		.pipe( browserSync.stream() ) //Possible use in future uncomment if neccessary

	}
);

// Process JS files (concatenate and uglify)
gulp.task(
	'scripts',
	function() {
		var jsFsCache = fsCache( '.tmp/jscache' ); // save cache to .tmp/jscache
		return gulp.src( 'assets/js/lib/**/*.js' )
		.pipe( concat( 'micemade-elements.js' ) )
		.pipe( rename( {suffix: '.min'} ) )
		.pipe( jsFsCache )
		//.pipe(uglify())
		.pipe( jsFsCache.restore )
		.pipe( gulp.dest( './assets/js' ) )

		.pipe( browserSync.stream() );
	}
)

// Generate translation .pot file from all php files
gulp.task(
	'makepot',
	function () {
		return gulp.src( '**/*.php' )
		.pipe(
			wpPot(
				{
					domain: project.pluginName,
					package: project.pluginName,
					team: 'Micemade <alen@micemade.com>'
				}
			)
		)
		.pipe( gulp.dest( './languages/' + project.pluginName + '.pot' ) );
	}
);

// dos2unix.
gulp.task(
	'eol',
	function () {
		return gulp.src(
			[
			//$packTemp + '/**/*.{css,js,php}',
			project.packTemp + '/**/**',
			]
		)
		.pipe( dos2unix() ) // This defaults to {feedback: false, write: false}
		.pipe( gulp.dest( project.packTemp ) )
	}
);
// end dos2unix

// Copy all files to destination
gulp.task(
	'copy',
	function() {
		return gulp.src(
			[
			'./**',
			'!./node_modules',
			'!./node_modules/**',
			'!./gulpfile.js',
			'!./package.json',
			'!./package-lock.json',
			'!./js/tmp',
			'!./js/tmp/**',
			'!./**/*.db' // remove Windows Thumbs.db files
			]
		)
		.pipe( gulp.dest( project.packTemp ) );
	}
)

// #######################################
// The DEFAULT task will process sass,
// run browser-sync and start watchin for changes
//
gulp.task( 'default', gulp.series( browserSync ) );
//
// #######################################


// Process JS SCRIPTS,
// run browser-sync 'serve' and start watchin for changes
//#################################################################
// gulp.task(
// 	'js',
// 	gulp.series(
// 		'scripts',
// 		'browser-sync',
// 		function() {
// 			gulp.watch(
// 				'assets/js/lib/**/*.js',
// 				gulp.series( 'scripts' )
// 			);
// 		}
// 	)
// );

// Zip the [project.pluginName] folder in pack desitnation
gulp.task(
	'zipit',
	function() {
		return gulp.src( project.packTemp + '**/**' )
		.pipe( zip( project.pluginName + '.' + project.pluginVersion + '.zip' ) )
		.pipe( gulp.dest( project.packDest ) )
	}
);
// Delete tempoarary folder ( copied theme folder in project.packDest directory )
gulp.task(
	'clean-temp',
	function () {
		del(
			project.packTemp,
			{force: true }
		);
	}
);


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
gulp.task( 'cssjs', gulp.series( css, js ) );
