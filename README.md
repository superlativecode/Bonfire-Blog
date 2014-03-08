Bonfire Blog
============

A simple blog with nested comments, search, markdown editor, tag clouds, and more. This module is designed for Bonfire and CodeIgniter.

**Created by [Superlative Code](http://superlativecode.com/)**. More info available [here](http://superlativecode.com/products/6/bonfire-blog).

###Dependancies not included

*   [Bonfire](https://github.com/ci-bonfire/Bonfire)
*   [Bonfire-Comments](https://github.com/superlativecode/Bonfire-Comments) by [Superlative Code](http://superlativecode.com/)
*   [Bonfire-Images](https://github.com/superlativecode/Bonfire-Images) by [Superlative Code](http://superlativecode.com/)

###Installation

**Note:** We assume you have a working instance of Bonfire running.

1. `cd ./path/to/modules/`
2. `git clone https://github.com/superlativecode/Bonfire-Blog ./blog`
3. `git clone https://github.com/superlativecode/Bonfire-Comments ./comments`
4. `git clone https://github.com/superlativecode/Bonfire-Images ./images`
5.  Login to the admin panel and migrate to the latest version on all three of those modules
6.  Move `/application/modules/images/public/assets/images/spritemap*.png` to `/public/assets/images`
7.  Copy the libraries in `/application/modules/blog/libraries` to `/application/libaries`
8.  Create folder `/public/uploads/` with permissions for read and write
9.  Make sure each module has the correct permissions to access it
10. Add ./public/uploads to your .gitignore


###Features

* Nested Comments
* Approve Comments
* Markdown Editor
* Blog Search
* Tag Clouds
* URL Slugs
* Drag and Drop Images
* Markdown Comments
* Mass Update Posts
* Post Stages (draft, ready for review, and published)
* Author
* Release Date

###Libraries Used

*   [Taggly](https://github.com/EllisLab/CodeIgniter/wiki/Taggly)
*   [Parsedown](http://parsedown.org/)
*   [CodeIgniter Advanced Images](https://github.com/jenssegers/CodeIgniter-Advanced-Images)
*   [Dropzone.js](http://www.dropzonejs.com/)

###TODO

* Better Tags
* Better comment approval process

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/superlativecode/bonfire-blog/trend.png)](https://bitdeli.com/free "Bitdeli Badge")