const { src, dest, watch } = require( 'gulp' ), sass = require( 'gulp-sass' )( require( 'sass' ) ), autoprefixer             = require( 'gulp-autoprefixer' )

function compileSass (done) {
	src( 'scss/theme.scss' )
	.pipe( sass().on( 'error', sass.logError ) )
	.pipe( autoprefixer() )
	.pipe( dest( 'css' ) )
	done()
}

function watchSass () {
	watch( 'scss/theme.scss', compileSass )
}

exports.watchSass = watchSass
