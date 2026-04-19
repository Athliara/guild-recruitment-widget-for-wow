<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Guild Recruitment Widget for WoW class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.
 *
 * @since 1.0
 */
class Athlios_Wow_Recruit_Widget extends WP_Widget
{
    private static function get_default_row_classes()
    {
        return array(
            'deathknight',
            'demonhunter',
            'druid',
            'evoker',
            'hunter',
            'mage',
            'monk',
            'paladin',
            'priest',
            'rogue',
            'shaman',
            'warlock',
            'warrior',
        );
    }

    private static function get_default_row_class($index)
    {
        $default_classes = self::get_default_row_classes();

        return isset($default_classes[$index]) ? $default_classes[$index] : 'deathknight';
    }


    /**
     * Widget setup.
     */
    public function __construct()
    {
        /* Widget settings. */
        $widget_ops = array(
            'classname' => 'guild-recruitment-widget-for-wow',
            'description' => __('Displays your guild\'s recruitment status.', 'guild-recruitment-widget-for-wow')
        );

        /* Widget control settings. */
        $control_ops = array('width' => 500, 'height' => 600, 'id_base' => 'guild-recruitment-widget-for-wow');

        /* Create the widget. */
        parent::__construct('guild-recruitment-widget-for-wow', __('Guild Recruitment Widget for WoW', 'guild-recruitment-widget-for-wow'), $widget_ops, $control_ops);
    }

    /**
     * How to display the widget on the screen.
     */
    public function widget($args, $instance)
    {

        global $athlios_wow_recruit_status;
        global $athlios_wow_recruit_class;
        global $athlios_wow_recruit_display_closed;
        global $athlios_wow_recruit_theme;
        /* global $wr_max_row; */
        // Avoid extract() on modern PHP/WP; map known wrapper args explicitly.
        $before_widget = isset($args['before_widget']) ? $args['before_widget'] : '';
        $after_widget = isset($args['after_widget']) ? $args['after_widget'] : '';
        $before_title = isset($args['before_title']) ? $args['before_title'] : '';
        $after_title = isset($args['after_title']) ? $args['after_title'] : '';
        /* Our variables from the widget settings. */
        $instance = wp_parse_args((array)$instance, array(
            'title' => '',
            'title_url' => '',
            'wr_width' => '100%',
            'message' => '',
            'wr_tooltip' => '[class]',
            'wr_max_row' => 13,
        ));

        $title = apply_filters('widget_title', $instance['title']);
        /* add in a title url */
        $title_url = $instance['title_url'];

        /* settable width
        * @since 1.4
        */
        $wr_width = $instance['wr_width'];
        /* recruitment message
        * @since 1.2
        */
        $message = $instance['message'];

        /* custom tooltip
        * @since 1.3
        */
        $wr_tooltip = $instance['wr_tooltip'];
        /* custom number of rows
        * @since 1.2
        */
        $wr_max_row = intval($instance['wr_max_row']);

        /* Before widget (defined by themes). */
        echo wp_kses_post($before_widget);


        /* Display the widget title if one was input (before and after defined by themes). */
        if ($title) {
            if ($title_url) {
                echo wp_kses_post($before_title);
                ?>

                <a href="<?php echo esc_url($title_url); ?>"><?php echo esc_html($title); ?> </a>
                <?php
                echo wp_kses_post($after_title);
            } else {
                echo wp_kses_post($before_title) . esc_html($title) . wp_kses_post($after_title);
            }
        }

        $wr_data = array();
        for ($r = 0; $r < $wr_max_row; $r++) {
            if (!isset($instance['wr_row_' . $r . '_class'])) {
                $instance['wr_row_' . $r . '_class'] = self::get_default_row_class($r);
            }
            if (!isset($instance['wr_row_' . $r . '_status'])) {
                $instance['wr_row_' . $r . '_status'] = 0;
            }
            if (!isset($instance['wr_row_' . $r . '_note'])) {
                $instance['wr_row_' . $r . '_note'] = '';
            }
        }
//prepare for sorting machanim
        for ($r = 0; $r < $wr_max_row; $r++) {
            $row_class = isset($instance['wr_row_' . $r . '_class']) ? (string) $instance['wr_row_' . $r . '_class'] : self::get_default_row_class($r);
            $row_status = isset($instance['wr_row_' . $r . '_status']) ? (int) $instance['wr_row_' . $r . '_status'] : 0;
            $row_note = isset($instance['wr_row_' . $r . '_note']) ? (string) $instance['wr_row_' . $r . '_note'] : '';

            if ($athlios_wow_recruit_display_closed) {
                if ($row_status > -1) {
                    $wr_data[] = array(
                        'status' => $row_status,
                        'class' => $row_class,
                        'note' => $row_note,
                    );
                }
            } else {
                if ($row_status > 0) {
                    $wr_data[] = array(
                        'status' => $row_status,
                        'class' => $row_class,
                        'note' => $row_note,
                    );
                }
            }
        }

// Sort recruitment rows by status and class.
        if (!empty($wr_data)) {
            usort($wr_data, array(__CLASS__, 'athlios_wow_recruit_sort_rows'));
        }

        /**
         * Frontend Start
         */
        ?>
        <div class="wr-clear"></div>
        <div
            class="wow-recruit-widget <?php echo esc_attr($athlios_wow_recruit_theme ? 'wr-' . $athlios_wow_recruit_theme : 'wr-normal'); ?>" <?php if ($title_url) {
            ?>
            onclick="location.href='<?php echo esc_js($title_url); ?>';"
            style="cursor: pointer;" <?php } ?>>
            <?php
            if ($message) {
                ?>
                <div class="wr-message">
                    <?php echo wp_kses_post($message); ?>
                </div>
                <?php
            }
            ?>
            <div class="wr-container">

                <?php
                if (!empty($wr_data)) {
                    $even = false;
                    foreach ($wr_data as $k => $v) {
                        $row_class_slug = sanitize_html_class($v['class']);
                        $row_note_slug = sanitize_html_class(strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $v['note'])));
                        $row_status_num = (int) $v['status'];
                        ?>
                        <div
                            class="wr-item wr-<?php echo esc_attr($even ? 'even' : 'odd'); ?> wr-<?php echo esc_attr($row_class_slug); ?> wr-<?php echo esc_attr($row_note_slug); ?> wr-status<?php echo esc_attr($row_status_num); ?>"
                            title="<?php
                            /**
                             * advanced tooltip
                             * @since 1.3                       *
                             */
                            $tooltiptemp = $wr_tooltip;
                            $tooltiptemp = str_replace("[class]", isset($athlios_wow_recruit_class[$v['class']]) ? $athlios_wow_recruit_class[$v['class']] : $v['class'], $tooltiptemp);
                            $tooltiptemp = str_replace("[status]", isset($athlios_wow_recruit_status[$v['status']]) ? $athlios_wow_recruit_status[$v['status']] : '', $tooltiptemp);
                            $tooltiptemp = str_replace("[note]", $v['note'], $tooltiptemp);
                            echo esc_attr($tooltiptemp);
                            ?>" style="width:<?php echo esc_attr($wr_width); ?>">
                            <div class="wr-left">
                                <div class="wr-icon wr-<?php echo esc_attr($row_class_slug); ?>"></div>
                            </div>
                            <div class="wr-right">
                                <div class="wr-class-text wr-<?php echo esc_attr($row_class_slug); ?>">
                                    <?php echo esc_html(isset($athlios_wow_recruit_class[$v['class']]) ? $athlios_wow_recruit_class[$v['class']] : $v['class']); ?>
                                </div>
                                <div class="wr-status wr-status<?php echo esc_attr($row_status_num); ?>">
                                    <?php echo esc_html(isset($athlios_wow_recruit_status[$v['status']]) ? $athlios_wow_recruit_status[$v['status']] : ''); ?>
                                </div>
                                <?php
                                if ($v['note']) {
                                    ?>
                                    <div
                                        class="wr-note wr-<?php echo esc_attr($row_note_slug); ?>">
                                        <?php echo esc_html($v['note']); ?>
                                    </div>
                                    <?php
                                }
                                ?>


                            </div>
                        </div>


                        <?php
                        $even = !$even;
                    }
                }
                ?>


            </div>
            <div class="wr-clear"></div>
        </div>


        <?php
        /**
         *  Frontend End
         */
        /* After widget (defined by themes). */
        echo wp_kses_post($after_widget);
        ?>

        <?php
    }


    /**
     * Stable sort by status (desc), then class key (asc).
     */
    private static function athlios_wow_recruit_sort_rows($a, $b)
    {
        $a_status = isset($a['status']) ? (int) $a['status'] : 0;
        $b_status = isset($b['status']) ? (int) $b['status'] : 0;

        if ($a_status === $b_status) {
            $a_class = isset($a['class']) ? (string) $a['class'] : '';
            $b_class = isset($b['class']) ? (string) $b['class'] : '';
            return strcmp($a_class, $b_class);
        }

        return ($a_status > $b_status) ? -1 : 1;
    }

    /**
     * Update the widget settings.
     */
    public function update($new_instance, $old_instance)
    {

        global $athlios_wow_recruit_class;

        $instance = is_array($old_instance) ? $old_instance : array();
        $wr_max_row = isset($instance['wr_max_row']) ? intval($instance['wr_max_row']) : 13;

        $instance['wr_id'] = sanitize_title(isset($new_instance['wr_id']) ? $new_instance['wr_id'] : '');
        $instance['wr_max_row'] = isset($new_instance['wr_max_row']) ? intval($new_instance['wr_max_row']) : 13;
        $wr_max_row = $instance['wr_max_row'];
        $instance['wr_tooltip'] = sanitize_text_field(isset($new_instance['wr_tooltip']) ? $new_instance['wr_tooltip'] : '[class]');

        $new_width = isset($new_instance['wr_width']) ? sanitize_text_field($new_instance['wr_width']) : '100%';
        $instance['wr_width'] = preg_match('/^(auto|[0-9]+(px|%|em|rem|vw))$/', $new_width) ? $new_width : '100%';

        $instance['title'] = sanitize_text_field(isset($new_instance['title']) ? $new_instance['title'] : '');
        $instance['title_url'] = esc_url_raw(isset($new_instance['title_url']) ? $new_instance['title_url'] : '');
        $instance['message'] = isset($new_instance['message']) ? wp_kses_post($new_instance['message']) : '';

        foreach ($athlios_wow_recruit_class as $k => $v) {
            unset($instance[$k . '_status']);
            unset($instance[$k . '_note']);
        }

        $allowed_classes = array_keys($athlios_wow_recruit_class);
        for ($r = 0; $r < $wr_max_row; $r++) {
            $class_key = isset($new_instance['wr_row_' . $r . '_class']) ? sanitize_key($new_instance['wr_row_' . $r . '_class']) : self::get_default_row_class($r);
            if (!in_array($class_key, $allowed_classes, true)) {
                $class_key = self::get_default_row_class($r);
            }

            $status = isset($new_instance['wr_row_' . $r . '_status']) ? intval($new_instance['wr_row_' . $r . '_status']) : 0;
            if (!in_array($status, array(0, 2, 3), true)) {
                $status = 2;
            }

            $instance['wr_row_' . $r . '_class'] = $class_key;
            $instance['wr_row_' . $r . '_status'] = $status;
            $instance['wr_row_' . $r . '_note'] = sanitize_text_field(isset($new_instance['wr_row_' . $r . '_note']) ? $new_instance['wr_row_' . $r . '_note'] : '');
        }


        return $instance;
    }

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
    public function form($instance)
    {

        global $athlios_wow_recruit_status;
        global $athlios_wow_recruit_class;

        $defaults = array(
            'title' => '',
            'title_url' => '',
            'message' => '',
            'wr_max_row' => '13',
            'wr_tooltip' => '[class]',
            'wr_width' => '100%',
        );
        $instance = wp_parse_args((array)$instance, $defaults);

        $wr_max_row = isset($instance['wr_max_row']) ? intval($instance['wr_max_row']) : 13;

        $r = 0;
        foreach ($athlios_wow_recruit_class as $k => $v) {
            $legacy_status = isset($instance[$k . '_status']) ? $instance[$k . '_status'] : '';
            $legacy_note = isset($instance[$k . '_note']) ? $instance[$k . '_note'] : '';
            if ($legacy_status || $legacy_note) {
                $instance['wr_row_' . $r . '_class'] = $k;
                $instance['wr_row_' . $r . '_status'] = $legacy_status;
                $instance['wr_row_' . $r . '_note'] = $legacy_note;
                $r++;
            }
        }
        ?>

        <div style="float: right;">
            <a href="<?php echo esc_url(ATHLIOS_WOW_RECRUIT_HELP_URL); ?>" target="_blank" rel="noopener noreferrer"> <img
                    src="<?php echo esc_url(ATHLIOS_WOW_RECRUIT_INFO_ICON_URL); ?>" title="<?php esc_attr_e('More Info', 'guild-recruitment-widget-for-wow'); ?>"
                    alt="<?php esc_attr_e('View more info', 'guild-recruitment-widget-for-wow'); ?>"/>
            </a> <a href="<?php echo esc_url(ATHLIOS_WOW_RECRUIT_BUG_URL); ?>" target="_blank" rel="noopener noreferrer"> <img
                    src="<?php echo esc_url(ATHLIOS_WOW_RECRUIT_BUG_ICON_URL); ?>" title="<?php esc_attr_e('Report Bugs', 'guild-recruitment-widget-for-wow'); ?>"
                    alt="<?php esc_attr_e('Report bugs', 'guild-recruitment-widget-for-wow'); ?>"/>
            </a>
        </div>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title (optional):', 'guild-recruitment-widget-for-wow'); ?>
            </label> <input type="text"
                            id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                            name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                            value="<?php echo esc_attr($instance['title']); ?>" style="width: 100%;"/>
        </p>
        <p>
            <label
                for="<?php echo esc_attr($this->get_field_id('title_url')); ?>"><?php esc_html_e('Recruitment Page URL (optional, if not empty please use full url):', 'guild-recruitment-widget-for-wow'); ?>
            </label> <input type="text"
                            id="<?php echo esc_attr($this->get_field_id('title_url')); ?>"
                            name="<?php echo esc_attr($this->get_field_name('title_url')); ?>"
                            value="<?php echo esc_attr($instance['title_url']); ?>" style="width: 100%;"/>
        </p>

        <p>
            <label
                for="<?php echo esc_attr($this->get_field_id('message')); ?>"><?php esc_html_e('Recruitment Message (optional)', 'guild-recruitment-widget-for-wow'); ?>
            </label>
	<textarea rows="3" cols="1"
              id="<?php echo esc_attr($this->get_field_id('message')); ?>"
              name="<?php echo esc_attr($this->get_field_name('message')); ?>"
              style="width: 100%;"><?php echo esc_textarea($instance['message']); ?></textarea>
        </p>
        <p>


            <label for="<?php echo esc_attr($this->get_field_id('wr_max_row')); ?>"><?php esc_html_e('Number of rows:', 'guild-recruitment-widget-for-wow'); ?>
            </label> <input type="text"
                            id="<?php echo esc_attr($this->get_field_id('wr_max_row')); ?>"
                            name="<?php echo esc_attr($this->get_field_name('wr_max_row')); ?>"
                            value="<?php echo esc_attr($instance['wr_max_row']); ?>" style="width: 10%;"/>&nbsp;&nbsp;


            <label for="<?php echo esc_attr($this->get_field_id('wr_width')); ?>"><?php esc_html_e('Item width:', 'guild-recruitment-widget-for-wow'); ?>
            </label> <input type="text"
                            id="<?php echo esc_attr($this->get_field_id('wr_width')); ?>"
                            name="<?php echo esc_attr($this->get_field_name('wr_width')); ?>"
                            value="<?php echo esc_attr($instance['wr_width']); ?>" style="width: 50%;"/>


        </p>
        <p>
            <label
                for="<?php echo esc_attr($this->get_field_id('wr_tooltip')); ?>"><?php esc_html_e('Tooltip pattern:', 'guild-recruitment-widget-for-wow'); ?>
            </label>
            (<em><?php esc_html_e('Tokens available:', 'guild-recruitment-widget-for-wow'); ?></em>
            [class], [status], [note])
            <br/>
            <input type="text"
                   id="<?php echo esc_attr($this->get_field_id('wr_tooltip')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('wr_tooltip')); ?>"
                   value="<?php echo esc_attr($instance['wr_tooltip']); ?>" style="width: 50%;"/>

        </p>
        <table>
            <thead>
            <tr>
                <th style="text-align: left; width: 20%;"><?php esc_html_e('Class', 'guild-recruitment-widget-for-wow'); ?></th>
                <th style="text-align: left; width: 20%;"><?php esc_html_e('Status', 'guild-recruitment-widget-for-wow'); ?></th>
                <th style="text-align: left;"><?php esc_html_e('Note', 'guild-recruitment-widget-for-wow'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            for ($r = 0; $r < $wr_max_row; $r++) {
                $row_class = isset($instance['wr_row_' . $r . '_class']) ? $instance['wr_row_' . $r . '_class'] : self::get_default_row_class($r);
                $row_status = isset($instance['wr_row_' . $r . '_status']) ? $instance['wr_row_' . $r . '_status'] : '0';
                if ((string)$row_status === '1') {
                    $row_status = '2';
                }
                $row_note = isset($instance['wr_row_' . $r . '_note']) ? $instance['wr_row_' . $r . '_note'] : '';
                ?>
                <tr>
                    <td>
                        <select
                            id="<?php echo esc_attr($this->get_field_id('wr_row_' . $r . '_class')); ?>"
                            name="<?php echo esc_attr($this->get_field_name('wr_row_' . $r . '_class')); ?>">
                            <?php
                            foreach ($athlios_wow_recruit_class as $k => $v) {
                                ?>
                                <option <?php selected($k, $row_class); ?> value="<?php echo esc_attr($k); ?>">
                                    <?php echo esc_html($v); ?>
                                </option>
                                <?php
                            }
                            ?>

                        </select>
                    </td>
                    <td>
                        <select
                            id="<?php echo esc_attr($this->get_field_id('wr_row_' . $r . '_status')); ?>"
                            name="<?php echo esc_attr($this->get_field_name('wr_row_' . $r . '_status')); ?>">
                            <?php
                            foreach ($athlios_wow_recruit_status as $k => $v) {
                                if ((string)$k === '1') {
                                    continue;
                                }
                                ?>
                                <option <?php selected((string)$k, (string)$row_status); ?> value="<?php echo esc_attr($k); ?>">
                                    <?php echo esc_html($v); ?>
                                </option>

                                <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="text"
                               id="<?php echo esc_attr($this->get_field_id('wr_row_' . $r . '_note')); ?>"
                               name="<?php echo esc_attr($this->get_field_name('wr_row_' . $r . '_note')); ?>"
                               value="<?php echo esc_attr($row_note); ?>" style="width:100%"/>
                    </td>
                </tr>


                <?php
            }
            ?>
            </tbody>
        </table>

        <?php

    }

}
