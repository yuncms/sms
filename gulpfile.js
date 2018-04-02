
var es = require('event-stream');
var gulp = require('gulp');
var rename = require('gulp-rename');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');

var libPath = 'resources/lib/';

var jsDeps = [
    { srcGlob: 'node_modules/bootstrap/dist/js/bootstrap.js', dest: libPath+'bootstrap/js' },
    { srcGlob: 'node_modules/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.js', dest: libPath+'bootstrap-iconpicker/js' },
    { srcGlob: 'node_modules/bootstrap-iconpicker/bootstrap-iconpicker/js/iconset/*', dest: libPath+'bootstrap-iconpicker/js/iconset' },
    { srcGlob: 'node_modules/inputmask/dist/jquery.inputmask.bundle.js', dest: libPath+'inputmask' },
    { srcGlob: 'node_modules/jquery/dist/jquery.js', dest: libPath+'jquery' },
    { srcGlob: 'node_modules/punycode/punycode.js', dest: libPath+'punycode' },
    { srcGlob: 'node_modules/yii2-pjax/jquery.pjax.js', dest: libPath+'yii2-pjax' },
    { srcGlob: 'node_modules/jquery-treegrid/js/*', dest: libPath+'jquery-treegrid/js' },
    { srcGlob: 'node_modules/typeahead.js/dist/*', dest: libPath+'typeahead.js' }
];

var staticDeps = [
    { srcGlob: 'node_modules/bootstrap/dist/css/*', dest: libPath+'bootstrap/css' },
    { srcGlob: 'node_modules/bootstrap/dist/fonts/*', dest: libPath+'bootstrap/fonts' },
    { srcGlob: 'node_modules/bootstrap-iconpicker/bootstrap-iconpicker/css/*', dest: libPath+'bootstrap-iconpicker/css' },
    { srcGlob: 'node_modules/jquery-treegrid/css/*', dest: libPath+'jquery-treegrid/css' },
    { srcGlob: 'node_modules/font-awesome/css/*', dest: libPath+'font-awesome/css' },
    { srcGlob: 'node_modules/font-awesome/fonts/*', dest: libPath+'font-awesome/fonts' },
    { srcGlob: 'node_modules/cropper/dist/*', dest: libPath+'cropper' },
    { srcGlob: 'node_modules/metismenu/dist/*', dest: libPath+'metismenu' },
    { srcGlob: 'node_modules/pace-progress/*', dest: libPath+'pace' },
    { srcGlob: 'node_modules/jquery-slimscroll/*', dest: libPath+'jquery-slimscroll' },
    { srcGlob: 'node_modules/bootstrap-filestyle/src/*', dest: libPath+'bootstrap-filestyle' },
    { srcGlob: 'node_modules/animate.css/*', dest: libPath+'animate.css' },
    { srcGlob: 'node_modules/blueimp-canvas-to-blob/js/*', dest: libPath+'blueimp-canvas-to-blob' },
    { srcGlob: 'node_modules/blueimp-tmpl/js/*', dest: libPath+'blueimp-tmpl' },
    { srcGlob: 'node_modules/blueimp-file-upload/js/*', dest: libPath+'blueimp-file-upload/js' },
    { srcGlob: 'node_modules/blueimp-file-upload/css/*', dest: libPath+'blueimp-file-upload/css' },
    { srcGlob: 'node_modules/blueimp-load-image/js/*', dest: libPath+'blueimp-load-image' }
];

gulp.task('deps', function() {
    var streams = [];

    // Minify & move the JS deps
    jsDeps.forEach(function(dep) {
        streams.push(
            gulp.src(dep.srcGlob)
            //.pipe(gulp.dest(dest))
                .pipe(sourcemaps.init())
                .pipe(uglify())
                //.pipe(rename({ suffix: '.min' }))
                .pipe(sourcemaps.write('./'))
                .pipe(gulp.dest(dep.dest))
        );
    });

    // Statically move over any dep files we don't need to modify
    staticDeps.forEach(function(dep) {
        streams.push(
            gulp.src(dep.srcGlob)
                .pipe(gulp.dest(dep.dest))
        );
    });

    return es.merge(streams);
});