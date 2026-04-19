<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the plugin widget.
 *
 * @since 1.0
 */
function athlios_wow_recruit_load_widgets()
{
    register_widget('Athlios_Wow_Recruit_Widget');
}


function athlios_wow_recruit_widget_install()
{
    $options = array(
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
        'theme' => false,
        'display_closed' => false,
    );

    add_option('athlios_wow_recruit', $options);
}

function athlios_wow_recruit_widget_deactivate()
{
    // Intentionally keep saved settings on deactivation.
}

function athlios_wow_recruit_widget_uninstall()
{
    // Reserved for optional uninstall workflow.
}
