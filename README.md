# Custom fields for Dokuwiki

A way of defining data in wiki text which can then be used in a template. Similar to the custom fields concept in WordPress.

Define the field in your Wiki-text like this:

    ##somefield Hello world##


And use them in your templates like this:

    // will return "Hello world"
    echo getCustomField('somefield');
