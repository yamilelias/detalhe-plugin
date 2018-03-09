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
            'description' => 'Ingresa los operadores financieros que deseas mostrar.',
            'name'        => 'Operadores Financieros'
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

        $text = ! empty($text) ? $text : 'Métodos de pago aceptados:';

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
            <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Texto' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="Métodos de pago aceptados:" />
            <small class="text-muted">Ingresa el texto que deseas que se muestre.</small>
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