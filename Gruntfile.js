'use strict';

module.exports = function (grunt) {

    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        clean: ['src/Resources/public/*'],
        concat: {
            options: {
                separator: grunt.util.linefeed,
                banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd HH:MM") %> */' + grunt.util.linefeed
            },
            js_shuwee: {
                src: [
                    'assets/js/shuwee-admin-form.js'
                ],
                dest: 'src/Resources/public/js/shuwee_admin.js'
            },
            js_vendors: {
                src: [
                    'assets/vendors/jquery/dist/jquery.min.js',
                    'assets/vendors/bootstrap/dist/js/bootstrap.min.js',
                    'assets/vendors/markdown/bin/markdown.js',
                    'assets/vendors/bootstrap-markdown/js/bootstrap-markdown.js'
                ],
                dest: 'src/Resources/public/js/vendors.js'
            },
            css_shuwee: {
                src: [
                    'assets/css/shuwee-admin.css',
                    'assets/css/shuwee-admin-form.css'
                ],
                dest: 'src/Resources/public/css/shuwee_admin.css'
            },
            css_vendors: {
                src: [
                    'assets/vendors/bootstrap/dist/css/bootstrap.min.css',
                    'assets/vendors/bootstrap/dist/css/bootstrap-theme.min.css',
                    'assets/vendors/bootstrap-markdown/css/bootstrap-markdown.min.css'
                ],
                dest: 'src/Resources/public/css/vendors.css'
            }
        },
        copy: {
            fonts: {
                files: [
                    {expand: true, flatten: true, src: ['assets/fonts/*'], dest: 'src/Resources/public/fonts/', filter: 'isFile'},
                ]
            }
        },
        cssmin: {
            options: {
                shorthandCompacting: false,
                roundingPrecision: -1,
                sourceMap: true
            },
            css_shuwee: {
                files: {
                    'src/Resources/public/css/shuwee_admin.css': [
                        'assets/css/shuwee-admin.css',
                        'assets/css/shuwee-admin-form.css'
                    ]
                }
            },
            css_vendors: {
                files: {
                    'src/Resources/public/css/vendors.css': [
                        'assets/vendors/bootstrap/dist/css/bootstrap.min.css',
                        'assets/vendors/bootstrap/dist/css/bootstrap-theme.min.css',
                        'assets/vendors/bootstrap-markdown/css/bootstrap-markdown.min.css'
                    ]
                }
            }
        },
        jshint: {
            shuwee: [
                'assets/js/*'
            ]
        },
        uglify: {
            options: {
                mangle: false,
                sourceMap: true
            },
            // Uglify both Shuwee and vendors are all vendors are not minified by default (and so it generates a map for us
            js_vendors: {
                files: {
                    'src/Resources/public/js/vendors.js': [
                        'assets/vendors/jquery/dist/jquery.min.js',
                        'assets/vendors/bootstrap/dist/js/bootstrap.min.js',
                        'assets/vendors/markdown/bin/markdown.js',
                        'assets/vendors/bootstrap-markdown/js/bootstrap-markdown.js'
                    ]
                }
            },
            js_shuwee: {
                files: {
                    'src/Resources/public/js/shuwee_admin.js': [
                        'assets/js/shuwee-admin-form.js'
                    ]
                }
            }
        },
        watch: {
            options: {
                livereload: true
            },
            assets: {
                files: [
                    'assets/css/*.css',
                    'assets/js/*.js',
                ],
                tasks: [
                    'concat'
                ]
            }
        }
    });

    grunt.registerTask('default', [
        'dev'
    ]);

    /**
     * Build and watch easy to debug resources for development.
     */
    grunt.registerTask('dev', [
        'clean',
        'jshint',
        'copy',
        'concat',
        'watch'
    ]);

    /**
     * Build minified resources for production
     */
    grunt.registerTask('prod', [
        'clean',
        'copy',
        'uglify',
        'cssmin'
    ]);
};
