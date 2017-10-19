<?php


/**
 * Class Operator_Widget. This widget let you decide which card operator should be displayed.
 * FIXME: Save the value from the checkbox or do it hardcoded
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
        echo $args['before_widget'];
        echo view('com.detalhe.core.widgets.operators');
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
        $instance['your_checkbox_var'] = 'on';
    ?>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance[ 'your_checkbox_var' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'your_checkbox_var' ); ?>" name="<?php echo $this->get_field_name( 'your_checkbox_var' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'your_checkbox_var' ); ?>">Label of your checkbox variable</label>
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
//        $instance[ 'mastercard' ] = $new_instance[ 'mastercard' ];
//        $instance[ 'visa' ] = $new_instance[ 'visa' ];
//        $instance[ 'american-express' ] = $new_instance[ 'american-express' ];
//        $instance[ 'paypal' ] = $new_instance[ 'paypal' ];

        $instance = $old_instance;
        // Add this line
        $instance[ 'your_checkbox_var' ] = !isset($new_instance[ 'your_checkbox_var' ]) ? $new_instance[ 'your_checkbox_var' ] : '';
//        $instance[ 'your_checkbox_var' ] = $new_instance[ 'your_checkbox_var' ];
        // Change 'your_checkbox_var' for your custom ID
        // ...
        return $instance;
    }
}