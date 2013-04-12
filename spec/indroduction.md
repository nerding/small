# Branch

Branch is a (purely hypothetical) blogging engine focusing on simplicity that  
takes cues from [John O'Nolan's Ghost](http://john.onolan.org/ghost/). The 
major difference here is that instead of being thought out from a purely 
design point of view, Branch aims to be thought out from a more developmental 
point of view.

It would be safe to say that this is an attempt to describe the implementation
of Ghost. Along with looking at the proposal, some things and ideas are
inspired by comments from 
[Ghost's Hacker News post](http://news.ycombinator.com/item?id=4743245), and
the [follow up on John's blog](http://john.onolan.org/ghost-from-fiction-to-function/).

If you haven't yet read the information on Ghost, you should do that first...

## Language and initial implementation

While a lot of the comments on HN suggest that people would *love* using
something other than PHP for such a project, I think the PHP is the way to go.
It's not that I love PHP (there are quite a few things I don't like about
PHP), but it is available on almost every generic webserver that your average
person would go out and buy (well, technically rent, but that's another
discussion). I think that part of WordPress' huge success is it's choice of
PHP over different languages because of the ease of installation.

Sure, I love python a whole lot more than PHP, and I think writing a blogging
engine in python might be a lot nicer than doing so in PHP, but it wouldn't be
easy for Joey Blogwriter to go forth and install on his server unless he has
some experience with SSH and the command line. When it comes to making it easy
for someone to express their thoughts and publish, installation is a big
aspect.


### The WordPress issue

I personally think it's a non-issue, but that's just me. Branch *would not* be
a WordPress fork, or an anything fork. Branch would be a stand-alone
application dependent on nothing other than itself.

By choosing not to fork something else (even a smaller project) and start from
scratch, all of the legacy behind the parent application. One doesn't have to
worry about design decisions made the original team almost a decade ago.

Craig Mod wrote an article called 
[Subcompact Publishing](http://craigmod.com/journal/subcompact_publishing/),
where he talks about how it's okay to be subtractive, and how when Honda
started working on the N360 (one of their first cars, and one of the first
subcompacts) they threw everything they knew about car design on production
down and thought about how to build the simplest thing they could. Branch
would follow that same idea, which means dumping WordPress and its legacy.


## Data Stuff (aka nerd things)

As a programmer, I kinda want to get right into the direct implementation side
of things, part of which is the database and how it's structured.

Branch would have five basic entities (there would be quite a few join
entities, but those aren't the basic entities):

- Users
- Posts
- Tags
- Pages
- Attachments (files)

There isn't much else needed directly by the application. While the Ghost
proposal talks about analytics and stuff, all visitor statistics and the like
are better collected by an external application built for just such a task.
Adding that into the core functionality would reduce the simplicity of the
application.


### Users

A User is fairly self explanitory. Users would hold a small amount of
information: their name; their email address; and a short biography.

Users don't need to hold an avatar. The external service Gravatar would be
used for avatars (this is part of the simplifying process. Avatars are another
piece of information that doesn't really need to be kept track of by the
application. Sure, a user could have a foreign key referencing an attachment
as it's avatar, but it's simpler to use gravatar).


### Posts

A Post is simply that, a blog post. Posts would hold the smallest amount of
information needed to operate:

- **Title** - *string* - oh yes, getting fancy here, posts would have a 
  title, used to identify it and stuff...

- **Author** - *int* - The user id of the author.

- **Published** - *boolean* - Has the post been published yet, yes or no.
  There is no in between, no awaiting moderation. That's all another level
  of complexity that isn't needed, and it's part of the editing workflow,
  not the writing workflow.

- **Timestamp** - *datetime* - The date and time of when the publish button
  was hit.

- **Contents** - *text* - The post's contents. Written in markdown. Why
  markdown? Because it's fairly easy to learn, it's simple, and it's better
  for everyone to not use a WYSIWYG editor. And it goes with the simplicity
  goal here.

Posts themselves (or the content of which) could be stored in a database or as
a text file. I personally prefer as a text file, but doing it that way makes
it a little harder implementation-wise (not that I have a problem with that).
A half/half method could also work, where there is a database entry for each
post, but the `contents` field is a file url as opposed to the markdown
contents itself.

Keeping the contents of the post as a plaintext file is better for a whole lot
of reasons, if the database goes down, you still have your content, it's
completely portable (it's just a file), you can edit it outside of the
browser are just a few.

Additionally, keeping an entire post (with all its information) in a file
means that all of the other, non-contents information can be parsed at the
same time as the as the content, and tags can be queried for durring the
process (though that's essentially true of using a purely database based
configuration too).

A major disadvantage comes with the tags though, finding posts with a tag
become `O(n)` operations as opposed to `O(1) + database query` operations,
which gets worse as you accumulate more posts.

Considering both the advantages and disadvantages, a hybrid method will be
used: posts will have a database field with all of the content *except* the
contents, the contents will be stored in a plaintext markdown file, which will
also contain yaml frontmatter of the other information (which will be parsed
out when displaying).

At some point in the future an import tool could be written which would read
from a collection of post files and create the appropriate database entries
(for both tags and posts).


### Tags

Tags are similar to WordPress' categories. They are an individual string
(which means they can contain multiple words), but nothing else. There is no
description other than those words. Tags are just a way for similar posts to
be grouped together.

As such, tags contain only one piece of data, the tag name (and an ID, but
that goes without saying).


### Pages

Similar to posts, pages act in much the same capacity, but instead of having
an author, a timestamp, and tags, they have just a name, their published
status, and their contents.

Like posts, pages would be stored in a hybrid database/file method, likewise
using yaml frontmatter to hold the title and published information.


### Attachments (files)

Attachments are just the assorted files uploaded for use on the site.

I like Ghost's proposal for image uploading, and something similar (if not
entirely the same) would be used.


## Moving Forwards

I would like to start working on this soon. I've recently been lamenting about 
not having a side project to work on (but we'll get to that (maybe)), and this
is something that I've been thinking about (in the abstract) since before the
original Ghost proposal.

I've created a [GitHub repository](https://github.com/dkuntz2/branch) where
I'm going to (hopefully) be uploading my progress to (it's currently empty). 

