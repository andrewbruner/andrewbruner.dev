const { EleventyHtmlBasePlugin } = require("@11ty/eleventy");
const syntaxHighlight = require('@11ty/eleventy-plugin-syntaxhighlight');

module.exports = function(eleventyConfig) {
	eleventyConfig.addPlugin(EleventyHtmlBasePlugin);
	eleventyConfig.addPlugin(syntaxHighlight);
	eleventyConfig.addPassthroughCopy('src/css');
	eleventyConfig.addPassthroughCopy('src/js');
	return {
		dir: {
			input: "src",
			layouts: "layouts",
			output: "public",
		},
	};
};
