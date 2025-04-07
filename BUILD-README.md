# Build Process Documentation

This document outlines the different build approaches available in the WP Multisite WaaS plugin and how to use them.

## Modern Build Process

The modern build process uses TypeScript, SASS, and modern JavaScript tooling to compile and optimize assets.

### Commands

- `npm run build` - Main build command that runs clean, translations, CSS, and JS processing
- `npm run css` - Compiles SASS to CSS and minifies it
- `npm run js` - Compiles TypeScript to JavaScript and minifies it
- `npm run translations` - Generates translation files
- `npm run lint` - Runs linting for PHP, JS, and CSS
- `npm run test` - Runs Jest tests
- `npm run prepare-release` - Runs build, lint, and test for release preparation
- `npm run zip` - Creates a ZIP file for distribution

### When to use

Use this build process during active development when working with TypeScript files and modern tooling.

## Compatibility Build Process

The compatibility build process is simpler and uses more basic tools like uglify-js and cleancss for direct minification without compilation steps.

### Commands

- `npm run build:compat` - Main compatibility build command
- `npm run build:dev:compat` - Development version of the compatibility build
- `npm run uglify` - Minifies JavaScript files
- `npm run cleancss:compat` - Minifies CSS files
- `npm run makepot` - Generates translation files
- `npm run archive` - Creates a ZIP file using composer

### When to use

Use this build process when working with the plugin-check branch or when you need a simpler build process without TypeScript compilation.

## GitHub Actions Workflow

The GitHub Actions workflow is designed to automatically detect which build process to use based on the presence of TypeScript files. If TypeScript files are detected, it uses the modern build process; otherwise, it falls back to the compatibility build process.

## Merging branches

When merging branches that use different build approaches:

1. Keep both sets of build scripts in package.json
2. The GitHub Actions workflow will automatically detect and use the appropriate build process
3. For local development, choose the build process that matches your current work

This dual approach ensures compatibility with both the modern toolchain and the simpler plugin-check approach. 