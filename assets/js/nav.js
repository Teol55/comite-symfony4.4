import '../css/nav.scss';
import $ from 'jquery';
$(document).ready(function(){
    $('.toggle_btn').click(function(){
        $(this).toggleClass('open');
        $('.menu').toggleClass('nav_open');
        $('.site-content').toggleClass('site-content_nav');
    });
});