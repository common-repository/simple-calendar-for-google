<?php
/**
 * Simple Calendar for Google
 *
 * @package    Simple Calendar for Google
 * @subpackage SimpleCalendarForGoogle Main Functions
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

$simplecalendarforgoogle = new SimpleCalendarForGoogle();

/** ==================================================
 * Main Functions
 */
class SimpleCalendarForGoogle {

	/** ==================================================
	 * Construct
	 *
	 * @since 1.00
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'simplecalendarforgoogle_block_init' ) );
		add_shortcode( 'scalg', array( $this, 'simplecalendarforgoogle_func' ) );
		add_action( 'enqueue_block_assets', array( $this, 'load_style' ) );

		/* Original hook */
		add_filter( 'scalg_get_calendar', array( $this, 'get_calendar' ), 10, 2 );
	}

	/** ==================================================
	 * Attribute block
	 *
	 * @since 2.00
	 */
	public function simplecalendarforgoogle_block_init() {

		$cal_api = get_user_option( 'scalg_api', $this->get_user_id() );

		$ids = get_user_option( 'scalg_ids', $this->get_user_id() );
		$ids2[] = array(
			'value' => '',
			'label' => __( 'All' ),
		);
		if ( ! empty( $ids ) ) {
			foreach ( $ids as $key => $value ) {
				$ids2[] = array(
					'value' => $value,
					'label' => $key . ' - ' . $value,
				);
			}
		}

		if ( get_user_option( 'scalg_color', $this->get_user_id() ) ) {
			$color = get_user_option( 'scalg_color', $this->get_user_id() );
		} else {
			$color = '#000';
		}
		if ( get_user_option( 'scalg_bgcolor', $this->get_user_id() ) ) {
			$bgcolor = get_user_option( 'scalg_bgcolor', $this->get_user_id() );
		} else {
			$bgcolor = '#ddd';
		}
		if ( get_user_option( 'scalg_duplimit', $this->get_user_id() ) ) {
			$duplimit = intval( get_user_option( 'scalg_duplimit', $this->get_user_id() ) );
		} else {
			$duplimit = 3;
		}
		if ( get_user_option( 'scalg_dupcolor', $this->get_user_id() ) ) {
			$dupcolor = get_user_option( 'scalg_dupcolor', $this->get_user_id() );
		} else {
			$dupcolor = '#f00';
		}
		if ( get_user_option( 'scalg_alttext', $this->get_user_id() ) ) {
			$alttext = get_user_option( 'scalg_alttext', $this->get_user_id() );
		} else {
			$alttext = __( 'Reservation', 'simple-calendar-for-google' );
		}

		register_block_type(
			plugin_dir_path( __DIR__ ) . 'block/build',
			array(
				'render_callback' => array( $this, 'simplecalendarforgoogle_func' ),
				'title' => 'Simple Calendar for Google',
				'description' => _x( 'Lists Google’s public calendars.', 'block description', 'simple-calendar-for-google' ),
				'keywords' => array(
					_x( 'calendar', 'block keyword', 'simple-calendar-for-google' ),
					_x( 'lists', 'block keyword', 'simple-calendar-for-google' ),
					'google',
				),
				'attributes'      => array(
					'id'  => array(
						'type'      => 'string',
						'default'   => null,
					),
					'color' => array(
						'type'      => 'string',
						'default'   => $color,
					),
					'bgcolor' => array(
						'type'      => 'string',
						'default'   => $bgcolor,
					),
					'duplimit' => array(
						'type'      => 'number',
						'default'   => $duplimit,
					),
					'dupcolor' => array(
						'type'      => 'string',
						'default'   => $dupcolor,
					),
					'alttext' => array(
						'type'      => 'string',
						'default'   => $alttext,
					),
				),
			)
		);

		$script_handle = generate_block_asset_handle( 'simple-calendar-for-google/scgcalendar-block', 'editorScript' );
		wp_set_script_translations( $script_handle, 'simple-calendar-for-google' );

		wp_localize_script(
			$script_handle,
			'scgcalendar_ids',
			$ids2
		);
	}

	/** ==================================================
	 * short code
	 *
	 * @param array $atts  atts.
	 * @return string $this->main_func()
	 */
	public function simplecalendarforgoogle_func( $atts ) {

		$a = shortcode_atts(
			array(
				'id' => '',
				'color' => '',
				'bgcolor' => '',
				'duplimit' => '',
				'dupcolor' => '',
				'alttext' => '',
			),
			$atts
		);

		$cal_ids = array();
		if ( ! empty( $a['id'] ) ) {
			$cal_ids[] = $a['id'];
		} elseif ( get_user_option( 'scalg_ids', $this->get_user_id() ) ) {
				$cal_ids = get_user_option( 'scalg_ids', $this->get_user_id() );
		}
		if ( ! empty( $a['color'] ) ) {
			$color = $a['color'];
		} elseif ( get_user_option( 'scalg_color', $this->get_user_id() ) ) {
				$color = get_user_option( 'scalg_color', $this->get_user_id() );
		} else {
			$color = '#000';
		}
		if ( ! empty( $a['bgcolor'] ) ) {
			$bgcolor = $a['bgcolor'];
		} elseif ( get_user_option( 'scalg_bgcolor', $this->get_user_id() ) ) {
				$bgcolor = get_user_option( 'scalg_bgcolor', $this->get_user_id() );
		} else {
			$bgcolor = '#ddd';
		}
		if ( ! empty( $a['duplimit'] ) ) {
			$duplimit = $a['duplimit'];
		} elseif ( get_user_option( 'scalg_duplimit', $this->get_user_id() ) ) {
				$duplimit = get_user_option( 'scalg_duplimit', $this->get_user_id() );
		} else {
			$duplimit = 3;
		}
		if ( ! empty( $a['dupcolor'] ) ) {
			$dupcolor = $a['dupcolor'];
		} elseif ( get_user_option( 'scalg_dupcolor', $this->get_user_id() ) ) {
				$dupcolor = get_user_option( 'scalg_dupcolor', $this->get_user_id() );
		} else {
			$dupcolor = '#f00';
		}
		if ( ! empty( $a['alttext'] ) ) {
			$alttext = $a['alttext'];
		} elseif ( get_user_option( 'scalg_alttext', $this->get_user_id() ) ) {
				$alttext = get_user_option( 'scalg_alttext', $this->get_user_id() );
		} else {
			$alttext = __( 'Reservation', 'simple-calendar-for-google' );
		}

		return $this->main_func( $cal_ids, $color, $bgcolor, $duplimit, $dupcolor, $alttext );
	}

	/** ==================================================
	 * Main
	 *
	 * @param array  $cal_ids  cal_ids.
	 * @param string $color  color.
	 * @param string $bgcolor  bgcolor.
	 * @param int    $duplimit  duplimit.
	 * @param string $dupcolor  dupcolor.
	 * @param string $alttext  alttext.
	 */
	private function main_func( $cal_ids, $color, $bgcolor, $duplimit, $dupcolor, $alttext ) {

		$calendar_arr = array();
		$count = 0;

		$cal_api = get_user_option( 'scalg_api', $this->get_user_id() );

		foreach ( $cal_ids as $cal_id ) {
			$cals = $this->get_calendar( $cal_api, $cal_id );
			if ( $cals ) {
				$timezone     = $cals['timeZone'];
				$datetimezone = new DateTimeZone( $timezone );
				$datetime_now = new DateTime( 'now', $datetimezone );
				$timeoffset   = $datetimezone->getOffset( $datetime_now );
				foreach ( $cals['items'] as $key => $key2 ) {
					$date_flag = false;
					if ( array_key_exists( 'dateTime', $key2['start'] ) ) {
						$start_time = strtotime( $key2['start']['dateTime'] );
					} else if ( array_key_exists( 'date', $key2['start'] ) ) {
						$start_time = strtotime( $key2['start']['date'] );
						$date_flag = true;
					}
					if ( array_key_exists( 'dateTime', $key2['end'] ) ) {
						$end_time = strtotime( $key2['end']['dateTime'] );
					} else if ( array_key_exists( 'date', $key2['end'] ) ) {
						$end_time = strtotime( $key2['end']['date'] );
						$date_flag = true;
					}
					if ( array_key_exists( 'summary', $key2 ) ) {
						$summary = $key2['summary'];
					} else {
						$summary = $alttext;
					}
					if ( function_exists( 'wp_date' ) ) {
						$start_date   = wp_date( get_option( 'date_format' ), $start_time );
						$start_time_f = wp_date( get_option( 'time_format' ), $start_time );
						$end_date     = wp_date( get_option( 'date_format' ), $end_time );
						$end_time_f   = wp_date( get_option( 'time_format' ), $end_time );
						$start_date_d = wp_date( get_option( 'date_format' ), $start_time - $timeoffset );
						$end_date_d   = wp_date( get_option( 'date_format' ), $end_time - $timeoffset - 1 );
					} else {
						$start_date   = date_i18n( get_option( 'date_format' ), $start_time + $timeoffset );
						$start_time_f = date_i18n( get_option( 'time_format' ), $start_time + $timeoffset );
						$end_date     = date_i18n( get_option( 'date_format' ), $end_time + $timeoffset );
						$end_date_d   = date_i18n( get_option( 'date_format' ), $end_time + $timeoffset );
						$end_time_f   = date_i18n( get_option( 'time_format' ), $end_time + $timeoffset );
						$start_date_d = date_i18n( get_option( 'date_format' ), $start_time );
						$end_date_d   = date_i18n( get_option( 'date_format' ), $end_time - 1 );
					}
					if ( $start_date === $end_date ) {
						$date_str = $start_date;
						$start_time_str = $start_time_f;
						$time_str = $start_time_f . ' - ' . $end_time_f;
					} elseif ( $date_flag && ( $end_time - $start_time ) <= 86400 ) {
							$date_str = $start_date;
							$start_time_str = $start_time_f;
							$time_str = $start_time_f . ' - ' . $end_time_f;
					} else {
						$date_str = $start_date_d . ' - ' . $end_date_d;
						$start_time_str = null;
						$time_str = null;
					}
					if ( array_key_exists( 'location', $key2 ) ) {
						$location = $key2['location'];
					} else {
						$location = null;
					}
					$calendar_arr[] = array(
						'calendar' => $cals['summary'],
						'summary' => $summary,
						'start_date' => $start_date,
						'end_date' => $end_date,
						'start_time' => $start_time,
						'start_time_str' => $start_time_str,
						'end_time' => $end_time,
						'date' => $date_str,
						'time' => $time_str,
						'location' => $location,
					);
				}
			}
		}

		$html = null;
		if ( ! empty( $calendar_arr ) ) {
			/* Sort start_time */
			foreach ( $calendar_arr as $key => $value ) {
				$id[ $key ] = $value['start_time'];
			}
			array_multisort( $id, SORT_ASC, $calendar_arr );

			$foward_date = null;
			$foward_time = null;
			$dup_count = 1;
			foreach ( $calendar_arr as $key => $value ) {
				if ( $foward_date === $value['start_date'] ) {
					if ( $foward_time < $value['start_time'] ) {
						$html .= '<div class="scalg_start_time">' . esc_html( $value['start_time_str'] ) . '</div>';
						$dup_count = 1;
					} else {
						++$dup_count;
					}
				} else {
					$html .= '<div class="scalg_date">' . esc_html( $value['date'] ) . '</div>';
					$html .= '<div class="scalg_start_time">' . esc_html( $value['start_time_str'] ) . '</div>';
				}
				if ( $duplimit < $dup_count ) {
					$bg_val_color = $dupcolor;
				} else {
					$bg_val_color = $bgcolor;
				}
				$summary = $value['summary'];
				if ( $alttext === $summary && 2 <= $dup_count ) {
					$summary = $summary . $dup_count;
				}
				$html .= '<div class="scalg_summary" style="background-color: ' . esc_attr( $bg_val_color ) . '; color: ' . esc_attr( $color ) . ';">' . esc_html( $summary . ' : ' . $value['time'] ) . '</div>';
				if ( ! empty( $value['location'] ) ) {
					$html .= '<div class="scalg_location">' . esc_html( $value['location'] ) . '</div>';
				}
				$html .= '<hr class="scalg_hr">';
				$foward_date = $value['start_date'];
				$foward_time = $value['start_time'];
			}
		}

		if ( is_user_logged_in() && is_null( $html ) ) {
			$html = '<div>' . esc_html__( 'There is no calendar.', 'simple-calendar-for-google' ) . '</div>';
		}

		return $html;
	}

	/** ==================================================
	 * Get Calendar
	 *
	 * @param string $cal_api  cal_api.
	 * @param string $cal_id  cal_id.
	 * @return array or bool $cals  cals.
	 * @since 1.00
	 */
	public function get_calendar( $cal_api, $cal_id ) {

		$t = time();

		$params = array();
		$params[] = 'orderBy=startTime';
		$params[] = 'timeMin=' . urlencode( gmdate( 'c', $t ) );

		$url = 'https://www.googleapis.com/calendar/v3/calendars/' . $cal_id . '/events?key=' . $cal_api . '&singleEvents=true&' . implode( '&', $params );

		$results = wp_remote_get( $url );
		$cals = json_decode( $results['body'], true );
		if ( array_key_exists( 'error', $cals ) ) {
			$cals = false;
		}

		return $cals;
	}

	/** ==================================================
	 * Get User ID
	 *
	 * @since 2.00
	 */
	private function get_user_id() {

		$post_id = url_to_postid( get_the_permalink() );
		$post    = get_post( $post_id );
		if ( $post ) {
			$user_data = get_userdata( $post->post_author );
			return $user_data->ID;
		} elseif ( get_option( 'scalg_admin' ) ) {
				return get_option( 'scalg_admin' );
		} else {
			return 0;
		}
	}

	/** ==================================================
	 * Load Style
	 *
	 * @since 2.15
	 */
	public function load_style() {

		wp_enqueue_style( 'simple-calendar-for-google', plugin_dir_url( __DIR__ ) . 'css/scalg.css', array(), '1.00' );
		wp_add_inline_style( 'simple-calendar-for-google', get_option( 'scalg_css' ) );
	}
}
