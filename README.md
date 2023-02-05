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
│   │   └── script.js               // script for navbar functionality
│   ├── /layouts                    // reused templates (layouts)
│   │   ├── base.njk                // layout for base of every page
│   │   └── post.njk                // layout for post page
│   ├── /posts
│   │   ├── index.njk               // index file for list of all posts
│   │   ├── posts.json              // data file for all posts
│   │   └── ... .md                 // blog post markdown files
│   ├── 404.njk                     // 404 index file
│   └── index.njk                   // index file for homepage
├── .firebaserc                     // configuration for Firebase deploy target
├── .gitignore                      // configuration of files for git to ignore
│                                     (node_modules and potential firebase credentials)
├── eleventy.config.cjs             // configuration file for eleventy builds
├── firebase.json                   // configuration file for firebase deployment
├── LICENSE                         // open source licence
├── package.json                    // project information
├── package-lock.json               // dependency information
└── README.md                       // what you're reading now!
```

To print the above file tree in the terminal, see below:

*Command*

`tree -a -I '.git|node_modules' --dirsfirst /workspaces/andrewbruner.dev`

*Syntax*

`tree <show hidden files> <ignore .git and node_modules> <list directories first> </path/to/directory>`
