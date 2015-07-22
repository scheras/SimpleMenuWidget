<?php
/**
 * Simple Menu Widget class.
 *
 * @author     Šimon Schierreich <admin@scheras.eu>
 * @since      0.1
 *
 * @package    WordPress
 * @subpackage SimpleMenuWidget
 *
 */
namespace ScheRas\Plugins\Widgets;

class Simple_Menu_Widget extends \WP_Widget
{

	public function __construct ()
	{

		parent::__construct ( 'simple-menu-widget', __ ( 'Simple Menu Widget', 'simple-menu' ), array ( 'description' => __ ( 'Displays custom links list.', 'simple-menu' ) ) );

	}

	/**
	 * Print the widget content
	 *
	 * @author Šimon Schierreich <admin@scheras.eu>
	 * @since  0.1
	 *
	 * @param  array $args     Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param  array $instance The settings for the particular instance of the widget.
	 * @return void
	 */
	public function widget ( $args, $instance )
	{
		?>
		<?php print $args[ 'before_widget' ]; ?>

		<?php $this->display_title ( $args, $instance ); ?>

		<ul class="simple-menu-widget">
			<?php foreach ( $instance[ 'items' ] as $item ) : ?>
				<?php $this->display_single_item ( $item[ 'title' ], $item[ 'link' ] ); ?>
			<?php endforeach; ?>
		</ul>

		<?php print $args[ 'after_widget' ]; ?>
		<?php
	}

	/**
	 * Output the settings update form
	 *
	 * @author Šimon Schierreich <admin@scheras.eu>
	 * @since  0.1
	 *
	 * @param  array  $instance Current settings.
	 * @return string           Default return is 'noform'.
	 */
	public function form ( $instance )
	{
		$title = ( isset ( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';
		$link_count = ( isset ( $instance[ 'links_count' ] ) ) ? $instance[ 'links_count' ] : 5;
		?>
			<p>
				<label for="<?php print esc_attr ( $this->get_field_id ( 'title' ) ); ?>"><?php esc_html_e ( 'Title:', 'simple-menu' ); ?>
					<input type="text" name="<?php print esc_attr ( $this->get_field_name ( 'title' ) ); ?>" id="<?php echo $this->get_field_id ( 'title' ); ?>"  class="widefat" value="<?php print esc_attr ( $title ) ?>" placeholder="<?php esc_attr_e ( 'Widget Title', 'simple-menu' ); ?>">
				</label>
				<label for="<?php print esc_attr ( $this->get_field_id ( 'links_count' ) ); ?>"><?php esc_html_e ( 'Links Count:', 'simple-menu' ) ?>
					<input type="number" step="1" min="1" max="10" name="<?php print esc_attr ( $this->get_field_name ( 'links_count' ) ); ?>" id="<?php print esc_attr ( $this->get_field_id ( 'links_count' ) ); ?>" class="widefat" value="<?php print esc_attr ( $link_count ); ?>">
				</label>
			</p>
			<?php for ( $i = 0; $i < $link_count; $i++ ) : ?>
				<?php $item_title = isset( $instance[ 'item_'.$i.'_title' ] ) ? $instance[ 'item_'.$i.'_title' ] : ''; ?>
				<?php $item_link = isset( $instance[ 'item_'.$i.'_link' ] ) ? $instance[ 'item_'.$i.'_link' ] : ''; ?>
				<p>
					<div style="text-align: center">
						<b><?php print esc_html ( sprintf ( __ ( 'Link #%d', 'simple-menu' ), $i + 1 ) ); ?></b>
					</div>
					<label for="<?php echo $this->get_field_id ( 'item_'.$i.'_title' ); ?>"><?php esc_html_e ( 'Link Title:', 'simple-menu' ) ?>
						<input type="text" name="<?php print esc_attr ( $this->get_field_name ( 'item_'.$i.'_title' ) ); ?>" id="<?php print esc_attr ( $this->get_field_id ( 'item_'.$i.'_title' ) ); ?>" class="widefat" value="<?php print esc_attr ( $item_title ); ?>" placeholder="<?php esc_attr_e ( 'Link Title', 'simple-menu' ) ?>">
					</label>
					<label for="<?php echo $this->get_field_id ( 'item_'.$i.'_link' ); ?>"><?php esc_html_e ( 'Link URL:', 'simple-menu' ) ?>
						<input type="url" name="<?php print esc_attr ( $this->get_field_name ( 'item_'.$i.'_link' ) ); ?>" id="<?php print esc_attr ( $this->get_field_id ( 'item_'.$i.'_link' ) ); ?>" class="widefat" value="<?php print esc_attr ( $item_link ); ?>" placeholder="<?php esc_attr_e ( 'Link URL', 'simple-menu' ) ?>">
					</label>
				</p>
			<?php endfor; ?>
		</table>
		<?php
	}

	/**
	 * Update a particular instance
	 *
	 * This function should check that $new_instance is set correctly. The newly-calculated
	 * value of `$instance` should be returned. If false is returned, the instance won't be
	 * saved/updated.
	 *
	 * @author Šimon Schierreich <admin@scheras.eu>
	 * @since  0.1
	 *
	 * @param  array $new New settings for this instance as input by the user via {@see WP_Widget::form()}.
	 * @param  array $old Old settings for this instance.
	 * @return array      Settings to save or bool false to cancel saving.
	 */
	public function update ( $new, $old )
	{
		$instance = array ();

		$instance[ 'title' ] = $new[ 'title' ];
		$instance[ 'links_count' ] = filter_var ( (int)$new[ 'links_count' ], FILTER_VALIDATE_INT, array ( 'options' => array ( 'min_range' => 0, 'max_range' => 10, 'default' => 5 ) ) );

		for ( $i = 0; $i < $old[ 'links_count' ]; $i++ ) {
			$instance[ 'item_'.$i.'_title' ] = sanitize_text_field ( $new[ 'item_'.$i.'_title' ] );
			$instance[ 'item_'.$i.'_link' ] = filter_var ( $new[ 'item_'.$i.'_link' ], FILTER_VALIDATE_URL );
		}

		return $instance;
	}

	/**
	 * Function used to register widget into WordPress
	 *
	 * @author Šimon Schierreich <admin@scheras.eu>
	 * @since  0.1
	 *
	 * @param  void
	 * @return void
	 */
	public static function register_widget ()
	{
		register_widget ( 'ScheRas\Plugins\Widgets\Simple_Menu_Widget' );
	}

	/**
	 * Displays widget title
	 *
	 * @author Šimon Schierreich <admin@scheras.eu>
	 * @since  0.1
	 *
	 * @param  array $args     Display arguments including before_title, after_title, before_widget, and after_widget.
	 * @param  array $instance The settings for the particular instance of the widget.
	 * @return void
	 */
	protected function display_title ( $args, $instance )
	{
		?>
		<?php if ( isset ( $instance[ 'title' ] ) && trim ( $instance[ 'title' ] ) !== '' ) : ?>
			<?php print $args[ 'before_title' ]; ?>
				<?php print esc_html ( trim ( $instance[ 'title' ] ) ) ?>
			<?php print $args[ 'after_title' ]; ?>
		<?php endif; ?>
		<?
	}

	/**
	 * Displays widget single item.
	 *
	 * @author Šimon Schierreich <admin@scheras.eu>
	 * @since  0.1
	 *
	 * @param  string $title Single item title.
	 * @param  string $link  Single item URL.
	 * @return void
	 */
	protected function display_single_item ( $title, $link )
	{
		?>
		<li>
			<a href="<?php print esc_attr ( esc_url ( $link ) ); ?>" title="<?php print esc_attr ( $title ); ?>"><?php print esc_html ( $title ); ?></a>
		</li>
		<?php
	}

}
