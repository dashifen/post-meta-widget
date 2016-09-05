module.exports = function (grunt) {

	grunt.initConfig({
		sass: {
			options: {
				outputStyle: "compressed"
			},

			dist: {
				files: [{
					expand: true,
					cwd: ".",
					src: "**/*.scss",
					dest: ".",
					ext: ".min.css"
				}]
			}
		},

		postcss: {
			options: {
				processors: [
					require("autoprefixer")({
						browsers: ["last 2 versions"]
					})
				]
			}
		},

		uglify: {
			options: {
				screwIE8: true
			},

			my_target: {
				files: [{
					expand: true,
					cwd: ".",
					src: "**/*.js",
					dest: ".",
					ext: ".min.js"
				}]
			}
		},

		watch: {
			css: {
				files: "assets/styles/sass/**/*.scss",
				tasks: ["sass", "concat", "postcss"]
			},

			js: {
				files: "includes/scripts/max/**/*.js",
				tasks: ["uglify"]
			}
		}
	});

	grunt.loadNpmTasks("grunt-contrib-uglify");
	grunt.loadNpmTasks("grunt-contrib-watch");
	grunt.loadNpmTasks("grunt-postcss");
	grunt.loadNpmTasks("grunt-sass");

	grunt.registerTask("css", ["sass", "postcss"]);
	grunt.registerTask("default", ["watch"]);
};