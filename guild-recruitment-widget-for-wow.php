<?php
/*
 * Plugin Name:       Guild Recruitment Widget for WoW
 * Plugin URI:        https://gordian-knot.eu
 * Description:       Display your guild recruitment priorities for a World of Warcraft guild in a sidebar widget.
 * Version:           2.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Athlios
 * Author URI:        https://a-wd.eu
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       guild-recruitment-widget-for-wow
 * Domain Path:       /
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Group help and bug report URLs and icon paths for easier maintenance.
 */
define('ATHLIOS_WOW_RECRUIT_HELP_URL', 'https://github.com/Athliara/guild-recruitment-widget-for-wow');
define('ATHLIOS_WOW_RECRUIT_BUG_URL', 'https://github.com/Athliara/guild-recruitment-widget-for-wow/issues');

define('ATHLIOS_WOW_RECRUIT_FILE', __FILE__);

define('ATHLIOS_WOW_RECRUIT_BUG_ICON_URL', plugins_url('images/ic_bug_report.svg', ATHLIOS_WOW_RECRUIT_FILE));
define('ATHLIOS_WOW_RECRUIT_INFO_ICON_URL', plugins_url('images/ic_info_outline.svg', ATHLIOS_WOW_RECRUIT_FILE));

add_filter('plugin_action_links_' . plugin_basename(ATHLIOS_WOW_RECRUIT_FILE), 'athlios_wow_recruit_plugin_action_links');

function athlios_wow_recruit_plugin_action_links($links)
{
    $settings_link = sprintf(
        '<a href="%s">%s</a>',
        esc_url(admin_url('admin.php?page=athlios_wow_recruit_options')),
        esc_html__('Settings', 'guild-recruitment-widget-for-wow')
    );

    array_unshift($links, $settings_link);

    return $links;
}

/**
 * Add function to widgets_init that loads our widget.
 *
 * @since 1.0
 */
add_action('widgets_init', 'athlios_wow_recruit_load_widgets');

/**
 * install/uninstall hooks
 *
 * @since 1.2
 */
register_activation_hook(ATHLIOS_WOW_RECRUIT_FILE, 'athlios_wow_recruit_widget_install');
register_deactivation_hook(ATHLIOS_WOW_RECRUIT_FILE, 'athlios_wow_recruit_widget_deactivate');

include_once plugin_dir_path(__FILE__) . 'inc/hooks.php';
include_once plugin_dir_path(__FILE__) . 'inc/config.php';
include_once plugin_dir_path(__FILE__) . 'inc/widget.php';

if (is_admin()) {
    include_once plugin_dir_path(__FILE__) . 'inc/admin.php';
}
