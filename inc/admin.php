<?php
/**
 * Guild Recruitment Widget for WoW Admin Page
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_init', 'athlios_wow_recruit_options_init');
add_action('admin_menu', 'athlios_wow_recruit_options_add_page');
add_action('admin_enqueue_scripts', 'athlios_wow_recruit_admin_enqueue_assets');

function athlios_wow_recruit_get_default_options()
{
    return array(
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
}

function athlios_wow_recruit_options_init()
{
    register_setting('athlios_wow_recruit_options_options', 'athlios_wow_recruit', 'athlios_wow_recruit_options_validate');
}

function athlios_wow_recruit_options_add_page()
{
    add_menu_page(
        __('Guild Recruitment Widget for WoW Options', 'guild-recruitment-widget-for-wow'),
        __('Guild Recruitment', 'guild-recruitment-widget-for-wow'),
        'manage_options',
        'athlios_wow_recruit_options',
        'athlios_wow_recruit_options_do_page',
        ATHLIOS_WOW_RECRUIT_MENU_ICON,
        58.2
    );
}

function athlios_wow_recruit_admin_enqueue_assets($hook_suffix)
{
    if ('toplevel_page_athlios_wow_recruit_options' !== $hook_suffix) {
        return;
    }

    wp_enqueue_style(
        'athlios-wrz-admin-settings',
        plugins_url('css/admin-settings.css', ATHLIOS_WOW_RECRUIT_FILE),
        array(),
        '2.1'
    );
    wp_enqueue_script(
        'athlios-wrz-admin-settings',
        plugins_url('js/admin-settings.js', ATHLIOS_WOW_RECRUIT_FILE),
        array(),
        '2.1',
        true
    );
}

function athlios_wow_recruit_get_logo_svg_markup()
{
    $svg = base64_decode((string) substr(ATHLIOS_WOW_RECRUIT_MENU_ICON, strpos(ATHLIOS_WOW_RECRUIT_MENU_ICON, ',') + 1), true);

    return is_string($svg) ? $svg : '';
}

function athlios_wow_recruit_get_svg_kses_allowed_html()
{
    return array(
        'svg' => array(
            'id' => true,
            'version' => true,
            'xmlns' => true,
            'xmlns:xlink' => true,
            'width' => true,
            'height' => true,
            'viewbox' => true,
            'viewBox' => true,
        ),
        'g' => array(
            'id' => true,
        ),
        'path' => array(
            'id' => true,
            'd' => true,
            'stroke' => true,
            'fill' => true,
            'fill-rule' => true,
        ),
    );
}

function athlios_wow_recruit_options_do_page()
{
    $options = get_option('athlios_wow_recruit', array());
    if (!is_array($options)) {
        $options = array();
    }
    $options = wp_parse_args($options, athlios_wow_recruit_get_default_options());

    $themes = array(
        __('Plugin Default (36px)', 'guild-recruitment-widget-for-wow') => '',
        __('Small Icons (25px)', 'guild-recruitment-widget-for-wow') => 'small',
        __('Huge Icons (56px)', 'guild-recruitment-widget-for-wow') => 'large',
    );
    ?>
    <div class="wrap athlios-wrz-admin-page">
        <div class="athlios-wrz-admin-hero">
            <span class="athlios-wrz-admin-hero__logo" aria-hidden="true"><?php echo wp_kses(athlios_wow_recruit_get_logo_svg_markup(), athlios_wow_recruit_get_svg_kses_allowed_html()); ?></span>
            <div class="athlios-wrz-admin-hero__content">
                <h1><?php esc_html_e('Guild Recruitment Widget for WoW', 'guild-recruitment-widget-for-wow'); ?></h1>
                <p><?php esc_html_e('Configure class labels, recruitment status text, and icon size for your sidebar recruitment widget.', 'guild-recruitment-widget-for-wow'); ?></p>
            </div>
        </div>

        <?php settings_errors('athlios_wow_recruit'); ?>

        <p>
            <a href="<?php echo esc_url(ATHLIOS_WOW_RECRUIT_HELP_URL); ?>" target="_blank" rel="noopener noreferrer">
                <img style="vertical-align: middle;" src="<?php echo esc_url(ATHLIOS_WOW_RECRUIT_INFO_ICON_URL); ?>" alt="<?php esc_attr_e('View more info', 'guild-recruitment-widget-for-wow'); ?>" />
                <?php esc_html_e('Plugin Documentation', 'guild-recruitment-widget-for-wow'); ?>
            </a>
            &nbsp;|
            <a href="<?php echo esc_url(ATHLIOS_WOW_RECRUIT_BUG_URL); ?>" target="_blank" rel="noopener noreferrer">
                <img style="vertical-align: middle;" src="<?php echo esc_url(ATHLIOS_WOW_RECRUIT_BUG_ICON_URL); ?>" alt="<?php esc_attr_e('Report bugs', 'guild-recruitment-widget-for-wow'); ?>" />
                <?php esc_html_e('Report Bugs', 'guild-recruitment-widget-for-wow'); ?>
            </a>
        </p>

        <form method="post" action="options.php" class="athlios-wrz-admin-form">
            <?php settings_fields('athlios_wow_recruit_options_options'); ?>
            <div class="athlios-wrz-admin-tabs" role="tablist" aria-label="<?php esc_attr_e('Guild Recruitment Widget for WoW settings', 'guild-recruitment-widget-for-wow'); ?>">
                <button type="button" class="athlios-wrz-admin-tab is-active" data-tab="theme"><?php esc_html_e('Theme', 'guild-recruitment-widget-for-wow'); ?></button>
                <button type="button" class="athlios-wrz-admin-tab" data-tab="status"><?php esc_html_e('Status', 'guild-recruitment-widget-for-wow'); ?></button>
                <button type="button" class="athlios-wrz-admin-tab" data-tab="classes"><?php esc_html_e('Classes', 'guild-recruitment-widget-for-wow'); ?></button>
            </div>

            <section class="athlios-wrz-admin-panel is-active" data-panel="theme">
                <div class="athlios-wrz-admin-card">
                    <div class="athlios-wrz-admin-field-grid athlios-wrz-admin-field-grid--list">
                        <div class="athlios-wrz-admin-field">
                            <label class="athlios-wrz-admin-field__label" for="athlios-wrz-theme"><?php esc_html_e('Icon Size', 'guild-recruitment-widget-for-wow'); ?></label>
                            <select id="athlios-wrz-theme" name="athlios_wow_recruit[theme]">
                                <?php foreach ($themes as $label => $value) : ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php selected($options['theme'], $value); ?>>
                                        <?php echo esc_html($label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php esc_html_e('Choose the icon size used by the recruitment widget.', 'guild-recruitment-widget-for-wow'); ?></p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="athlios-wrz-admin-panel" data-panel="status" hidden>
                <div class="athlios-wrz-admin-card">
                    <div class="athlios-wrz-admin-field-grid athlios-wrz-admin-field-grid--list">
                        <div class="athlios-wrz-admin-field">
                            <label class="athlios-wrz-admin-field__label" for="athlios-wrz-status-high"><?php esc_html_e('High', 'guild-recruitment-widget-for-wow'); ?></label>
                            <input id="athlios-wrz-status-high" type="text" name="athlios_wow_recruit[status3]" value="<?php echo esc_attr($options['status3']); ?>" />
                            <p class="description"><?php esc_html_e('Text shown for high-priority recruitment rows.', 'guild-recruitment-widget-for-wow'); ?></p>
                        </div>
                        <div class="athlios-wrz-admin-field">
                            <label class="athlios-wrz-admin-field__label" for="athlios-wrz-status-medium"><?php esc_html_e('Medium', 'guild-recruitment-widget-for-wow'); ?></label>
                            <input id="athlios-wrz-status-medium" type="text" name="athlios_wow_recruit[status2]" value="<?php echo esc_attr($options['status2']); ?>" />
                            <p class="description"><?php esc_html_e('Text shown for medium-priority recruitment rows.', 'guild-recruitment-widget-for-wow'); ?></p>
                        </div>
                        <div class="athlios-wrz-admin-field">
                            <label class="athlios-wrz-admin-field__label" for="athlios-wrz-status-closed"><?php esc_html_e('Closed', 'guild-recruitment-widget-for-wow'); ?></label>
                            <input id="athlios-wrz-status-closed" type="text" name="athlios_wow_recruit[status0]" value="<?php echo esc_attr($options['status0']); ?>" />
                            <p class="description"><?php esc_html_e('Text shown for closed recruitment rows.', 'guild-recruitment-widget-for-wow'); ?></p>
                        </div>
                        <div class="athlios-wrz-admin-field">
                            <span class="athlios-wrz-admin-field__label"><?php esc_html_e('Closed Rows', 'guild-recruitment-widget-for-wow'); ?></span>
                            <label class="athlios-wrz-admin-checkbox">
                                <input name="athlios_wow_recruit[display_closed]" type="checkbox" value="1" <?php checked(1, (int) $options['display_closed']); ?> />
                                <span><?php esc_html_e('Show closed rows in the widget', 'guild-recruitment-widget-for-wow'); ?></span>
                            </label>
                            <p class="description"><?php esc_html_e('Leave this off to hide classes marked as closed.', 'guild-recruitment-widget-for-wow'); ?></p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="athlios-wrz-admin-panel" data-panel="classes" hidden>
                <div class="athlios-wrz-admin-card">
                    <div class="athlios-wrz-admin-field-grid athlios-wrz-admin-field-grid--classes">
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class0"><?php esc_html_e('Death Knight', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class0" type="text" name="athlios_wow_recruit[class0]" value="<?php echo esc_attr($options['class0']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class11"><?php esc_html_e('Demon Hunter', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class11" type="text" name="athlios_wow_recruit[class11]" value="<?php echo esc_attr($options['class11']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class1"><?php esc_html_e('Druid', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class1" type="text" name="athlios_wow_recruit[class1]" value="<?php echo esc_attr($options['class1']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class12"><?php esc_html_e('Evoker', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class12" type="text" name="athlios_wow_recruit[class12]" value="<?php echo esc_attr($options['class12']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class3"><?php esc_html_e('Hunter', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class3" type="text" name="athlios_wow_recruit[class3]" value="<?php echo esc_attr($options['class3']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class7"><?php esc_html_e('Mage', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class7" type="text" name="athlios_wow_recruit[class7]" value="<?php echo esc_attr($options['class7']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class10"><?php esc_html_e('Monk', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class10" type="text" name="athlios_wow_recruit[class10]" value="<?php echo esc_attr($options['class10']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class2"><?php esc_html_e('Paladin', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class2" type="text" name="athlios_wow_recruit[class2]" value="<?php echo esc_attr($options['class2']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class5"><?php esc_html_e('Priest', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class5" type="text" name="athlios_wow_recruit[class5]" value="<?php echo esc_attr($options['class5']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class4"><?php esc_html_e('Rogue', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class4" type="text" name="athlios_wow_recruit[class4]" value="<?php echo esc_attr($options['class4']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class6"><?php esc_html_e('Shaman', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class6" type="text" name="athlios_wow_recruit[class6]" value="<?php echo esc_attr($options['class6']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class8"><?php esc_html_e('Warlock', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class8" type="text" name="athlios_wow_recruit[class8]" value="<?php echo esc_attr($options['class8']); ?>" /></div>
                        <div class="athlios-wrz-admin-field"><label class="athlios-wrz-admin-field__label" for="athlios-wrz-class9"><?php esc_html_e('Warrior', 'guild-recruitment-widget-for-wow'); ?></label><input id="athlios-wrz-class9" type="text" name="athlios_wow_recruit[class9]" value="<?php echo esc_attr($options['class9']); ?>" /></div>
                    </div>
                </div>
            </section>

            <?php submit_button(__('Save Changes', 'guild-recruitment-widget-for-wow')); ?>
        </form>
    </div>
    <?php
}

function athlios_wow_recruit_options_validate($input)
{
    if (!is_array($input)) {
        $input = array();
    }

    $defaults = athlios_wow_recruit_get_default_options();
    $input = wp_parse_args($input, $defaults);

    $text_fields = array(
        'class0', 'class1', 'class2', 'class3', 'class4', 'class5',
        'class6', 'class7', 'class8', 'class9', 'class10', 'class11', 'class12',
        'status0', 'status2', 'status3',
    );

    foreach ($text_fields as $key) {
        $input[$key] = sanitize_text_field($input[$key]);
    }

    $input['status1'] = $input['status2']; // keep legacy key aligned with Medium

    $allowed_themes = array('', 'small', 'large');
    $input['theme'] = in_array($input['theme'], $allowed_themes, true) ? $input['theme'] : '';
    unset($input['custom_style']);
    $input['display_closed'] = !empty($input['display_closed']) ? 1 : 0;

    return $input;
}
