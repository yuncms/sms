'use strict';
module.exports = function (grunt) {
    // Project Configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        watch: {
            inspinia_js: {
                files: ['resources/assets/cp/src/js/*.js'],
                tasks: ['uglify:inspinia_js']
            },
            other_js: {
                files: ['resources/assets/*/dist/*.js', '!resources/assets/*/dist/*.min.js'],
                tasks: ['uglify:other_js']
            }
        },
        postcss: {
            options: {
                map: true,
                processors: [
                    require('autoprefixer')({browsers: 'last 2 versions'})
                ]
            },
            dist: {
                expand: true,
                cwd: 'resources/assets',
                src: [
                    '**/*.css'
                ],
                dest: 'resources/assets'
            }
        },
        uglify: {
            options: {
                sourceMap: true,
                preserveComments: 'some',
                screwIE8: true,
                mangle: false
            },
            inspinia_js: {
                src: 'resources/assets/inspinia/dist/js/inspinia.js',
                dest: 'resources/assets/inspinia/dist/js/inspinia.min.js'
            },
            other_js: {//批量压缩其他JS
                expand: true,
                cwd: 'resources/assets',
                src: ['*/dist/*.js', '!*/dist/*.min.js', '!tests/dist/tests.js'],
                dest: 'resources/assets',
                rename: function (dest, src) {
                    // Keep them where they came from
                    return dest + '/' + src;
                },
                ext: '.min.js'
            }
        },
        jshint: {
            options: {
                expr: true,
                laxbreak: true,
                loopfunc: true, // Supresses "Don't make functions within a loop." errors
                shadow: true,
                strict: false,
                force: true,
                '-W041': true,
                '-W061': true
            },
            beforeconcat: [
                'Gruntfile.js',
                'resources/assets/**/*.js',
                '!resources/assets/**/*.min.js',
                '!resources/assets/inspinia/dist/js/inspinia.js'
            ],
            afterconcat: [
                'resources/assets/inspinia/dist/js/inspinia.js'
            ]
        }
    });

    //Load NPM tasks
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-jshint');

    // Default task(s).
    grunt.registerTask('css', ['postcss']);
    grunt.registerTask('js', ['jshint:beforeconcat', 'concat', 'jshint:afterconcat', 'uglify']);
    grunt.registerTask('default', ['css', 'js']);
};
