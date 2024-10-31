<?php
/**
 * PDF Shortcodes Ultimate class.
 *
 * @package PDF Shortcodes Ultimate
 */

defined( 'ABSPATH' ) || exit;

/**
 * PDF Shortcodes Ultimate class.
 */
class PDF_Shortcodes_Ultimate {

	/**
	 * The single instance of PDF_Shortcodes_Ultimate.
	 *
	 * @var    object
	 * @access private
	 * @since  1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 *
	 * @var    object
	 * @access public
	 * @since  1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 *
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 *
	 * @param string $file    File pathname.
	 * @param string $version Version number.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token = 'pdf_shortcodes_ultimate';

		// Load plugin environment variables.
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Register our PDF shortcode.
		add_filter( 'su/data/shortcodes', array( $this, 'register_su_pdf_shortcode' ) );

		// Load frontend JS & CSS.
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		// Load admin JS & CSS.
		// add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		// add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Load API for generic admin functions.
		if ( is_admin() ) {
			// $this->admin = new PDF_Shortcodes_Ultimate_Admin_API();

			// @link https://codex.wordpress.org/Function_Reference/register_activation_hook#Process_Flow
			if ( get_option( 'Shortcodes_Ultimate_Plugin_Not_Activated' ) ) {

				delete_option( 'Shortcodes_Ultimate_Plugin_Not_Activated' );

				// Display warning to user: should activate Shortcodes Ultimate plugin.
				add_action( 'admin_notices', array( $this, 'no_shortcodes_ultimate_admin_notice__warning' ) );
			}
		}

		// Handle localisation.
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
	} // End __construct ()


	/**
	 * Register PDF shortcode to Shortcode Ultimate
	 *
	 * [su_pdf url="https://domain.com/document.pdf" link="Click here to download PDF"]
	 *
	 * @since 1.0.0
	 *
	 * @link https://gndev.info/kb/shortcodes-ultimate-api-overview/
	 * @link https://github.com/gndev/shortcodes-ultimate/blob/master/inc/core/data.php
	 *
	 * @hooked su/data/shortcodes
	 *
	 * @param  array $shortcodes Original plugin shortcodes.
	 * @return array Modified array
	 */
	public function register_su_pdf_shortcode( $shortcodes ) {

		// Add new shortcode.
		$shortcodes['pdf'] = array(
			// Shortcode name.
			'name' => __( 'PDF', 'pdf-shortcodes-ultimate' ),
			// Shortcode type. Can be 'wrap' or 'single'.
			// Example: [b]this is wrapped[/b], [this_is_single].
			'type' => 'single',
			// Shortcode group.
			// Can be 'content', 'box', 'media' or 'other'.
			// Groups can be mixed, for example 'content box'.
			'group' => 'media',
			// List of shortcode params (attributes).
			'atts' => array(
				// URL attribute.
				'url' => array(
					// Attribute type.
					// Can be 'select', 'color', 'bool', 'text', or 'upload.
					'type' => 'upload',
					// Available values.
					'values' => array(),
					// Default value.
					'default' => '',
					// Attribute name.
					'name' => __( 'URL', 'pdf-shortcodes-ultimate' ),
					// Attribute description.
					'desc' => __( 'URL to PDF document (.pdf)', 'pdf-shortcodes-ultimate' ),
				),
				// Link attribute.
				'link' => array(
					'default' => __( 'Click here to download PDF', 'pdf-shortcodes-ultimate' ),
					'name' => __( 'Fallback download link text', 'pdf-shortcodes-ultimate' ),
					'desc' => __( 'Text for the link to download PDF (in case the browser cannot display PDF).', 'pdf-shortcodes-ultimate' ),
				),
			),
			// Default content for generator (for wrap-type shortcodes).
			'content' => '',
			// Shortcode description for cheatsheet and generator.
			'desc' => __( 'Embed PDF using the browser built-in PDF viewer.', 'pdf-shortcodes-ultimate' ),
			// Custom icon (font-awesome).
			'icon' => 'file-pdf-o',
			// Name of custom shortcode function.
			'function' => array( $this, 'su_pdf_shortcode' ),
		);

		// Return modified data.
		return $shortcodes;
	}


	/**
	 * PDF shortcode function
	 *
	 * @since 1.0.0
	 *
	 * @link https://gndev.info/kb/shortcodes-ultimate-api-overview/
	 *
	 * @uses Shortcodes Ultimate plugin
	 * @see https://github.com/gndev/shortcodes-ultimate/blob/master/inc/core/shortcodes.php#L612
	 *
	 * @param  array  $atts    Shortcode attributes.
	 * @param  string $content Shortcode content.
	 * @return string Shortcode markup.
	 */
	public function su_pdf_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'url' => '',
				'link' => __( 'Click here to download PDF', 'pdf-shortcodes-ultimate' ),
			),
			$atts
		);

		if ( ! $atts['url'] ) {
			// Error: No or invalid URL to PDF.
			return Su_Tools::error( __FUNCTION__, __( 'please specify correct url', 'shortcodes-ultimate' ) );
		}

		/*
		<div class="agl-su-pdf su-responsive-media-yes">
		  <object data="https://domain.com/document.pdf" type="application/pdf">
		    <p>
		      <a href="https://domain.com/document.pdf">
		        Click here to download PDF
		      </a>
		    </p>
		  </object>
		</div>
		*/

		ob_start();
		?>
		<div class="agl-su-pdf su-responsive-media-yes">
			<object data="<?php echo esc_url( $atts['url'] ); ?>" type="application/pdf">
				<p>
					<a href="<?php echo esc_url( $atts['url'] ); ?>">
						<?php echo esc_html( $atts['link'] ); ?>
					</a>
				</p>
			</object>
		</div>
		<?php
		// Enqueue media-shortcodes.css.
		su_query_asset( 'css', 'su-media-shortcodes' );

		$pdf_html = ob_get_clean();

		return $pdf_html;
	}


	/**
	 * Wrapper function to register a new post type
	 *
	 * @param  string $post_type   Post type name.
	 * @param  string $plural      Post type item plural name.
	 * @param  string $single      Post type item single name.
	 * @param  string $description Description of post type.
	 * @param  array  $options     Post type options.
	 * @return object              Post type class object.
	 */
	public function register_post_type( $post_type = '', $plural = '', $single = '', $description = '', $options = array() ) {

		if ( ! $post_type ||
			! $plural ||
			! $single ) {
			return;
		}

		$post_type = new PDF_Shortcodes_Ultimate_Post_Type( $post_type, $plural, $single, $description, $options );

		return $post_type;
	}

	/**
	 * Wrapper function to register a new taxonomy
	 *
	 * @param  string $taxonomy      Taxonomy name.
	 * @param  string $plural        Taxonomy single name.
	 * @param  string $single        Taxonomy plural name.
	 * @param  array  $post_types    Post types to which this taxonomy applies.
	 * @param  array  $taxonomy_args Taxonomy arguments.
	 * @return object                Taxonomy class object
	 */
	public function register_taxonomy( $taxonomy = '', $plural = '', $single = '', $post_types = array(), $taxonomy_args = array() ) {

		if ( ! $taxonomy ||
			! $plural ||
			! $single ) {
			return;
		}

		$taxonomy = new PDF_Shortcodes_Ultimate_Taxonomy( $taxonomy, $plural, $single, $post_types, $taxonomy_args );

		return $taxonomy;
	}

	/**
	 * Load frontend CSS.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-frontend' );
	} // End enqueue_scripts ()

	/**
	 * Load admin CSS.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function admin_enqueue_styles( $hook = '' ) {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	} // End admin_enqueue_styles ()

	/**
	 * Load admin Javascript.
	 *
	 * @access public
	 * @since  1.0.0
	 *
	 * @param string $hook Hook.
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook = '' ) {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
	} // End admin_enqueue_scripts ()

	/**
	 * Load plugin localisation
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function load_localisation() {
		load_plugin_textdomain( 'pdf-shortcodes-ultimate', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_localisation ()

	/**
	 * Load plugin pdf-shortcodes-ultimate
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$domain = 'pdf-shortcodes-ultimate';

		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	} // End load_plugin_textdomain ()

	/**
	 * Main PDF_Shortcodes_Ultimate Instance
	 *
	 * Ensures only one instance of PDF_Shortcodes_Ultimate is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see PDF_Shortcodes_Ultimate()
	 *
	 * @param string $file    File pathname.
	 * @param string $version Version number.
	 * @return Main PDF_Shortcodes_Ultimate instance
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	} // End instance ()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __clone ()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	} // End __wakeup ()

	/**
	 * Installation. Runs on activation.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return bool false if Shortcodes Ultimate plugin not activated.
	 */
	public function install() {
		$this->_log_version_number();

		if ( ! function_exists( 'shortcodes_ultimate' ) ) {
			// @link https://codex.wordpress.org/Function_Reference/register_activation_hook#Process_Flow
			add_option( 'Shortcodes_Ultimate_Plugin_Not_Activated', true );

			return false;
		}

		// Reset Shortcodes Ultimate cache.
		delete_transient( 'su/generator/popup' );
		delete_transient( 'su/generator/settings/pdf' );
		Su_Generator::reset();

		return true;
	} // End install ()

	/**
	 * Log the plugin version number.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	private function _log_version_number() {
		update_option( $this->_token . '_version', $this->_version );
	} // End _log_version_number ()


	/**
	 * Display warning to user: should activate Shortcodes Ultimate plugin.
	 *
	 * @access  public
	 * @since 1.0.0
	 */
	public function no_shortcodes_ultimate_admin_notice__warning() {
		$search_plugin_url = admin_url( 'plugin-install.php?tab=search&s=shortcodes+ultimate' );
		?>
		<div class="notice notice-warning"><p>
			<strong><?php esc_html_e( 'PDF Shortcodes Ultimate', 'pdf-shortcodes-ultimate' ); ?></strong>:
			<?php
			esc_html_e(
				'Please activate the Shortcodes Ultimate plugin, or install it:',
				'pdf-shortcodes-ultimate'
			);
			?>
			<a href="<?php echo esc_url( $search_plugin_url ); ?>">
				<?php echo esc_url( $search_plugin_url ); ?>
			</a>
		</p></div>
		<?php
	}
}
