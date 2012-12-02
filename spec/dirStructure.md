# Directory Structure

## Current

    /contents       # all user content
    |   /posts      # all post files

    /database       # an sql dump of the database I'm using to develop this

    /includes       # all class files and other libraries I'm using. 
                    # will eventualy be inside of the future /branch
                    # directory

    /js             # contains jquery. Will most likely be moved into
                    # the future /theme and /branch directories, because
                    # it'll be uesd by both.
                    #
                    # But extensively by the admin area...

    /spec           # all specification files for the project. Including
                    # this one...

## Eventual - hopefully

There are three non-root directories:

- `/branch` - the administrative area
- `/contents` - contains all media content
- `/theme` - the theme directory


## /branch

The administrative area of a branch install. Both the functions and the look
and feel of the administrative area are held here.


## /contents

The media (including text media) contents of the site. Contents has three
subdirectories:

- `/posts`
- `/pages`
- `/attachments`

One for each of the three media types recognized by branch.

### /contents/posts

Contains plaintext markdown files for branch posts, as described in the
introduction.

Posts would be organized in a `./yyyy/mm/dd/hh-mm-[title].md` manner. The hour
and minute part doesn't need to be part of the file name, it's just suggested. 

### /contents/pages

Likes posts, contains plaintext markdown files for branch pages.

### /contents/attachments

Contains all uploaded files. Branch itself would do no processing on the
files, and would expect users to do the processing (or rely on browsers to do
the processing).


## /theme

Contains the **current** theme used by branch. No other themes are included in
here. There are no subdirectories (unless the theme uses them for assets and
the like), there is only (xul) theme.


