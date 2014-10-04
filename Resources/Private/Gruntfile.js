'use strict';

// all js development should by in Resources/Private, all included files in Public
// if uglify and concat messes up the include order, move development to public

module.exports = function (grunt) {

	// Project configuration.
	grunt.initConfig({
		dirs: {
			js: {
				src: [
					'JavaScript/**/*.js'
					],
				dest: '../Public/JavaScript'
			},
			sass: {
				src: 'Styles',
				dest: '../Public/Styles'
			},
		},


		fileLists: {
			js: {
				libs: [
					'bower_components/jquery/dist/jquery.min.js',
					'bower_components/bootstrap_sass/js/dropdown.js',
					'bower_components/magnific_popup/js/magnigic-popup.js'
				]
			}
		},


		compass: {
			dist: {
				options: {
					config: 'config.rb'
				}
			}
		},


		concat: {
			dist: {
				src: [
					'<%= fileLists.js.libs %>'
				],
				dest: '<%= dirs.js.dest %>/libs.min.js'
			}
		},


		uglify: {
			dist: {
				src: [
					'<%= dirs.js.src %>'
				],
				dest: '<%= dirs.js.dest %>/app.min.js'
			}
		},


		watch: {
			js: {
				files: ['<%= dirs.js.src %>'],
				tasks: 'dist'
			},
			sass: {
				files: ['<%= dirs.sass.src %>/**/*.scss'],
				tasks: 'compass'
			}
		}
	});

	// Load the plugin that provides the "concat" task.
	grunt.loadNpmTasks('grunt-contrib-concat');

	// Load the plugin that provides the "uglify" task.
	grunt.loadNpmTasks('grunt-contrib-uglify');

	// Load the plugin that provides the "watch" task.
	grunt.loadNpmTasks('grunt-contrib-watch');

	// Load the plugin that provides the "compass" task.
	grunt.loadNpmTasks('grunt-contrib-compass');

	// Load the plugin that provides the "copy" task.
	grunt.loadNpmTasks('grunt-contrib-copy');

	// Load Task to install bower packages
	grunt.loadNpmTasks('grunt-bower-task');

	// Load Task to clean build paths
	grunt.loadNpmTasks('grunt-contrib-clean');


	// Default task.
	grunt.registerTask('default', ['dist', 'compass']);


	grunt.registerTask('dist', ['build:dist']);

	grunt.registerTask('w', ['watch']);

	// Build task.
	grunt.registerTask('build', 'The general build tasks. No arguments builds everything, you can use :dist and :angular', function(argument) {
		var argumentParts = argument.split("_");
		var baseArgument = argumentParts[argumentParts.length -1] === "dev" ? argument.substr(0,argument.length -4) : argument;
		grunt.task.run('uglify:' + baseArgument);
		grunt.task.run('concat:' + argument);
	});
};
