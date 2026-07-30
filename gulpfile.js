const gulp = require('gulp');
const usage = require('gulp-help-doc');
const concat = require('gulp-concat');
const sass = require('gulp-sass');
const del = require('del');
const sourcemaps = require('gulp-sourcemaps');
const composer = require('gulp-composer');
const cleanCSS = require('gulp-clean-css');
const uglify = require('gulp-uglify');
const babel = require('gulp-babel');
const filter = require('gulp-filter');
const expect = require('gulp-expect-file');
const autoprefixer = require('gulp-autoprefixer');

const jsFiles = [

	// vendor
	'node_modules/jquery/dist/jquery.js',
	'node_modules/bootstrap/dist/js/bootstrap.js',
	'node_modules/nette.ajax.js/nette.ajax.js',
	'node_modules/nette.ajax.js/extensions/confirm.ajax.js',
	'node_modules/adt-nette-ajax/extensions/live.js',
	'node_modules/nette-forms/src/assets/netteForms.js',
	'node_modules/jquery-ui/ui/version.js',
	'node_modules/jquery-ui/ui/widgets/datepicker.js',
	'node_modules/jquery-ui/ui/i18n/datepicker-cs.js',
	'node_modules/jquery-ui-timepicker-addon/dist/jquery-ui-timepicker-addon.js',
	'node_modules/js-cookie/dist/js.cookie.js',

	// app
	'www/src/js/*.js',
];

const appJsFilesFilter = filter(['**/www/src/js/*'], {restore: true});

const browsers = [ '> 0.25%', 'not dead', 'last 2 versions'];

const babelConfig = {
	presets: [
		['env', {
			targets: {
				browsers
			}
		}]
	],
	plugins: ["transform-object-rest-spread"],
};

const errorHandler = function(err) {
	console.error(`An error occurred in ${err.fileName}`);
	console.error(`${err.name}: ${err.message}`);
};

/**
 * Prints list of all available gulp commands.
 */
gulp.task('default', () => {
	return usage(gulp);
});

/**
 * Cleans temp directory.
 *
 * @task {clean:temp}
 * @group {Clean}
 */
gulp.task('clean:temp', () => {
	return del([
		'./temp/**/*',
		'!./temp/.gitignore'
	]);
});

/**
 * Cleans dist directory.
 *
 * @task {clean:dist}
 * @group {Clean}
 */
gulp.task('clean:dist', () => {
	return del([
		'./www/dist/**/*',
		'!./www/dist/.gitignore'
	]);
});

/**
 * Cleans temp and dist directories.
 *
 * @task {clean}
 * @group {Clean}
 */
gulp.task('clean', gulp.parallel('clean:temp', 'clean:dist'));

/**
 * Transpiles, uglifies and concatenates all JS files into a single file.
 *
 * @task {js}
 * @group {Development + Deployment}
 */
gulp.task('js', () => {
	return gulp.src(jsFiles)
		.pipe(expect(jsFiles))
		.pipe(sourcemaps.init())
		.pipe(appJsFilesFilter)
		.pipe(babel(babelConfig))
		.pipe(appJsFilesFilter.restore)
		.pipe(uglify())
		.pipe(concat('app.js'))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./www/dist/js/'));
});

/**
 * Transforms source SASS/SCSS files into a single CSS file and minifies it.
 *
 * @task {css}
 * @group {Development + Deployment}
 */
gulp.task('css', () => {
	return gulp.src([
		'./www/src/scss/app.scss',
		'./www/src/scss/admin.scss',
	])
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer({
			browsers,
		}))
		.pipe(cleanCSS({
			// Když používáme pluginy z node_modules, tak musíme assets kopírovat do složky dist; nemůžeme se
			// odkazovat zpět do školy node_modules, která se nenahrává na server
			rebase: false,
		}))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('./www/dist/css/'));
});

/**
 * Moves all assets into a dist directory.
 *
 * @task {assets}
 * @group {Development + Deployment}
 */
gulp.task('assets', () => {
	return gulp.src([
		'./node_modules/@fortawesome/fontawesome-free/webfonts/*',
		'./www/src/fonts/*',
	]).pipe(gulp.dest('./www/dist/fonts'))
});

/**
 * Inits the project for development (i.e. after being cloned from GIT).
 *
 * @task {init}
 * @group {Development}
 */
gulp.task('init', gulp.parallel('js', 'css', 'assets'));

/**
 * Builds all files necessary for a deployment and moves assets into a dist directory.
 *
 * @task {build}
 * @group {Development + Deployment}
 */
gulp.task('build', gulp.series('clean', 'init'));

/**
 * Watches for changes in SASS/SCSS and JS files.
 *
 * @task {watch}
 * @group {Development}
 */
gulp.task('watch', gulp.series(gulp.parallel('js', 'css'), () => {
	let cssWatcher = gulp.watch('www/src/scss/**/*.scss', gulp.series('css'));
	cssWatcher.on('change', (path) => {
		console.log(`File ${path} was changed...`);
	});
	let jsWatcher = gulp.watch('www/src/js/**/*.js', gulp.series('js'));
	jsWatcher.on('change', (path) => {
		console.log(`File ${path} was changed...`);
	});
}));


/**
 * Runs `composer install`
 *
 * @task {composer}
 * @group {Misc}
 */
gulp.task('composer', () => {
	return composer('install');
});
