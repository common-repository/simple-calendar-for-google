<?php
/**
 * Simple Calendar for Google
 *
 * @package    Simple Calendar for Google
 * @subpackage SimpleCalendarForGoogleAdmin Management screen
/*
	Copyright (c) 2019- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$simplecalendarforgoogleadmin = new SimpleCalendarForGoogleAdmin();

/** ==================================================
 * Management screen
 */
class SimpleCalendarForGoogleAdmin {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'register_user_settings' ) );

		add_action( 'admin_menu', array( $this, 'add_pages' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ) );
		add_filter( 'plugin_action_links', array( $this, 'settings_link' ), 10, 2 );

		add_action( 'admin_notices', array( $this, 'change_settings' ) );
	}

	/** ==================================================
	 * Add a "Settings" link to the plugins page
	 *
	 * @param  array  $links  links array.
	 * @param  string $file   file.
	 * @return array  $links  links array.
	 * @since 1.00
	 */
	public function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty( $this_plugin ) ) {
			$this_plugin = 'simple-calendar-for-google/simplecalendarforgoogle.php';
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="' . admin_url( 'admin.php?page=simplecalendarforgoogle' ) . '">Simple Calendar for Google</a>';
			$links[] = '<a href="' . admin_url( 'admin.php?page=simplecalendarforgoogle-settings' ) . '">' . __( 'Settings' ) . '</a>';
		}
		return $links;
	}

	/** ==================================================
	 * Add page
	 *
	 * @since 1.00
	 */
	public function add_pages() {
		add_menu_page(
			'Simple Calendar for Google',
			'Simple Calendar for Google',
			'upload_files',
			'simplecalendarforgoogle',
			array( $this, 'manage_page' ),
			'dashicons-calendar-alt'
		);
		add_submenu_page(
			'simplecalendarforgoogle',
			__( 'Settings' ),
			__( 'Settings' ),
			'upload_files',
			'simplecalendarforgoogle-settings',
			array( $this, 'settings_page' )
		);
	}

	/** ==================================================
	 * Add Css and Script
	 *
	 * @since 1.00
	 */
	public function load_custom_wp_admin_style() {
		if ( $this->is_my_plugin_screen() ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'simplecalendarforgoogle-js', plugin_dir_url( __DIR__ ) . 'js/jquery.simplecalendarforgoogle.admin.js', array( 'jquery' ), '1.00', false );
			wp_enqueue_script( 'colorpicker-admin-js', plugin_dir_url( __DIR__ ) . 'js/jquery.colorpicker.admin.js', array( 'wp-color-picker' ), '1.0.0', false );
		}
	}

	/** ==================================================
	 * For only admin style
	 *
	 * @since 1.00
	 */
	private function is_my_plugin_screen() {
		$screen = get_current_screen();
		if ( is_object( $screen ) && 'simple-calendar-for-google_page_simplecalendarforgoogle-settings' == $screen->id ) {
			return true;
		} else {
			return false;
		}
	}

	/** ==================================================
	 * Manage page
	 *
	 * @since 1.00
	 */
	public function manage_page() {

		if ( ! current_user_can( 'upload_files' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$settings_html = '<a href="' . admin_url( 'admin.php?page=simplecalendarforgoogle-settings' ) . '" style="text-decoration: none; word-break: break-all;">' . __( 'Settings' ) . '</a>';

		?>
		<div class="wrap">

			<h2>Simple Calendar for Google
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=simplecalendarforgoogle-settings' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Settings' ); ?></a>
			</h2>

			<h3><?php esc_html_e( "Lists Google's public calendars.", 'simple-calendar-for-google' ); ?></h3>

			<details>
			<summary><strong><?php esc_html_e( 'Various links of this plugin', 'simple-calendar-for-google' ); ?></strong></summary>
			<?php $this->credit(); ?>
			</details>

			<div style="clear: both;"></div>

			<hr>
			<h3><?php esc_html_e( 'Set up a block', 'simple-calendar-for-google' ); ?></h3>
			<div style="padding: 5px 20px; font-weight: bold;"><?php esc_html_e( 'Single display', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 25px;"><?php esc_html_e( 'Can display it by entering the ID.', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 20px; font-weight: bold;"><?php esc_html_e( 'Can display calendars with different IDs together in chronological order.', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 25px;">
			<?php
			/* translators: Settings link */
			echo wp_kses_post( sprintf( __( 'If you do not specify an ID in the block, the calendar of all the IDs added to the %s will be displayed.', 'simple-calendar-for-google' ), $settings_html ) );
			?>
			</div>

			<h3><?php esc_html_e( 'Set up a shortcode', 'simple-calendar-for-google' ); ?></h3>
			<div style="padding: 5px 20px; font-weight: bold;"><?php esc_html_e( 'Single display', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 25px;"><?php esc_html_e( 'Can specify the ID individually and display it.', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 30px;"><?php esc_html_e( 'to the post or pages', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 40px;"><code>[scalg id="testcalendar@test.com" color="#000" bgcolor="#ddd"]</code></div>
			<div style="padding: 5px 30px;"><?php esc_html_e( 'to the template of the theme', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 40px;"><code>&lt;?php echo do_shortcode('[scalg id="testcalendar@test.com" color="#000" bgcolor="#ddd"]'); ?&gt</code></div>
			<div style="padding: 5px 30px;"><?php esc_html_e( 'attribute', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 40px;"><?php echo esc_html( __( 'Calendar' ) . ' ID' ); ?> : <code>id</code></div>
			<div style="padding: 5px 40px;"><?php echo esc_html( __( 'Text Color' ) ); ?> : <code>color</code></div>
			<div style="padding: 5px 40px;"><?php echo esc_html( __( 'Background Color' ) ); ?> : <code>bgcolor</code></div>
			<div style="padding: 5px 40px;"><?php echo esc_html( __( 'Upper limit of duplication', 'simple-calendar-for-google' ) ); ?> : <code>duplimit</code></div>
			<div style="padding: 5px 40px;"><?php echo esc_html( __( 'Background color when the upper limit of duplication is exceeded', 'simple-calendar-for-google' ) ); ?> : <code>dupcolor</code></div>
			<div style="padding: 5px 40px;"><?php echo esc_html( __( 'Alternate text for [Show Appointment (Time Frame Only)]', 'simple-calendar-for-google' ) ); ?> : <code>alttext</code></div>

			<div style="padding: 5px 20px; font-weight: bold;"><?php esc_html_e( 'Can display calendars with different IDs together in chronological order.', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 25px;">
			<?php
			/* translators: Settings link */
			echo wp_kses_post( sprintf( __( 'If you do not specify an ID in the shortcode, the calendar of all the IDs added to the %s will be displayed.', 'simple-calendar-for-google' ), $settings_html ) );
			?>
			</div>
			<div style="padding: 5px 30px;"><?php esc_html_e( 'to the post or pages', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 40px;"><code>[scalg]</code></div>
			<div style="padding: 5px 30px;"><?php esc_html_e( 'to the template of the theme', 'simple-calendar-for-google' ); ?></div>
			<div style="padding: 5px 40px;"><code>&lt;?php echo do_shortcode('[scalg]'); ?&gt</code></div>

		</div>
		<?php
	}

	/** ==================================================
	 * Settings page
	 *
	 * @since 1.00
	 */
	public function settings_page() {

		if ( ! current_user_can( 'upload_files' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		$this->options_updated();

		$scriptname = admin_url( 'admin.php?page=simplecalendarforgoogle-settings' );

		?>
		<div class="wrap">

			<h2>Simple Calendar for Google <a href="<?php echo esc_url( admin_url( 'admin.php?page=simplecalendarforgoogle-settings' ) ); ?>" style="text-decoration: none;"><?php esc_html_e( 'Settings' ); ?></a>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=simplecalendarforgoogle' ) ); ?>" class="page-title-action"><?php esc_html_e( 'How to use', 'simple-calendar-for-google' ); ?></a>
			</h2>
			<div style="clear: both;"></div>

			<div style="padding:10px;">
				<form style="padding:10px;" method="post" action="<?php echo esc_url( $scriptname ); ?>" />
					<?php wp_nonce_field( 'scalg_set', 'simplecalendarforgoogle_settings' ); ?>
					<h3><?php echo 'Google ' . esc_html__( 'Calendar' ) . ' API ' . esc_html__( 'Key' ); ?></h3>
					<div style="margin: 5px; padding: 5px;">
					<input type="text" name="api" style="width: 400px;" value="<?php echo esc_attr( get_user_option( 'scalg_api', get_current_user_id() ) ); ?>">
					<?php /* translators: API */ ?>
					<?php submit_button( sprintf( __( 'Save %s', 'simple-calendar-for-google' ), 'API' ), 'large', 'Saveapi', false ); ?>
					&nbsp;
					<?php /* translators: API */ ?>
					<?php submit_button( sprintf( __( 'Remove %s', 'simple-calendar-for-google' ), 'API' ), 'large', 'Removeapi', false ); ?>
					</div>
					<h3><?php echo 'Google ' . esc_html__( 'Calendar' ) . ' ID'; ?></h3>
					<h4><?php esc_html_e( 'If no value is specified for the block or shortcode "ID" attribute, a calendar will be generated from all the IDs added here.', 'simple-calendar-for-google' ); ?></h4>
					<div style="margin: 5px; padding: 5px;">ID : <input type="text" name="ids" style="width: 400px;">
					<?php /* translators: ID */ ?>
					<?php submit_button( sprintf( __( 'Add %s', 'simple-calendar-for-google' ), 'ID' ), 'large', 'Addids', false ); ?>
					</div>
					<?php
					if ( get_user_option( 'scalg_ids', get_current_user_id() ) ) {
						?>
						<div style="margin: 5px; padding: 5px;">
						<input type="checkbox" id="group_simple-calendar-for-google" class="simplecalendarforgoogle-checkAll"> <?php esc_html_e( 'Select all' ); ?>
						</div>
						<?php
						$ids = get_user_option( 'scalg_ids', get_current_user_id() );
						foreach ( $ids as $key => $id ) {
							?>
							<div style="margin: 5px; padding: 5px;">
							<input type="checkbox" name="del_ids[]" value="<?php echo esc_attr( $key ); ?>" class="group_simple-calendar-for-google" > <?php echo esc_html( $key . ' - ' . $id ); ?>
							</div>
							<?php
						}
					}
					?>
					<div style="margin: 5px; padding: 5px;">
					<?php /* translators: ID */ ?>
					<?php submit_button( sprintf( __( 'Delete %s', 'simple-calendar-for-google' ), 'ID' ), 'large', 'Deleteids', false ); ?>
					</div>
					<h3><?php esc_html_e( 'Color' ); ?></h3>
					<div style="margin: 5px; padding: 5px;">
					<?php esc_html_e( 'Text Color' ); ?> : 
					<input type="text" class="wpcolor" name="color" value="<?php echo esc_attr( get_user_option( 'scalg_color', get_current_user_id() ) ); ?>" size="10" />
					&nbsp;
					<?php esc_html_e( 'Background Color' ); ?> : 
					<input type="text" class="wpcolor" name="bgcolor" value="<?php echo esc_attr( get_user_option( 'scalg_bgcolor', get_current_user_id() ) ); ?>" size="10" />
					&nbsp;
					<?php /* translators: Color */ ?>
					<?php submit_button( sprintf( __( 'Save %s', 'simple-calendar-for-google' ), __( 'Color' ) ), 'large', 'Savecolor', false ); ?>
					</div>
					<h3><?php esc_html_e( 'Schedule Duplication', 'simple-calendar-for-google' ); ?></h3>
					<p class="description">
					<?php esc_html_e( 'When the schedule duplication exceeds the upper limit, changes the background color to the specified color.', 'simple-calendar-for-google' ); ?>
					</p>
					<div style="margin: 5px; padding: 5px;">
					<?php esc_html_e( 'Upper Limit', 'simple-calendar-for-google' ); ?> : <input type="number" name="duplimit" min="1" max="30" step="1" value="<?php echo esc_attr( get_user_option( 'scalg_duplimit', get_current_user_id() ) ); ?>">
					&nbsp;
					<?php esc_html_e( 'Background Color' ); ?> : 
					<input type="text" class="wpcolor" name="dupcolor" value="<?php echo esc_attr( get_user_option( 'scalg_dupcolor', get_current_user_id() ) ); ?>" size="10" />
					</div>
					<p class="description">
					<?php esc_html_e( 'Alternate text for [Show Appointment (Time Frame Only)]', 'simple-calendar-for-google' ); ?>
					</p>
					<div style="margin: 5px; padding: 5px;">
					<input type="text" name="alttext" value="<?php echo esc_attr( get_user_option( 'scalg_alttext', get_current_user_id() ) ); ?>" size="50" />
					&nbsp;
					<?php /* translators: Settings */ ?>
					<?php submit_button( sprintf( __( 'Save %s', 'simple-calendar-for-google' ), __( 'Schedule Duplication', 'simple-calendar-for-google' ) ), 'large', 'SaveScheduleDup', false ); ?>
					</div>
					<h3>CSS</h3>
					<p class="description">
					<?php esc_html_e( 'If you want to change the style other than the color, change the CSS below.', 'simple-calendar-for-google' ); ?>
					</p>
					</p>
					<div style="margin: 5px; padding: 5px;">
					<textarea name="customcss" cols="50" rows="6"><?php echo esc_textarea( get_option( 'scalg_css' ) ); ?></textarea>
					&nbsp;
					<?php submit_button( __( 'Default' ), 'large', 'DefaultCSS', false ); ?>
					<?php /* translators: Settings */ ?>
					<?php submit_button( sprintf( __( 'Save %s', 'simple-calendar-for-google' ), 'CSS' ), 'large', 'SaveCSS', false ); ?>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

	/** ==================================================
	 * Credit
	 *
	 * @since 1.00
	 */
	private function credit() {

		$plugin_name    = null;
		$plugin_ver_num = null;
		$plugin_path    = plugin_dir_path( __DIR__ );
		$plugin_dir     = untrailingslashit( wp_normalize_path( $plugin_path ) );
		$slugs          = explode( '/', $plugin_dir );
		$slug           = end( $slugs );
		$files          = scandir( $plugin_dir );
		foreach ( $files as $file ) {
			if ( '.' === $file || '..' === $file || is_dir( $plugin_path . $file ) ) {
				continue;
			} else {
				$exts = explode( '.', $file );
				$ext  = strtolower( end( $exts ) );
				if ( 'php' === $ext ) {
					$plugin_datas = get_file_data(
						$plugin_path . $file,
						array(
							'name'    => 'Plugin Name',
							'version' => 'Version',
						)
					);
					if ( array_key_exists( 'name', $plugin_datas ) && ! empty( $plugin_datas['name'] ) && array_key_exists( 'version', $plugin_datas ) && ! empty( $plugin_datas['version'] ) ) {
						$plugin_name    = $plugin_datas['name'];
						$plugin_ver_num = $plugin_datas['version'];
						break;
					}
				}
			}
		}
		$plugin_version = __( 'Version:' ) . ' ' . $plugin_ver_num;
		/* translators: FAQ Link & Slug */
		$faq       = sprintf( __( 'https://wordpress.org/plugins/%s/faq', 'simple-calendar-for-google' ), $slug );
		$support   = 'https://wordpress.org/support/plugin/' . $slug;
		$review    = 'https://wordpress.org/support/view/plugin-reviews/' . $slug;
		$translate = 'https://translate.wordpress.org/projects/wp-plugins/' . $slug;
		$facebook  = 'https://www.facebook.com/katsushikawamori/';
		$twitter   = 'https://twitter.com/dodesyo312';
		$youtube   = 'https://www.youtube.com/channel/UC5zTLeyROkvZm86OgNRcb_w';
		$donate    = __( 'https://shop.riverforest-wp.info/donate/', 'simple-calendar-for-google' );

		?>
		<span style="font-weight: bold;">
		<div>
		<?php echo esc_html( $plugin_version ); ?> | 
		<a style="text-decoration: none;" href="<?php echo esc_url( $faq ); ?>" target="_blank" rel="noopener noreferrer">FAQ</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $support ); ?>" target="_blank" rel="noopener noreferrer">Support Forums</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $review ); ?>" target="_blank" rel="noopener noreferrer">Reviews</a>
		</div>
		<div>
		<a style="text-decoration: none;" href="<?php echo esc_url( $translate ); ?>" target="_blank" rel="noopener noreferrer">
		<?php
		/* translators: Plugin translation link */
		echo esc_html( sprintf( __( 'Translations for %s' ), $plugin_name ) );
		?>
		</a> | <a style="text-decoration: none;" href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-facebook"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-twitter"></span></a> | <a style="text-decoration: none;" href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-video-alt3"></span></a>
		</div>
		</span>

		<div style="width: 250px; height: 180px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
		<h3><?php esc_html_e( 'Please make a donation if you like my work or would like to further the development of this plugin.', 'simple-calendar-for-google' ); ?></h3>
		<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
		<button type="button" style="margin: 5px; padding: 5px;" onclick="window.open('<?php echo esc_url( $donate ); ?>')"><?php esc_html_e( 'Donate to this plugin &#187;' ); ?></button>
		</div>

		<?php
	}

	/** ==================================================
	 * Update wp_options table.
	 *
	 * @since 1.00
	 */
	private function options_updated() {

		if ( isset( $_POST['Saveapi'] ) && ! empty( $_POST['Saveapi'] ) ) {
			if ( check_admin_referer( 'scalg_set', 'simplecalendarforgoogle_settings' ) ) {
				if ( ! empty( $_POST['api'] ) ) {
					update_user_option( get_current_user_id(), 'scalg_api', sanitize_text_field( wp_unslash( $_POST['api'] ) ) );
				}
				/* translators: Add API */
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Saved %s', 'simple-calendar-for-google' ), 'API' ) ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['Removeapi'] ) && ! empty( $_POST['Removeapi'] ) ) {
			if ( check_admin_referer( 'scalg_set', 'simplecalendarforgoogle_settings' ) ) {
				delete_user_option( get_current_user_id(), 'scalg_api' );
				/* translators: Remove API */
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Removed %s', 'simple-calendar-for-google' ), 'API' ) ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['Addids'] ) && ! empty( $_POST['Addids'] ) ) {
			if ( check_admin_referer( 'scalg_set', 'simplecalendarforgoogle_settings' ) ) {
				$ids = array();
				if ( get_user_option( 'scalg_ids', get_current_user_id() ) ) {
					$ids = get_user_option( 'scalg_ids', get_current_user_id() );
				}
				if ( ! empty( $_POST['ids'] ) ) {
					$id = sanitize_text_field( wp_unslash( $_POST['ids'] ) );
					$cal_api = get_user_option( 'scalg_api', get_current_user_id() );
					$cals = apply_filters( 'scalg_get_calendar', $cal_api, $id );
					if ( ! empty( $cals ) && array_key_exists( 'summary', $cals ) ) {
						$ids_name = $cals['summary'];
						if ( ! empty( $ids_name ) ) {
							$ids[ $ids_name ] = $id;
							update_user_option( get_current_user_id(), 'scalg_ids', $ids );
							/* translators: Name & ID */
							echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Added %s', 'simple-calendar-for-google' ), $ids_name . ' - ' . $id ) ) . '</li></ul></div>';
						} else {
							echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html__( 'There is no calendar name.', 'simple-calendar-for-google' ) . '</li></ul></div>';
						}
					} else {
						echo '<div class="notice notice-error is-dismissible"><ul><li>' . esc_html__( 'The ID you entered is not a public calendar.', 'simple-calendar-for-google' ) . '</li></ul></div>';
					}
				}
			}
		}

		if ( isset( $_POST['Deleteids'] ) && ! empty( $_POST['Deleteids'] ) ) {
			if ( check_admin_referer( 'scalg_set', 'simplecalendarforgoogle_settings' ) ) {
				if ( ! empty( $_POST['del_ids'] ) ) {
					$del_ids = array_map( 'sanitize_text_field', wp_unslash( $_POST['del_ids'] ) );
					$scalg_ids = get_user_option( 'scalg_ids', get_current_user_id() );
					$del_text = null;
					foreach ( $del_ids as $key ) {
						$del_text .= $key . ' - ' . $scalg_ids[ $key ] . ',';
						unset( $scalg_ids[ $key ] );
					}
					$del_text = rtrim( $del_text, ',' );
					update_user_option( get_current_user_id(), 'scalg_ids', $scalg_ids );
					echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( __( 'Delete' ) . ' --> ' . $del_text ) . '</li></ul></div>';
				}
			}
		}

		if ( isset( $_POST['Savecolor'] ) && ! empty( $_POST['Savecolor'] ) ) {
			if ( check_admin_referer( 'scalg_set', 'simplecalendarforgoogle_settings' ) ) {
				if ( ! empty( $_POST['color'] ) ) {
					update_user_option( get_current_user_id(), 'scalg_color', sanitize_text_field( wp_unslash( $_POST['color'] ) ) );
				}
				if ( ! empty( $_POST['bgcolor'] ) ) {
					update_user_option( get_current_user_id(), 'scalg_bgcolor', sanitize_text_field( wp_unslash( $_POST['bgcolor'] ) ) );
				}
				if ( ! empty( $_POST['dupcolor'] ) ) {
					update_user_option( get_current_user_id(), 'scalg_dupcolor', sanitize_text_field( wp_unslash( $_POST['dupcolor'] ) ) );
				}
				/* translators: Color */
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Saved %s', 'simple-calendar-for-google' ), __( 'Color' ) ) ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['SaveScheduleDup'] ) && ! empty( $_POST['SaveScheduleDup'] ) ) {
			if ( check_admin_referer( 'scalg_set', 'simplecalendarforgoogle_settings' ) ) {
				if ( ! empty( $_POST['duplimit'] ) ) {
					update_user_option( get_current_user_id(), 'scalg_duplimit', absint( $_POST['duplimit'] ) );
				}
				if ( ! empty( $_POST['dupcolor'] ) ) {
					update_user_option( get_current_user_id(), 'scalg_dupcolor', sanitize_text_field( wp_unslash( $_POST['dupcolor'] ) ) );
				}
				if ( ! empty( $_POST['alttext'] ) ) {
					update_user_option( get_current_user_id(), 'scalg_alttext', sanitize_text_field( wp_unslash( $_POST['alttext'] ) ) );
				}
				/* translators: Schedule Duplication */
				echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Saved %s', 'simple-calendar-for-google' ), __( 'Schedule Duplication', 'simple-calendar-for-google' ) ) ) . '</li></ul></div>';
			}
		}

		if ( isset( $_POST['DefaultCSS'] ) && ! empty( $_POST['DefaultCSS'] ) ) {
			if ( check_admin_referer( 'scalg_set', 'simplecalendarforgoogle_settings' ) ) {
				$css = '.scalg_date { font-weight: bold; padding-bottom: 5px; font-size: 1.2em; } .scalg_start_time { padding: 0 10px; font-size: 1.2em; } .scalg_summary { padding: 0 10px; font-size: 1.2em; } .scalg_location { padding: 0 10px; font-size: 1.2em; } .scalg_hr { border-top: 1px solid #bbb; }';
				update_option( 'scalg_css', $css );
				echo '<div class="notice notice-success is-dismissible"><ul><li>CSS --> ' . esc_html__( 'Default' ) . '</li></ul></div>';
			}
		}
		if ( isset( $_POST['SaveCSS'] ) && ! empty( $_POST['SaveCSS'] ) ) {
			if ( check_admin_referer( 'scalg_set', 'simplecalendarforgoogle_settings' ) ) {
				if ( ! empty( $_POST['customcss'] ) ) {
					update_option( 'scalg_css', sanitize_text_field( wp_unslash( $_POST['customcss'] ) ) );
					/* translators: CSS */
					echo '<div class="notice notice-success is-dismissible"><ul><li>' . esc_html( sprintf( __( 'Saved %s', 'simple-calendar-for-google' ), 'CSS' ) ) . '</li></ul></div>';
				}
			}
		}
	}

	/** ==================================================
	 * Settings register
	 *
	 * @since 2.15
	 */
	public function register_settings() {

		if ( ! get_option( 'scalg_css' ) ) {
			$css = '.scalg_date { font-weight: bold; padding-bottom: 5px; font-size: 1.2em; } .scalg_start_time { padding: 0 10px; font-size: 1.2em; } .scalg_summary { padding: 0 10px; font-size: 1.2em; } .scalg_location { padding: 0 10px; font-size: 1.2em; } .scalg_hr { border-top: 1px solid #bbb; }';
			update_option( 'scalg_css', $css );
		}
	}

	/** ==================================================
	 * Settings register
	 *
	 * @since 2.10
	 */
	public function register_user_settings() {

		if ( get_option( 'scalg_api' ) || get_option( 'scalg_ids' ) ||
			! get_user_option( 'scalg_color', get_current_user_id() ) ||
			! get_user_option( 'scalg_bgcolor', get_current_user_id() ) ||
			! get_user_option( 'scalg_duplimit', get_current_user_id() ) ||
			! get_user_option( 'scalg_dupcolor', get_current_user_id() ) ||
			! get_user_option( 'scalg_alttext', get_current_user_id() ) ) {
			/* Old option 1.03 -> New option 2.00 */
			if ( get_option( 'scalg_api' ) ) {
				update_user_option( get_current_user_id(), 'scalg_api', get_option( 'scalg_api' ) );
				delete_option( 'scalg_api' );
			}
			if ( get_option( 'scalg_ids' ) ) {
				update_user_option( get_current_user_id(), 'scalg_ids', get_option( 'scalg_ids' ) );
				delete_option( 'scalg_ids' );
			}
		}

		if ( ! get_user_option( 'scalg_color', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'scalg_color', '#000' );
		} else { /* Old option 2.13 -> New option 2.14 */
			$scalg_color = get_user_option( 'scalg_color', get_current_user_id() );
			if ( strpos( $scalg_color, '#' ) === false ) {
				update_user_option( get_current_user_id(), 'scalg_color', '#' . $scalg_color );
			}
		}
		if ( ! get_user_option( 'scalg_bgcolor', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'scalg_bgcolor', '#ddd' );
		} else { /* Old option 2.13 -> New option 2.14 */
			$scalg_bgcolor = get_user_option( 'scalg_bgcolor', get_current_user_id() );
			if ( strpos( $scalg_bgcolor, '#' ) === false ) {
				update_user_option( get_current_user_id(), 'scalg_bgcolor', '#' . $scalg_bgcolor );
			}
		}
		if ( ! get_user_option( 'scalg_duplimit', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'scalg_duplimit', 3 );
		}
		if ( ! get_user_option( 'scalg_dupcolor', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'scalg_dupcolor', '#f00' );
		} else { /* Old option 2.13 -> New option 2.14 */
			$scalg_dupcolor = get_user_option( 'scalg_dupcolor', get_current_user_id() );
			if ( strpos( $scalg_dupcolor, '#' ) === false ) {
				update_user_option( get_current_user_id(), 'scalg_dupcolor', '#' . $scalg_dupcolor );
			}
		}
		if ( ! get_user_option( 'scalg_alttext', get_current_user_id() ) ) {
			update_user_option( get_current_user_id(), 'scalg_alttext', __( 'Reservation', 'simple-calendar-for-google' ) );
		}

		$ids = get_user_option( 'scalg_ids', get_current_user_id() );
		if ( ! empty( $ids ) && array_values( $ids ) === $ids ) { /* Version 2.09 or earlier*/
			$cal_api = get_user_option( 'scalg_api', get_current_user_id() );
			foreach ( $ids as $value ) {
				$cals = apply_filters( 'scalg_get_calendar', $cal_api, $value );
				if ( ! empty( $cals ) && array_key_exists( 'summary', $cals ) ) {
					$ids_name = $cals['summary'];
					$ids2[ $ids_name ] = $value;
				}
			}
			update_user_option( get_current_user_id(), 'scalg_ids', $ids2 );
		}

		if ( ! get_option( 'scalg_admin' ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				update_option( 'scalg_admin', get_current_user_id() );
			}
		}
	}

	/** ==================================================
	 * Notice
	 *
	 * @since 2.00
	 */
	public function change_settings() {

		if ( ! current_user_can( 'upload_files' ) ) {
			return;
		}
		if ( get_option( 'scalg_api' ) || get_option( 'scalg_ids' ) ||
			! get_user_option( 'scalg_color', get_current_user_id() ) ||
			! get_user_option( 'scalg_bgcolor', get_current_user_id() ) ) {
			$settings_html = '<a href="' . admin_url( 'admin.php?page=simplecalendarforgoogle-settings' ) . '" style="text-decoration: none; word-break: break-all;">' . __( 'Settings' ) . '</a>';
			/* translators: Settings link */
			echo '<div class="notice notice-warning is-dismissible"><ul><li><strong>Simple Calendar for Google</strong> : ' . wp_kses_post( sprintf( __( 'Data needs to be updated. Display the %s screen.', 'simple-calendar-for-google' ), $settings_html ) ) . '</li></ul></div>';
		}
	}
}


