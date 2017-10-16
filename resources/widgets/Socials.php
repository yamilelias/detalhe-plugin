<?php

use Themosis\Facades\Field;

/**
 * Class Socials_Widget. Display the social media that the company have.
 *
 * @since       1.0.0
 * @author      Yamil Elias <yamil@wtf.style>
 */
class Socials_Widget extends WP_Widget
{
    /**
     * Information_Widget constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $params = [
            'description' => 'You have social media accounts? Show it to your customers!',
            'name'        => 'Social Media Icons'
        ];

        parent::__construct('Socials_Widget', '', $params);
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
        echo view('com.detalhe.core.widgets.socials');
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
        $facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : esc_html__( 'https://facebook.com/', 'Socials_Widget' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_attr_e( 'Facebook:', 'Socials_Widget' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>">
        </p>
        <?php

        $instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : esc_html__( 'https://instagram.com/', 'Socials_Widget' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_attr_e( 'Instagram:', 'Socials_Widget' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="text" value="<?php echo esc_attr( $instagram ); ?>">
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
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
}