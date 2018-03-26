module.exports = function (grunt) {
    // Project Configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        watch: {
            inspiniajs: {
                files: ['src/web/assets/inspinia/src/js/*.js'],
                tasks: ['concat', 'uglify:cpjs']
            },
            otherjs: {
                files: ['src/web/assets/*/dist/*.js', '!src/web/assets/*/dist/*.min.js'],
                tasks: ['uglify:otherjs']
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
                cwd: 'src/web/assets',
                src: [
                    '**/*.css'
                ],
                dest: 'src/web/assets'
            }
        },
        concat: {
            inspiniajs: {
                options: {
                    banner: '/*! <%= pkg.name %> <%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %> */\n'
                },
                src: [
                    'src/web/assets/inspinia/src/js/inspinia.js'
                ],
                dest: 'src/web/assets/inspinia/dist/js/inspinia.js'
            }
        },
        uglify: {
            options: {
                sourceMap: true,
                preserveComments: 'some',
                screwIE8: true
            },
            inspiniajs: {
                src: 'src/web/assets/inspinia/dist/js/inspinia.js',
                dest: 'src/web/assets/inspinia/dist/js/inspinia.min.js'
            },
            otherjs: {
                expand: true,
                cwd: 'src/web/assets',
                src: ['*/dist/*.js', '!*/dist/*.min.js', '!tests/dist/tests.js'],
                dest: 'src/web/assets',
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
                'gruntfile.js',
                'src/web/assets/**/*.js',
                '!src/web/assets/**/*.min.js',
                '!src/web/assets/inspinia/dist/js/inspinia.js'
            ],
            afterconcat: [
                'src/web/assets/inspinia/dist/js/inspinia.js'
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
