<?php
/*
 * Plugin Name:       Guild Recruitment Widget for WoW
 * Plugin URI:        https://gordian-knot.eu
 * Description:       Display your guild recruitment priorities for a World of Warcraft guild in a sidebar widget.
 * Version:           2.1
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
define('ATHLIOS_WOW_RECRUIT_MENU_ICON', 'data:image/svg+xml;base64,PHN2ZyBpZD0ic3ZnIiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHdpZHRoPSI0MDAiIGhlaWdodD0iNDAwIiB2aWV3Qm94PSIwLCAwLCA0MDAsNDAwIj48ZyBpZD0ic3ZnZyI+PHBhdGggaWQ9InBhdGgwIiBkPSJNMTY3Ljk5MyA1Mi41OTMgQyAxNTMuODYwIDU1LjYyMSwxMzUuNzg3IDYyLjYzMSwxMjQuMzM2IDY5LjUyNyBMIDExNS4yNDYgNzUuMDAwIDk1LjEyMyA3NS4wMDAgTCA3NS4wMDAgNzUuMDAwIDc1LjAwMCA5NS4xMjMgTCA3NS4wMDAgMTE1LjI0NiA2OS41MjcgMTI0LjMzNiBDIDQzLjI1MiAxNjcuOTY5LDQzLjI1MiAyMzIuMDMxLDY5LjUyNyAyNzUuNjY0IEwgNzUuMDAwIDI4NC43NTQgNzUuMDAwIDMwNC44NzcgTCA3NS4wMDAgMzI1LjAwMCA5NS4xMjMgMzI1LjAwMCBMIDExNS4yNDYgMzI1LjAwMCAxMjQuMzM2IDMzMC40NzMgQyAxNjcuOTY5IDM1Ni43NDgsMjMyLjAzMSAzNTYuNzQ4LDI3NS42NjQgMzMwLjQ3MyBMIDI4NC43NTQgMzI1LjAwMCAzMDQuODc3IDMyNS4wMDAgTCAzMjUuMDAwIDMyNS4wMDAgMzI1LjAwMCAzMDQuODc3IEwgMzI1LjAwMCAyODQuNzU0IDMzMC40NzMgMjc1LjY2NCBDIDM1Ni43NDggMjMyLjAzMSwzNTYuNzQ4IDE2Ny45NjksMzMwLjQ3MyAxMjQuMzM2IEwgMzI1LjAwMCAxMTUuMjQ2IDMyNS4wMDAgOTUuMTIzIEwgMzI1LjAwMCA3NS4wMDAgMzA0Ljg3NyA3NS4wMDAgTCAyODQuNzU0IDc1LjAwMCAyNzUuNjY0IDY5LjUyNyBDIDI0Ni45NTkgNTIuMjQxLDIwMi4zNjcgNDUuMjI4LDE2Ny45OTMgNTIuNTkzIE0yMjcuMDM1IDc5LjA3MSBDIDIzOC41MTEgODEuNjMxLDI2MC40ODYgOTAuOTkxLDI2OC42MjEgOTYuNzgzIEMgMjcyLjMzOSA5OS40MzEsMjc1LjUxNiAxMDAuMDAwLDI4Ni41NjkgMTAwLjAwMCBMIDMwMC4wMDAgMTAwLjAwMCAzMDAuMDAwIDExMy40MzEgQyAzMDAuMDAwIDEyNC40ODQsMzAwLjU2OSAxMjcuNjYxLDMwMy4yMTcgMTMxLjM3OSBDIDMyOS43MzQgMTY4LjYxOSwzMjkuNzM0IDIzMS4zODEsMzAzLjIxNyAyNjguNjIxIEMgMzAwLjU2OSAyNzIuMzM5LDMwMC4wMDAgMjc1LjUxNiwzMDAuMDAwIDI4Ni41NjkgTCAzMDAuMDAwIDMwMC4wMDAgMjg2LjU2OSAzMDAuMDAwIEMgMjc1LjUxNiAzMDAuMDAwLDI3Mi4zMzkgMzAwLjU2OSwyNjguNjIxIDMwMy4yMTcgQyAyMzEuMzgxIDMyOS43MzQsMTY4LjYxOSAzMjkuNzM0LDEzMS4zNzkgMzAzLjIxNyBDIDEyNy42NjEgMzAwLjU2OSwxMjQuNDg0IDMwMC4wMDAsMTEzLjQzMSAzMDAuMDAwIEwgMTAwLjAwMCAzMDAuMDAwIDEwMC4wMDAgMjg2LjU2OSBDIDEwMC4wMDAgMjc1LjUxNiw5OS40MzEgMjcyLjMzOSw5Ni43ODMgMjY4LjYyMSBDIDcwLjI2NiAyMzEuMzgxLDcwLjI2NiAxNjguNjE5LDk2Ljc4MyAxMzEuMzc5IEMgOTkuNDMxIDEyNy42NjEsMTAwLjAwMCAxMjQuNDg0LDEwMC4wMDAgMTEzLjQzMSBMIDEwMC4wMDAgMTAwLjAwMCAxMTMuNDMxIDEwMC4wMDAgQyAxMjQuMzgzIDEwMC4wMDAsMTI3LjY3NiA5OS40MjAsMTMxLjI3NiA5Ni44NTcgQyAxNTUuMzY2IDc5LjcwMywxOTYuMDE5IDcyLjE1MiwyMjcuMDM1IDc5LjA3MSBNMTE4LjMzOSAxNDQuNDM1IEMgMTI0LjQzOSAxNTEuMzY1LDEyNC40NTEgMTUxLjQwNSwxMzYuNDU1IDIwNS40NDcgQyAxNDkuNDUxIDI2My45NDgsMTQ5LjI5OSAyNjEuNzIzLDE0MC45MjMgMjcwLjcwMyBMIDEzNi45MTUgMjc1LjAwMCAxNjIuMjA4IDI3NS4wMDAgQyAxNzYuMTE4IDI3NS4wMDAsMTg3LjUwMCAyNzQuNjE0LDE4Ny41MDAgMjc0LjE0MSBDIDE4Ny41MDAgMjczLjY2OSwxODYuMzkwIDI3MS4xMzYsMTg1LjAzNCAyNjguNTE0IEMgMTgyLjc1MyAyNjQuMTAzLDE5Ni40MzMgMjE3LjIxNSwyMDAuMDAwIDIxNy4yMTUgQyAyMDMuNTY3IDIxNy4yMTUsMjE3LjI0NyAyNjQuMTAzLDIxNC45NjYgMjY4LjUxNCBDIDIxMy42MTAgMjcxLjEzNiwyMTIuNTAwIDI3My42NjksMjEyLjUwMCAyNzQuMTQxIEMgMjEyLjUwMCAyNzQuNjE0LDIyMy44ODIgMjc1LjAwMCwyMzcuNzkyIDI3NS4wMDAgTCAyNjMuMDg1IDI3NS4wMDAgMjU5LjA3NyAyNzAuNzAzIEMgMjUwLjcwMSAyNjEuNzIzLDI1MC41NDkgMjYzLjk0OCwyNjMuNTQ1IDIwNS40NDcgQyAyNzUuNTQ5IDE1MS40MDUsMjc1LjU2MSAxNTEuMzY1LDI4MS42NjEgMTQ0LjQzNSBMIDI4Ny43NjYgMTM3LjUwMCAyNjIuNjMzIDEzNy41MDAgQyAyNDguODEwIDEzNy41MDAsMjM3LjUwMCAxMzcuODI4LDIzNy41MDAgMTM4LjIyOCBDIDIzNy41MDAgMTM4LjYyOSwyMzguNjk3IDE0MS40NjYsMjQwLjE2MCAxNDQuNTM0IEMgMjQyLjc2NCAxNDkuOTk1LDIzMS4zOTYgMjA3LjQ5MCwyMjguMTEzIDIwNS40NjEgQyAyMjcuNTg0IDIwNS4xMzQsMjIxLjI5MyAxODkuNzA5LDIxNC4xMzMgMTcxLjE4MyBDIDIwNi45NzQgMTUyLjY1NywyMDAuNjE0IDEzNy41MDAsMjAwLjAwMCAxMzcuNTAwIEMgMTk5LjM4NiAxMzcuNTAwLDE5My4wMjYgMTUyLjY1NywxODUuODY3IDE3MS4xODMgQyAxNzguNzA3IDE4OS43MDksMTcyLjQxNiAyMDUuMTM0LDE3MS44ODcgMjA1LjQ2MSBDIDE2OC42MDQgMjA3LjQ5MCwxNTcuMjM2IDE0OS45OTUsMTU5Ljg0MCAxNDQuNTM0IEMgMTYxLjMwMyAxNDEuNDY2LDE2Mi41MDAgMTM4LjYyOSwxNjIuNTAwIDEzOC4yMjggQyAxNjIuNTAwIDEzNy44MjgsMTUxLjE5MCAxMzcuNTAwLDEzNy4zNjcgMTM3LjUwMCBMIDExMi4yMzQgMTM3LjUwMCAxMTguMzM5IDE0NC40MzUgIiBzdHJva2U9Im5vbmUiIGZpbGw9IiNhN2FhYWQiIGZpbGwtcnVsZT0iZXZlbm9kZCI+PC9wYXRoPjwvZz48L3N2Zz4=');

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
