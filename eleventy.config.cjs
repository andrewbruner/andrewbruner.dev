const syntaxHighlight = require('@11ty/eleventy-plugin-syntaxhighlight');

module.exports = function(eleventyConfig) {
	eleventyConfig.addPlugin(syntaxHighlight);
	eleventyConfig.addPassthroughCopy('src/css');
	return {
		dir: {
			input: "src",
			layouts: "layouts",
			output: "public",
		},
	};
};
