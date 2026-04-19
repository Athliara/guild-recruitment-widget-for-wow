<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * custom class/ status texts
 * @since 1.2
 */
$athlios_wow_recruit_defaults = array(
    'class0' => 'Death Knight',
    'class1' => 'Druid',
    'class2' => 'Paladin',
    'class3' => 'Hunter',
    'class4' => 'Rogue',
    'class5' => 'Priest',
    'class6' => 'Shaman',
    'class7' => 'Mage',
    'class8' => 'Warlock',
    'class9' => 'Warrior',
    'class10' => 'Monk',
    'class11' => 'Demon Hunter',
    'class12' => 'Evoker',
    'status0' => 'Closed',
    'status1' => 'Medium',
    'status2' => 'Medium',
    'status3' => 'High',
    'theme' => '',
    'display_closed' => 0,
);

$athlios_wow_recruit_options = get_option('athlios_wow_recruit', array());
if (!is_array($athlios_wow_recruit_options)) {
    $athlios_wow_recruit_options = array();
}
$athlios_wow_recruit_options = wp_parse_args($athlios_wow_recruit_options, $athlios_wow_recruit_defaults);

$athlios_wow_recruit_status = array(
    '0' => $athlios_wow_recruit_options['status0'],
    '1' => $athlios_wow_recruit_options['status2'], // legacy Low values are mapped to Medium
    '2' => $athlios_wow_recruit_options['status2'],
    '3' => $athlios_wow_recruit_options['status3']
);

$athlios_wow_recruit_class = array(
    'deathknight' => $athlios_wow_recruit_options['class0'],
    'druid' => $athlios_wow_recruit_options['class1'],
    'paladin' => $athlios_wow_recruit_options['class2'],
    'hunter' => $athlios_wow_recruit_options['class3'],
    'rogue' => $athlios_wow_recruit_options['class4'],
    'priest' => $athlios_wow_recruit_options['class5'],
    'shaman' => $athlios_wow_recruit_options['class6'],
    'mage' => $athlios_wow_recruit_options['class7'],
    'warlock' => $athlios_wow_recruit_options['class8'],
    'warrior' => $athlios_wow_recruit_options['class9'],
    'monk' => $athlios_wow_recruit_options['class10'],
    'demonhunter' => $athlios_wow_recruit_options['class11'],
    'evoker' => $athlios_wow_recruit_options['class12'],
);

add_action('init', 'athlios_wow_recruit_widget_enqueue_styles');

function athlios_wow_recruit_widget_enqueue_styles()
{
    global $athlios_wow_recruit_options;

    wp_enqueue_style('wr_layout', plugins_url('css/style' . (($athlios_wow_recruit_options['theme'] != '') ? '-' . $athlios_wow_recruit_options['theme'] : '') . '.css', ATHLIOS_WOW_RECRUIT_FILE), array(), '2.1');
}

$athlios_wow_recruit_display_closed = !empty($athlios_wow_recruit_options['display_closed']);
$athlios_wow_recruit_theme = $athlios_wow_recruit_options['theme'];
