Hi, I'm Griffin and this is the customized version of Erin Burt's ComicControl CMS that I did for my webcomic, Apocalypse Child.
This public repository is for anyone who'd like a look at what I did to make it work for the very specific needs of that comic (namely, animated pages that use layers).
Given that this was made for my own needs, I wouldn't recommend using this version of the ComicControl CMS.
This is a work-in-progress and is liable to change and evolve with the needs of the site it's for.

CHANGES:
-PHP deprecation warnings are currently suppressed. This is a temporary fix until I can get around to updating the PHP properly.
-Comic Extra Pages is a field that has been added to the database and the corresponding backend pages for adding and editing comics. Animated pages are handled by layering the animated parts on top of static background pages. There is currently a max of three extra pages/layers that can be added to a page update.
-Static versions of animated pages have been added so that thumbnails for aniamted pages have something to work with.
-Comic high res functionality has been removed. I upload at exactly the size I want so I don't have a use for this.
-Comic Extra Pages can also be laid out in a vertical style like a webtoon if the "Is animated?" field is left unchecked.
-Comic thumbnails have to be uploaded manually since the auto generated thumbnail for animated pages will always be incorrect.
