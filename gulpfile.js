const { src, dest, watch } = require( 'gulp' ), sass = require( 'gulp-sass' )( require( 'sass' ) ), autoprefixer             = require( 'gulp-autoprefixer' )

function compileSass (done) {
	src( 'scss/style.scss' )
	.pipe( sass().on( 'error', sass.logError ) )
	.pipe( autoprefixer() )
	.pipe( dest( '.' ) )
	done()
}

function watchSass () {
	watch( 'scss/style.scss', compileSass )
}

exports.watchSass = watchSass
