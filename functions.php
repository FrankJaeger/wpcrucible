<?php defined('ABSPATH') or die("No script kiddies please!");

require_once('includes/theme-class.php');


$t = new fpwpcr_theme();
$t->add_menu( 'slug-1', 'Menu 1');
$t->add_sidebars(3);

$t->init();


$s = new fpwpcr_settings();

$s->add_section( 'Opcje', 'my-options', 'Opis' );

$s->add_text_field( 'Pole tekstowe', 'pole-textowe', 'my-options' );
$s->add_textarea( 'Moja textarea', 'moja-textarea', 'my-options' );
$s->add_range( 'Mój range', 'my-range', 'my-options' );
$s->add_number( 'Mój number', 'my-number', 'my-options');
$s->add_color( 'Mój kolor', 'my-color', 'my-options');
$s->add_upload( 'Mój upload', 'my-upload', 'my-options' );
$s->add_upload( 'Mój upload 2', 'my-upload2', 'my-options' );

$s->make_my_settings();



?>