/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
import '../css/app.scss';


import $ from 'jquery';
import 'bootstrap'; // adds functions to jQuery
// import 'google-auth-library/build/src/auth/oauth2client';

// import 'particles';
// uncomment if you have legacy code that needs global variables
//global.$ = $;
// import getNiceMessage from './components/get_nice_message';
//
// console.log(getNiceMessage(5));
//
// $('.dropdown-toggle').dropdown();
// $('.custom-file-input').on('change', function(event) {
//     var inputFile = event.currentTarget;
//     $(inputFile).parent()
//         .find('.custom-file-label')
//         .html(inputFile.files[0].name);
// });
// Import TinyMCE
import tinymce from 'tinymce/tinymce';

// A theme is also required
import 'tinymce/themes/silver';
import 'tinymce/themes/mobile';

// Any plugins you want to use has to be imported
import 'tinymce/plugins/paste';
import 'tinymce/plugins/link';

// Initialize the app
tinymce.init({
    selector: 'textarea#js-textarea',
    language: 'fr_FR',


    plugins: ['paste', 'link']
});


$('.dropdown-toggle').dropdown();
$('.custom-file-input').on('change', function(event) {
    var inputFile = event.currentTarget;
    $(inputFile).parent()
        .find('.custom-file-label')
        .html(inputFile.files[0].name);
});