'use strict';

module.exports = function (grunt) {

    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1,
                sourceMap: true
            },
            css_shuwee: {
                files: {
                    'src/Resources/public/css/shuwee_admin.min.css': [
                        'src/Resources/public/css/shuwee_admin/base.css',
                        'src/Resources/public/css/shuwee_admin/shuwee_admin.css'
                    ]
                }
            },
            css_vendors: {
                files: {
                    'src/Resources/public/css/vendors.min.css': [
                        'src/Resources/public/css/vendors/bootstrap.min.css',
                        'src/Resources/public/css/vendors/bootstrap-markdown.min.css'
                    ]
                }
            }
        },
        jshint: {
            shuwee: [
                'src/Resources/public/js/shuwee_admin/*.js'
            ]
        },
        sass: {
            dist: {
                options: {
                    style: 'expanded',
                    debugInfo: false,
                    lineNumbers: false
                },
                files: [{
                    expand: true,
                    debugInfo: false,
                    lineNumbers: false,
                    cwd: 'src/Resources/public/scss/',
                    src: ['*.scss'],
                    dest: 'src/Resources/public/css/shuwee_admin/',
                    ext: '.css'
                }]
            }
        },
        uglify: {
            options: {
                mangle: false,
                sourceMap: true
            },
            // Uglify both Shuwee and vendors are all vendors are not minified by default (and so it generates a map for us
            js_vendors: {
                files: {
                    'src/Resources/public/js/vendors.min.js': [
                        // do not use *.js as order is important
                        'src/Resources/public/js/vendors/jquery.min.js',
                        'src/Resources/public/js/vendors/bootstrap.min.js',
                        'src/Resources/public/js/vendors/markdown.js',
                        'src/Resources/public/js/vendors/bootstrap-markdown.js',
                        'src/Resources/public/js/vendors/stacktable.js'
                    ]
                }
            },
            js_shuwee: {
                files: {
                    'src/Resources/public/js/shuwee_admin.min.js': [
                        // do not use *.js as order is important
                        'src/Resources/public/js/shuwee_admin/common.js',
                        'src/Resources/public/js/shuwee_admin/forms.js'
                    ]
                }
            }
        },
        watch: {
            options: {
                livereload: true
            },
            sass: {
                files: 'src/Resources/public/scss/**',
                tasks: ['sass'],
                options: {
                    interrupt: true,
                }
            }
        }
    });

    grunt.registerTask('default', [
        'dev',
    ]);

    /**
     * Build and watch easy to debug resources for development.
     */
    grunt.registerTask('dev', [
        'sass',
        'jshint',
        'watch',
    ]);

    /**
     * Build minified resources for production
     */
    grunt.registerTask('dist', [
        'sass',
        'uglify',
        'cssmin'
    ]);
};
