ShortcodesConverterTask
=======================

Converts shortcodes for page links and documents from SS2.4 to new SS3.0 notation

Only works for MySQL databases for the moment

Usage:

run sake dev/tasks/ShortcodeConverterTask

What does it do?
This task will traverse all the dataobjects, and for each of them, will do a replace of every instance of the sitetree_link%20 and document_link%20 to the new notation. If dataobject is versioned, live and versioned records will also get converted.
