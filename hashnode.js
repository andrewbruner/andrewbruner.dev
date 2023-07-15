import dotenv from 'dotenv';
dotenv.config();
const personalAccessToken = process.env.PERSONAL_ACCESS_TOKEN;

let title = '';
let slug = '';
let lastModified = '';
let contentMarkdown = '';
let isRepublished = { originalArticleURL: '', };
let isPartOfPublication = { publicationId: '5fc6f68aa0a3250b19a58afc', };
let tags = [];

function hashnode() {
	fetch(`https://api.hashnode.com?_=${new Date().getTime()}`, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		cache: 'no-store',
		body: JSON.stringify({
			query: `query post($slug: String!, $hostname: String,) {
				post(slug: $slug, hostname: $hostname,) {
					_id,
				}
			}`,
			variables: {
				slug,
				hostname: 'andrewbruner',
			},
		}),
	})
		.then(response => response.json())
		.then(data => {
			if (data.data.post) {
				contentMarkdown = `*original article: [andrewbruner.dev/${slug}](https://andrewbruner.dev/${slug})*  \n*last modified: ${lastModified}*\n\n${contentMarkdown}`;
				fetch('https://api.hashnode.com', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						Authorization: personalAccessToken,
					},
					body: JSON.stringify({
						query: `mutation updateStory($postId: String!, $input: UpdateStoryInput!,) {
							updateStory(postId: $postId, input: $input,) {
								success,
							}
						}`,
						variables: {
							postId: data.data.post._id,
							input: { title, slug, contentMarkdown, isRepublished, isPartOfPublication, tags, },
						},
					}),
				})
					.then(response => response.json())
					.then(data => {
						if (data.data?.updateStory.success) {
							console.log('Hashnode article updated successfully');
						} else {
							console.log('Error updating Hashnode article');
							console.dir(data, { depth: null });
						}
					});
			} else {
				contentMarkdown = `*original article: [andrewbruner.dev/${slug}](https://andrewbruner.dev/${slug})*\n\n${contentMarkdown}`;
				fetch('https://api.hashnode.com', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						Authorization: personalAccessToken,
					},
					body: JSON.stringify({
						query: `mutation createStory($input: CreateStoryInput!,) {
							createStory(input: $input,) {
								success,
							}
						}`,
						variables: {
							input: { title, slug, contentMarkdown, isRepublished, isPartOfPublication, tags, },
						},
					}),
				})
					.then(response => response.json())
					.then(data => {
						if (data.data?.createStory.success) {
							console.log('Hashnode article created successfully');
						} else {
							console.log('Error creating Hashnode article')
							console.dir(data, { depth: null });
						}
					});
			}
		});
}

import path from 'path';
import url from 'url';
const filename = url.fileURLToPath(import.meta.url);
const dirname = path.dirname(filename);
import fs from 'fs';
import readline from 'readline';
import YAML from 'js-yaml';

function completer(line) {
  const currAddedDir = line.indexOf('/') !== - 1
		? line.substring(0, line.lastIndexOf('/') + 1)
		: '';
  const currAddingDir = line.substring(line.lastIndexOf('/') + 1);
  const path = `${dirname}/src/posts/${currAddedDir}`;
  const completions = fs.readdirSync(path);
  const hits = completions.filter(completion => completion.indexOf(currAddingDir) === 0);
  let strike = [];
  if (hits.length === 1) strike.push(`${currAddedDir}${hits[0]}`);
  return strike.length
		? [strike, line]
		: hits.length
			? hits
			: [completions, line];
}

const rl = readline.createInterface({
	input: process.stdin,
	output: process.stdout,
	completer,
});

function splitFile(data) {
	const yamlRegEx = /^---\s*\n([\s\S]*?)\n---\s*\n/;
	const yamlArr = data.match(yamlRegEx);
	if (yamlArr) {
		const yaml = YAML.load(yamlArr[1].trim());
		const markdown = data.slice(yamlArr[0].length);
		return { yaml, markdown, };
	} else {
		return { yaml: {}, markdown: data, };
	}
}

function slugify(title) {
	return title
		.toLowerCase()
		.replace(/[^\w\s-]/g, '')
		.replace(/\s+/g, '-')
		.replace(/--+/g, '-')
		.trim();
}

rl.question('\nWhich article should be exported?\n\n> ', article => {
	console.log(`\nExporting ${article}...\n`);
	const file = fs.readFileSync(`./src/posts/${article}`, 'utf-8');
	const { yaml, markdown, } = splitFile(file);
	title = yaml.title;
	slug = slugify(yaml.title);
	lastModified = `${['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][yaml.date.getMonth()]} ${yaml.date.getDate().toString().padStart(2, '0')}, ${yaml.date.getFullYear()}`;
	contentMarkdown = markdown;
	isRepublished = { originalArticleURL: `https://andrewbruner.dev/${slugify(yaml.title)}`, };
	hashnode();
  rl.close();
});
