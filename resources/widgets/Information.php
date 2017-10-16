<?php

/**
 * Class Information_Widget. This widget will display the information about payments accepted,
 * terms and conditions page and copyright shown.
 *
 * @since       1.0.0
 * @author      Yamil Elias <yamil@wtf.style>
 */
class Information_Widget extends WP_Widget
{
    /**
     * Information_Widget constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $params = [
            'description' => 'Display terms and conditions and copyright in a widget.',
            'name'        => 'Detalhe Information'
        ];

        parent::__construct('Information_Widget', '', $params);
    }

    /**
     * Creating widget front-end.
     *
     * @since 1.0.0
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $terms = apply_filters( 'widget_terms', $instance['terms'] );
        $copyright = apply_filters( 'widget_copyright', $instance['copyright'] );

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];

        $terms = ! empty($terms) ? $terms : '/terms-and-conditions';
        $copyright = ! empty($copyright) ? $copyright : 'Copyright 2017 &copy;';

        echo view('com.detalhe.core.widgets.information', [
                'copyright' => $copyright,
                'terms'     => $terms
        ]);

        echo $args['after_widget'];
    }

    /**
     * Widget Backend
     *
     * @since 1.0.0
     * @param array $instance
     * @return string Admin form
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'terms' ] ) ) {
            $terms = $instance[ 'terms' ];
        }
        else {
            $terms = __( 'Terms & Conditions', 'Information_Widget' );
        }

        if ( isset( $instance[ 'copyright' ] ) ) {
            $copyright = $instance[ 'copyright' ];
        }
        else {
            $copyright = __( 'Copyright', 'Information_Widget' );
        }

        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'terms' ); ?>"><?php _e( 'Terms & Conditions:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'terms' ); ?>" name="<?php echo $this->get_field_name( 'terms' ); ?>" type="text" value="/terms-and-conditions" />
            <small class="text-muted">Please add a relative path to the terms & conditions page. Example: '/terms-and-conditions' or '/pages/terms'</small>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'copyright' ); ?>"><?php _e( 'Copyright:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'copyright' ); ?>" name="<?php echo $this->get_field_name( 'copyright' ); ?>" type="text" value="<?php echo esc_attr( $copyright ); ?>" />
<!--            <small class="text-muted">Set your copyright message. Hint: to add copyright symbol (&#169;) please add this code: <code>--><?php //htmlentities('&copy;') ?><!--</code>.</code></small>-->
        </p>
        <?php
    }

    /**
     * Updating widget replacing old instances with new.
     *
     * @since 1.0.0
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['terms'] = ( ! empty( $new_instance['terms'] ) ) ? strip_tags( $new_instance['terms'] ) : '';
        $instance['copyright'] = ( ! empty( $new_instance['copyright'] ) ) ? strip_tags( $new_instance['copyright'] ) : '';

        return $instance;
    }
}