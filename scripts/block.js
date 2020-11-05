'use strict';

// stubs out the files/code for a new ACF-powered WP block

const fs = require('fs');
const args = process.argv.slice(2);
const slug = args[0];

if ( !slug ) {
    throw '🚫 Please provide a slug';
}

// create twig file

fs.writeFile(__dirname + `/../theme/twig/blocks/${slug}.twig`, 
`{% extends 'block.twig' %}

{% block content %}
<p>Content</p>
{% endblock %}`, 
(err) => {
    if (err) throw err;
    console.log('✅ Twig template created');
    console.log('\x1b[36m%s\x1b[0m', `theme/twig/blocks/${slug}.twig`);
});

// create scss file

fs.writeFile(__dirname + `/../src/styles/blocks/_${slug}.scss`, 
`.section--${slug} {
    //
}`, 
(err) => {
    if (err) throw err;
    console.log('✅ Stylesheet created');
    console.log('\x1b[36m%s\x1b[0m', `@import "styles/blocks/${slug}";`);

    // append to master stylesheet
    fs.appendFile(__dirname + `/../src/style.scss`, `
@import "styles/blocks/${slug}";`, (err) => {
        if (err) throw err;
        console.log('✅ Appended to style.scss');    
    })

});

// output stuff to paste into acf.php

console.log('✅ Register block with ACF', '(copy/paste into theme/inc/acf.php)');
console.log('\x1b[36m%s\x1b[0m', `
// ${slug}
acf_register_block([
    'name' => '${slug}',
    'title' => __('${slug}'),
    'description' => __('${slug}'),
    'render_callback' => '__block_render_callback',
    'category' => 'my-blocks',
    'mode' => 'preview',
    'icon' => 'editor-alignleft',
    'keywords' => ['${slug}'],
    //'supports' => [ 'multiple' => false, ],
]);
`);