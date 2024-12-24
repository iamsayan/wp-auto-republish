<?php
/**
 * Rewrite post permalinks.
 *
 * @since      1.1.0
 * @package    RevivePress
 * @subpackage RevivePress\Core\Premium
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */

namespace RevivePress\Core;

use RevivePress\Helpers\Hooker;
use RevivePress\Helpers\SettingsData;

defined( 'ABSPATH' ) || exit;

/**
 * Permalink Rewrite class.
 */
class RewritePermalinks
{
	use Hooker;
    use SettingsData;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( 'init','rewrite_tag' );
        $this->action( 'template_redirect', 'check_rewrite' );
        $this->filter( 'post_link', 'filter_post_link', 10, 2 );
        $this->filter( 'post_type_link', 'filter_post_link', 10, 2 );
        $this->filter( 'available_permalink_structure_tags', 'available_tags' );
		$this->action( 'admin_notices', 'permalink_notice' );
		$this->action( 'admin_init', 'fix_action' );
	}

	/**
	 * Register rewrite tags.
	 */
	public function rewrite_tag() {
		add_rewrite_tag( '%rvp_year%', '([0-9]{4})' );
		add_rewrite_tag( '%rvp_monthnum%', '([0-9]{1,2})' );
		add_rewrite_tag( '%rvp_day%', '([0-9]{1,2})' );
		add_rewrite_tag( '%rvp_hour%', '([0-9]{1,2})' );
		add_rewrite_tag( '%rvp_minute%', '([0-9]{1,2})' );
		add_rewrite_tag( '%rvp_second%', '([0-9]{1,2})' );
	}

	/**
	 * Check and throw 404 error if the actual date doesn't match.
	 * 
	 * @since 1.4.5
	 */
	public function check_rewrite() {
		if ( is_single() ) {
			$permalink_structure = get_option( 'permalink_structure' );
			// bail if tag is not present in the url
			if ( false === strpos( $permalink_structure, '%rvp_' ) ) {
				return;
			}

			$original_date = $this->get_meta( get_the_ID(), '_wpar_original_pub_date' );
			if ( ! $original_date ) {
				$original_date = get_the_date( 'Y-m-d H:i:s', get_the_ID() );
			}

			// This is not an API call because the permalink is based on the stored post_date value,
        	// which should be parsed as local time regardless of the default PHP timezone.
			$date = explode( ' ', str_replace( array( '-', ':' ), ' ', $original_date ) );

			$throw_404 = false;
			$rewritecode = array(
				'rvp_year',
				'rvp_monthnum',
				'rvp_day',
				'rvp_hour',
				'rvp_minute',
				'rvp_second',
			);

			foreach ( $rewritecode as $key => $slug ) {
				$datetime = get_query_var( $slug );
				if ( ! empty( $datetime ) && ( intval( $datetime ) !== intval( $date[ $key ] ) ) ) {
					$throw_404 = true;
					break;
				}
			}

			if ( $throw_404 ) {
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
				get_template_part( 404 );
				exit();
			}
		}
	}

	/**
	 * Filter post permalinks.
	 *
	 * @param string $permalink   Original post permalink.
	 * @param object $post        WordPress Post object.
	 *
	 * @return string             Filtered permalink
	 */
	public function filter_post_link( $permalink, $post ) {
		// bail if tag is not present in the url
		if ( false === strpos( $permalink, '%rvp_' ) ) {
			return $permalink;
		}

		$original_date = $this->get_meta( $post->ID, '_wpar_original_pub_date' );
		if ( ! $original_date ) {
		    $original_date = $post->post_date;
		}

		// This is not an API call because the permalink is based on the stored post_date value,
        // which should be parsed as local time regardless of the default PHP timezone.
		$date = explode( ' ', str_replace( array( '-', ':' ), ' ', $original_date ) );

		$rewritecode = array(
			'%rvp_year%',
			'%rvp_monthnum%',
			'%rvp_day%',
			'%rvp_hour%',
			'%rvp_minute%',
			'%rvp_second%',
		);

		$rewritereplace = array(
			$date[0],
            $date[1],
            $date[2],
            $date[3],
            $date[4],
            $date[5],
		);

		return str_replace( $rewritecode, $rewritereplace, $permalink );
	}

	/**
	 * Filter post permalink available tags lists.
	 * 
	 * @since 1.3.3
	 * @param array $tags   Existing tags.
	 *
	 * @return array        Filtered tags array
	 */
	public function available_tags( $tags ) {
		// phpcs:disable WordPress.WP.I18n.MissingArgDomain
		$available_tags = array(
			/* translators: %s: Permalink structure tag. */
			'rvp_year'     => __( '%s (The year of the post, four digits, for example 2004.)' ),
			/* translators: %s: Permalink structure tag. */
			'rvp_monthnum' => __( '%s (Month of the year, for example 05.)' ),
			/* translators: %s: Permalink structure tag. */
			'rvp_day'      => __( '%s (Day of the month, for example 28.)' ),
			/* translators: %s: Permalink structure tag. */
			'rvp_hour'     => __( '%s (Hour of the day, for example 15.)' ),
			/* translators: %s: Permalink structure tag. */
			'rvp_minute'   => __( '%s (Minute of the hour, for example 43.)' ),
			/* translators: %s: Permalink structure tag. */
			'rvp_second'   => __( '%s (Second of the minute, for example 33.)' ),
		);
		// phpcs:enable

		return array_merge( $tags, $available_tags );
	}

	/**
	 * Show permalink notices.
	 */
	public function permalink_notice() {
		$show_notice = get_option( 'revivepress_hide_permalink_notice' );
		if ( $show_notice ) {
			return;
		}

		$permalink_structure = get_option( 'permalink_structure' );
		$fix_url = wp_nonce_url( add_query_arg( 'rvp_sync_permalink', 'yes' ), 'rvp_sync_permalink' );
		$hide = wp_nonce_url( add_query_arg( 'rvp_sync_permalink', 'hide' ), 'rvp_sync_permalink' );
		
		if ( preg_match( '(%year%|%monthnum%|%day%|%hour%|%minute%|%second%)', $permalink_structure ) === 1 ) { ?>
			<div class="notice notice-warning">
				<p style="line-height: 1.8;">
					<strong>RevivePress</strong>: 
					<em><?php printf( 
							/* translators: 1: WordPress permalink tags, 2: RevivePress permalink tags. */
							esc_html__( 'As it seems that your permalinks structure contains date, please use %1$s instead of %2$s respectively. Otherwise, it may create SEO issues. But, if you want to use different permalink structure everytime after republish, you can safely dismiss this warning.', 'wp-auto-republish' ), '<code>%rvp_year%</code>, <code>%rvp_monthnum%</code>, <code>%rvp_day%</code>, <code>%rvp_hour%</code>, <code>%rvp_minute%</code>, <code>%rvp_second%</code>', '<code>%year%</code>, <code>%monthnum%</code>, <code>%day%</code>, <code>%hour%</code>, <code>%minute%</code>, <code>%second%</code>' 
						); ?>
					</em>
				</p>
				<p style="margin-bottom: 10px;">
					<a href="<?php echo esc_url( $fix_url ); ?>" class="button button-secondary"><strong><?php esc_html_e( 'Fix Permalink Structure', 'wp-auto-republish' ); ?></strong></a>&nbsp;&nbsp;
					<a href="<?php echo esc_url( $hide ); ?>"><strong><?php esc_html_e( 'Dismiss Notice', 'wp-auto-republish' ); ?></strong></a>
				</p>
			</div> <?php
		}
	}

	/**
	 * Fix permalink action.
	 */
	public function fix_action() {
		if ( ! isset( $_REQUEST['rvp_sync_permalink'] ) ) {
			return;
		}

		check_admin_referer( 'rvp_sync_permalink' );
			
		if ( 'hide' === $_REQUEST['rvp_sync_permalink'] ) {
			update_option( 'revivepress_hide_permalink_notice', true, false );
		}

		if ( 'yes' === $_REQUEST['rvp_sync_permalink'] ) {
			$this->fix_permalink();
		}
	
		wp_safe_redirect( admin_url( 'options-permalink.php' ) );
		exit;
	}

	/**
	 * Fix permalinks.
	 */
	private function fix_permalink() {
		$permalink_structure = get_option( 'permalink_structure' );

		$search = array( '%year%', '%monthnum%', '%day%', '%hour%', '%minute%', '%second%' );
		$replace = array( '%rvp_year%', '%rvp_monthnum%', '%rvp_day%', '%rvp_hour%', '%rvp_minute%', '%rvp_second%' );
		$permalink_structure = str_replace( $search, $replace, $permalink_structure );

		update_option( 'permalink_structure', $permalink_structure );
		flush_rewrite_rules();
	}
}