# Directory Structure

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


