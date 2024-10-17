<?php
 // Protect the file from direct access
 if (!defined('ABSPATH')) {
    exit;
 }
 
 class Constants {
    const ALLOWED_TAGS_HTML = array(
    'a' => array(
        'href' => true,
        'title' => true,
        'target' => true,
        'rel' => true,
        'class' => true,
        'style' => true,
    ),
    'abbr' => array(
        'class' => true,
        'style' => true,
    ),
    'acronym' => array(
        'class' => true,
        'style' => true,
    ),
    'address' => array(
        'class' => true,
        'style' => true,
    ),
    'article' => array(
        'class' => true,
        'style' => true,
    ),
    'aside' => array(
        'class' => true,
        'style' => true,
    ),
    'b' => array(
        'class' => true,
        'style' => true,
    ),
    'bdi' => array(
        'class' => true,
        'style' => true,
    ),
    'bdo' => array(
        'class' => true,
        'style' => true,
    ),
    'blockquote' => array(
        'cite' => true,
        'class' => true,
        'style' => true,
    ),
    'br' => array(
        'class' => true,
        'style' => true,
    ),
    'button' => array(
        'type' => true,
        'class' => true,
        'style' => true,
    ),
    'canvas' => array(
        'class' => true,
        'style' => true,
    ),
    'caption' => array(
        'class' => true,
        'style' => true,
    ),
    'cite' => array(
        'class' => true,
        'style' => true,
    ),
    'code' => array(
        'class' => true,
        'style' => true,
    ),
    'div' => array(
        'class' => true,
        'style' => true,
        'id' => true,
    ),
    'dl' => array(
        'class' => true,
        'style' => true,
    ),
    'dt' => array(
        'class' => true,
        'style' => true,
    ),
    'em' => array(
        'class' => true,
        'style' => true,
    ),
    'footer' => array(
        'class' => true,
        'style' => true,
    ),
    'form' => array(
        'action' => true,
        'method' => true,
        'enctype' => true,
        'class' => true,
        'style' => true,
    ),
    'h1' => array(
        'class' => true,
        'style' => true,
    ),
    'h2' => array(
        'class' => true,
        'style' => true,
    ),
    'h3' => array(
        'class' => true,
        'style' => true,
    ),
    'h4' => array(
        'class' => true,
        'style' => true,
    ),
    'h5' => array(
        'class' => true,
        'style' => true,
    ),
    'h6' => array(
        'class' => true,
        'style' => true,
    ),
    'header' => array(
        'class' => true,
        'style' => true,
    ),
    'hr' => array(
        'class' => true,
        'style' => true,
    ),
    'i' => array(
        'class' => true,
        'style' => true,
    ),
    'img' => array(
        'src' => true,
        'alt' => true,
        'title' => true,
        'width' => true,
        'height' => true,
        'class' => true,
        'style' => true,
    ),
    'input' => array(
        'type' => true,
        'name' => true,
        'value' => true,
        'placeholder' => true,
        'class' => true,
        'style' => true,
    ),
    'label' => array(
        'for' => true,
        'class' => true,
        'style' => true,
    ),
    'li' => array(
        'class' => true,
        'style' => true,
    ),
    'ol' => array(
        'class' => true,
        'style' => true,
    ),
    'p' => array(
        'class' => true,
        'style' => true,
    ),
    'pre' => array(
        'class' => true,
        'style' => true,
    ),
    'q' => array(
        'cite' => true,
        'class' => true,
        'style' => true,
    ),
    'section' => array(
        'class' => true,
        'style' => true,
    ),
    'span' => array(
        'class' => true,
        'style' => true,
    ),
    'strong' => array(
        'class' => true,
        'style' => true,
    ),
    'sub' => array(
        'class' => true,
        'style' => true,
    ),
    'sup' => array(
        'class' => true,
        'style' => true,
    ),
    'table' => array(
        'class' => true,
        'style' => true,
    ),
    'tbody' => array(
        'class' => true,
        'style' => true,
    ),
    'td' => array(
        'colspan' => true,
        'rowspan' => true,
        'class' => true,
        'style' => true,
    ),
    'th' => array(
        'colspan' => true,
        'rowspan' => true,
        'class' => true,
        'style' => true,
    ),
    'thead' => array(
        'class' => true,
        'style' => true,
    ),
    'tfoot' => array(
        'class' => true,
        'style' => true,
    ),
    'tr' => array(
        'class' => true,
        'style' => true,
    ),
    'u' => array(
        'class' => true,
        'style' => true,
    ),
    'ul' => array(
        'class' => true,
        'style' => true,
    ),
    'video' => array(
        'src' => true,
        'controls' => true,
        'autoplay' => true,
        'loop' => true,
        'muted' => true,
        'class' => true,
        'style' => true,
    ),
    'svg' => array(
        'xmlns' => true,
        'fill' => true,
        'viewBox' => true,
        'stroke-width' => true,
        'stroke' => true,
        'class' => true,
        'style' => true,
        'path' => array(
            'stroke-linecap' => true,
            'stroke-linejoin' => true,
            'd' => true,
            'class' => true,
            'style' => true,
        ),
    ),
    'path' => array(
            'stroke-linecap' => true,
            'stroke-linejoin' => true,
            'd' => true,
    ),
    'script' => array(
            'async' => true,
            'type' => true,
            'src' => true,
    ),
    );
}
?>