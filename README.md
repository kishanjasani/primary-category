# Primary Category
Allows you to choose primary category for posts and custom post types.

## Description

**Notice:** `Currently works only with WordPress Gutenberg Editor, not Classic Editor compatible`

- Allows you to choose primary category for posts and custom post types.

- When the permalink structure includes category and the category marked as Primary, will be used for generating the permalink of the post.

- Works with posts and custom post types.

## Installation

1. Go to WordPress Plugin's Directory and Run the following commands to install plugin:
```
git clone git@github.com:kishanjasani/primary-category.git
cd primary-category
composer dumpautoload
npm i
```

2. Now go to WordPress backend -> Plugin page -> Activate the Plugin.

3. Now you should have ability to choose primary category in post category section.

A video walkthrough of the plugin : [link](https://www.loom.com/share/77f7657cbb1b453390d9044a8847f933).

## Automated Testing
Setup Unit Test for the development:

1. Go inside the `Primary Category` plugin directory
2. Go to `composer.json` file and edit `setup-local-tests` script with your database configuration.
3. Then tun `composer run setup-local-tests`
4. Run `composer run test`

## Best Practice
The plugin follows WordPress and WordPress VIP coding standards. You can check the standard configuration inside the `phpcs.xml.dist` file.
