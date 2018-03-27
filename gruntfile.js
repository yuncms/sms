module.exports = function (grunt) {
    // Project Configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        watch: {
            inspiniajs: {
                files: ['resources/assets/inspinia/src/js/*.js'],
                tasks: ['concat', 'uglify:inspiniajs']
            },
            userjs: {
                files: ['resources/assets/yuncms-user/src/js/*.js'],
                tasks: [ 'uglify:userjs']
            },
            otherjs: {
                files: ['resources/assets/*/dist/*.js', '!resources/assets/*/dist/*.min.js'],
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
                cwd: 'resources/assets',
                src: [
                    '**/*.css'
                ],
                dest: 'resources/assets'
            }
        },
        concat: {
            inspiniajs: {
                options: {
                    banner: '/*! <%= pkg.name %> <%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %> */\n'
                },
                src: [
                    'resources/assets/inspinia/src/js/inspinia.js'
                ],
                dest: 'resources/assets/inspinia/dist/js/inspinia.js'
            }
        },
        uglify: {
            options: {
                sourceMap: true,
                preserveComments: 'some',
                screwIE8: true
            },
            inspiniajs: {
                src: 'resources/assets/inspinia/dist/js/inspinia.js',
                dest: 'resources/assets/inspinia/dist/js/inspinia.min.js'
            },
            userjs: {
                src: 'resources/assets/yuncms-user/src/js/user.js',
                dest: 'resources/assets/yuncms-user/dist/js/user.min.js'
            },
            usercropperjs: {
                src: 'resources/assets/yuncms-user/src/js/cropper.js',
                dest: 'resources/assets/yuncms-user/dist/js/cropper.min.js'
            },
            otherjs: {
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
                'gruntfile.js',
                'resources/assets/**/*.js',
                '!resources/assets/**/*.min.js',
                '!resources/assets/inspinia/dist/js/inspinia.js',
                '!resources/assets/yuncms-user/src/js/user.js',
                '!resources/assets/yuncms-user/src/js/cropper.js'
            ],
            afterconcat: [
                'resources/assets/inspinia/dist/js/inspinia.js',
                'resources/assets/yuncms-user/dist/js/user.js',
                'resources/assets/yuncms-user/dist/js/cropper.js'
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
