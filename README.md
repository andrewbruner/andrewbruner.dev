# [andrewbruner.dev](https://andrewbruner.dev)

> Personal website of Andrew Bruner

# Project Structure

```
.
├── /.github
│   └── /workflows
│       └── static.yml              // GitHub Actions workflow
├── /github-pages                   // directory for GitHub Pages deployment
│   └── ...
├── /public                         // directory for Firebase deployment
│   └── ...
├── /src                            // source directory for both /github-pages and /public builds
│   ├── /css
│   │   ├── prism-vsc-dark-plus.css // styles for codeblock syntax highlighting
│   │   └── style.css               // styles for blog posts
│   ├── /js
│   │   ├── script.js               // script for navbar functionality
│   │   └── script-404.js           // script for 404 page error message
│   ├── /layouts                    // reused templates (layouts)
│   │   ├── base.njk                // layout for base of every page
│   │   └── post.njk                // layout for post page
│   ├── /posts
│   │   ├── index.njk               // index file for list of all posts
│   │   ├── posts.json              // data file for all posts
│   │   └── ... .md                 // blog post markdown files
│   ├── 404.njk                     // 404 index file
│   ├── index.njk                   // index file for homepage
│   └── rss.njk                     // index file for rss.xml
├── [.env]                          // environment variables file
│                                      DO NOT COMMIT TO VERSION CONTROL!
├── .firebaserc                     // configuration for Firebase deploy target
├── .gitignore                      // configuration of files for git to ignore
│                                     (node_modules, .env, and potential firebase credentials)
├── eleventy.config.cjs             // configuration file for eleventy builds
├── firebase.json                   // configuration file for firebase deployment
├── hashnode.js                     // script to export blog post to Hashnode.dev
├── LICENSE                         // open source licence
├── package.json                    // project information
├── package-lock.json               // dependency information
└── README.md                       // what you're reading now!
```

To print the above file tree in the terminal, see below:

*Command*

`tree -a -I '.git|node_modules' --dirsfirst`

*Syntax*

`tree <show hidden files> <ignore .git and node_modules> <list directories first>`

# Blog Posts

- To add new post, create new markdown file in `src/posts` directory as `<new-post-title>.md`
- To update existing post, copy `variables.date` value in front matter to second-to-last item in `history` array, then update `variables.date` with current date.

# Deployment

- To run local server for testing, run command `npm run serve`

## Hashnode

- To export a new markdown blog post to Hashnode.dev or update an existing one, run command `npm run hashnode` and enter the .md filename at the prompt.

## GitHub Pages

- For GitHub Pages **build step**, run command `npm run github-pages`
- For GitHub Pages **deployment**, commit and push to `develop` branch.
- To **skip** GitHub Pages deployment with change to `develop` branch, add string `[skip ci]` to commit message.

## Firebase

- For Firebase **build step**, run command `npm run build`
- For Firebase **pre-deployment**, create/merge pull request for `main` branch with title: `Deploy vX.Y.Z`
- For Firebase **deployment**, run command `npm run deploy`

# Versioning

**Major . Minor . Patch/Article**
