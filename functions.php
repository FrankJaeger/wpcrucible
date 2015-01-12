<?php defined('ABSPATH') or die("No script kiddies please!");

require_once('theme-class.php');


$t = new fpwpcr_theme();
$t->add_menu( 'slug-1', 'Menu 1');
$t->add_sidebars(3);

$t->init();


$s = new fpwpcr_settings();

$s->add_section( 'Opcje', 'my-options', 'Opis' );
$s->add_text_field( 'Pole tekstowe', 'pole-textowe', 'my-options' );

$s->make_my_settings();

?>