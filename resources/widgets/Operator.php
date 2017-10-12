<?php


/**
 * Class Operator_Widget. This widget let you decide which card operator should be displayed.
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
        echo view('com.detalhe.core.widgets.contact');
    }

    /**
     * Widget Backend
     *
     * @since 1.0.0
     * @param array $instance
     * @return string Admin form
     */
    public function form( $instance ) {

        // Default operators
        $operators = array(
            'mastercard'       => 'Mastercard',
            'visa'             => 'Visa',
            'american-express' => 'American Express',
            'paypal'           => 'PayPal'
        );

        // Check if there are already operators in the instance, if not, save them for display
        $instance = !empty($instance) ? $instance : $operators;

        foreach ( $instance as $item => $value ) {
            $title = ! empty( $value ) ? $value : esc_html__( "New item", 'Operator_Widget' );
            ?>
            <p>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $item ) ); ?>" name="<?php echo $value; ?>" type="checkbox" <?php checked( $instance[ $item ], $value ); ?> value="<?php echo $value; ?>">
                <label for="<?php echo esc_attr( $this->get_field_id( $item ) ); ?>"><?php esc_attr_e( $title, 'text_domain' ); ?></label>
            </p>
            <?php
        }
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

        foreach ( $new_instance as $key => $value ) {
            $instance[ $key ] = $value;
        }
        return $instance;
    }
}