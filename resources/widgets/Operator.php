<?php


/**
 * Class Operator_Widget. This widget let you decide which card operator should be displayed.
 * TODO: Save the value from a checkbox to avoid hardcoding
 *
 * @since       1.0.0
 * @author      Yamil Elias <yamil@wtf.style>
 */
class Operator_Widget extends WP_Widget
{
    /**
     * Information_Widget constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $params = [
            'description' => 'Select the card operators you want to show.',
            'name'        => 'Card Operators'
        ];

        parent::__construct('Operator_Widget', '', $params);
    }

    /**
     * Creating widget front-end.
     *
     * @since 1.0.0
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $text = apply_filters( 'widget_text', $instance['text'] );

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];

        $text = ! empty($text) ? $text : 'Accepted payment methods:';

        echo view('com.detalhe.core.widgets.operators', [
            'text' => $text,
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
    ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Display Text' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="Accepted payment methods:" />
            <small class="text-muted">Fill the input with the text you want to show in the footer.</small>
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
        $instance = $old_instance;

        $instance['text'] = ( ! empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';

        return $instance;
    }
}