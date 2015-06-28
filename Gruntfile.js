'use strict';

module.exports = function (grunt) {

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        // TODO copy fonts ?
        concat: {
            options: {
                separator: grunt.util.linefeed,
                banner: '/*! <%= pkg.name %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd HH:MM") %> */'
                        + grunt.util.linefeed
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
            js_shuwee: {
                src: [
                    'assets/js/*.js'
                ],
                dest: 'src/Resources/public/js/shuwee_admin.js'
            },
            css_vendors: {
                src: [
                    'assets/vendors/bootstrap/dist/css/bootstrap.min.css',
                    'assets/vendors/bootstrap/dist/css/bootstrap-theme.min.css',
                    'assets/vendors/bootstrap-markdown/css/bootstrap-markdown.min.css'
                ],
                dest: 'src/Resources/public/css/vendors.css'
            },
            css_shuwee: {
                src: [
                    'assets/css/shuwee-admin.css',
                    'assets/css/shuwee-admin-form.css'
                ],
                dest: 'src/Resources/public/css/shuwee_admin.css'
            }
        },
        uglify: {
            options: {
                mangle: false,
                sourceMap: true
            },
            // Vendors are already minified, we will uglify only Shuwee own scripts and styles
            shuwee: {
                files: {
                    'src/Resources/public/js/shuwee_admin.js': 'src/Resources/public/js/shuwee_admin.js'
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
                    'concat',
                    'uglify'
                ]
            }
        }
    });

    grunt.registerTask('default', [
        'concat',
        'uglify',
        'watch'
    ]);
};
