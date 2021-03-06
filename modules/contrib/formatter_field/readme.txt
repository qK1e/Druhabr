Formatter field
===============

The formatter field module provides a mechanism for specifying a formatter and
formatter settings to be used for displaying a field, on a per-entity basis. By
default, Drupal provides formatters and settings per entity bundle, but in 
certain situations it is necessary to choose the formatter at the entity level.

For example, say you have a page node with an image field. Normally, you would
select an image style as the formatter, and all page nodes would use that same
image style.  With this module, you can add a formatter field to page nodes,
which is hooked up to the image field.  Then when the node is created or edited,
the image style can be selected per-node.

Usage
-----

1. Add the field that will be dynamically formatted as usual.
2. Add a formatter field to the entity bundle, just as you would with any
   other field.
3. In the field instance settings, choose the field that will be formatted by
   this field.
4. In the bundle display settings, select 'Formatter from field' as the
   formatter for the field to be formatter.
