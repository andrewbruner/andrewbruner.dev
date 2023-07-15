const { EleventyHtmlBasePlugin } = require("@11ty/eleventy");
const pluginRss = require('@11ty/eleventy-plugin-rss');
const syntaxHighlight = require('@11ty/eleventy-plugin-syntaxhighlight');
const markdownIt = require('markdown-it');

module.exports = function(eleventyConfig) {
	eleventyConfig.addPlugin(EleventyHtmlBasePlugin);
	eleventyConfig.addPlugin(pluginRss);
	eleventyConfig.addPlugin(syntaxHighlight);
	eleventyConfig.setFrontMatterParsingOptions({
		excerpt: true,
		excerpt_separator: "<!-- excerpt -->"
	});
	eleventyConfig.addFilter('md', (content) => markdownIt({ html: true }).renderInline(content));
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
