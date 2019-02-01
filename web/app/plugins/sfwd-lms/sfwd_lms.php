<?php
/**
 * Plugin Name: LearnDash LMS
 * Plugin URI: http://www.learndash.com
 * Description: LearnDash LMS Plugin - Turn your WordPress site into a learning management system.
 * Version: 2.6.4
 * Author: LearnDash
 * Author URI: http://www.learndash.com
 * Text Domain: learndash
 * Doman Path: /languages/
 *
 * @since 2.1.0
 *
 * @package LearnDash
 */

/**
 * LearnDash Version Constant
 */
define( 'LEARNDASH_VERSION', '2.6.4' );
define( 'LEARNDASH_SETTINGS_DB_VERSION', '2.5' );
define( 'LEARNDASH_SETTINGS_TRIGGER_UPGRADE_VERSION', '2.5' );
define( 'LEARNDASH_LMS_TEXT_DOMAIN', 'learndash' );

if ( ! defined( 'LEARNDASH_LMS_PLUGIN_DIR' ) ) {
	define( 'LEARNDASH_LMS_PLUGIN_DIR', trailingslashit( str_replace( '\\', '/', WP_PLUGIN_DIR ) . '/' . basename( dirname( __FILE__ ) ) ) );
}
if ( ! defined( 'LEARNDASH_LMS_PLUGIN_URL' ) ) {
	$url = trailingslashit( WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) );
	$url = str_replace( array( 'https://', 'http://' ), array( '//', '//' ), $url );
	define( 'LEARNDASH_LMS_PLUGIN_URL', $url );
}

if ( ! defined( 'LEARNDASH_LMS_PLUGIN_KEY' ) ) {
	$current_plugin_dir = LEARNDASH_LMS_PLUGIN_DIR;
	$current_plugin_dir = basename( $current_plugin_dir ) . '/' . basename( __FILE__ );
	define( 'LEARNDASH_LMS_PLUGIN_KEY', $current_plugin_dir );
}

if ( ! defined( 'LEARNDASH_TRANSIENTS_DISABLED' ) ) {
	define( 'LEARNDASH_TRANSIENTS_DISABLED', false );
}

if ( ! defined( 'LEARNDASH_DEBUG' ) ) {
	define( 'LEARNDASH_DEBUG', false );
}

// If the WordPress 'SCRIPT_DEBUG' is set then we also set our 'LEARNDASH_SCRIPT_DEBUG' so we are serving non-minified scripts.
if ( ! defined( 'LEARNDASH_SCRIPT_DEBUG' ) ) {
	if ( ( defined( 'SCRIPT_DEBUG' ) ) && ( SCRIPT_DEBUG === true ) ) {
		define( 'LEARNDASH_SCRIPT_DEBUG', true );
	} else {
		define( 'LEARNDASH_SCRIPT_DEBUG', false );
	}
}

//define( 'LEARNDASH_SCRIPT_VERSION_TOKEN', 'debug' );
if ( ! defined( 'LEARNDASH_SCRIPT_VERSION_TOKEN' ) ) {
	if ( defined( 'LEARNDASH_SCRIPT_DEBUG' ) && ( LEARNDASH_SCRIPT_DEBUG === true ) ) {
		define( 'LEARNDASH_SCRIPT_VERSION_TOKEN', LEARNDASH_VERSION . '-' . time() );

	} else {
		define( 'LEARNDASH_SCRIPT_VERSION_TOKEN', LEARNDASH_VERSION );
	}
}

// Added to support REST API.
// @since 2.5.8.
if ( ! defined( 'LEARNDASH_REST_API_ENABLED' ) ) {
	define( 'LEARNDASH_REST_API_ENABLED', false );
}

// Added to support Lesson/Topic videos
// @since 2.4.5.
if ( ! defined( 'LEARNDASH_LESSON_VIDEO' ) ) {
	define( 'LEARNDASH_LESSON_VIDEO', true );
}

// Added to support Course Builder
// @since 2.5.
if ( ! defined( 'LEARNDASH_COURSE_BUILDER' ) ) {
	define( 'LEARNDASH_COURSE_BUILDER', true );
}

// Added to support Quiz Builder
// @since 2.6.0.
if ( ! defined( 'LEARNDASH_QUIZ_BUILDER' ) ) {
	define( 'LEARNDASH_QUIZ_BUILDER', true );
}

// Added to support Gutenberg Editor
// @since 2.5.8.
if ( ! defined( 'LEARNDASH_GUTENBERG' ) ) {
	define( 'LEARNDASH_GUTENBERG', true );
}

// Added to support Translations via GlotPress
// @since 2.5.1.1.
if ( ! defined( 'LEARNDASH_TRANSLATIONS' ) ) {
	define( 'LEARNDASH_TRANSLATIONS', true );
}

// Added to support Add-on Update logic
// @since 2.5.5.
if ( ! defined( 'LEARNDASH_ADDONS_UPDATER' ) ) {
	define( 'LEARNDASH_ADDONS_UPDATER', true );
}

// Added to support legacy tcpdf version.
// @since 2.6.2.
if ( ! defined( 'LEARNDASH_TCPDF_LEGACY' ) ) {
	define( 'LEARNDASH_TCPDF_LEGACY', false );
}

if ( ! defined( 'LEARNDASH_LMS_DEFAULT_QUESTION_POINTS' ) ) {
	define( 'LEARNDASH_LMS_DEFAULT_QUESTION_POINTS', 1 );
}

if ( ! defined( 'LEARNDASH_LMS_DEFAULT_ANSWER_POINTS' ) ) {
	define( 'LEARNDASH_LMS_DEFAULT_ANSWER_POINTS', 0 );
}

// Define the number of items to lazy load per AJAX request.
if ( ! defined( 'LEARNDASH_LMS_DEFAULT_LAZY_LOAD_PER_PAGE' ) ) {
	define( 'LEARNDASH_LMS_DEFAULT_LAZY_LOAD_PER_PAGE', 5000 );
}

// Define the number of items for Data Upgrade batch.
if ( ! defined( 'LEARNDASH_LMS_DEFAULT_DATA_UPGRADE_BATCH_SIZE' ) ) {
	define( 'LEARNDASH_LMS_DEFAULT_DATA_UPGRADE_BATCH_SIZE', 1000 );
}

// Define the default number of items per page.
if ( ! defined( 'LEARNDASH_LMS_DEFAULT_WIDGET_PER_PAGE' ) ) {
	define( 'LEARNDASH_LMS_DEFAULT_WIDGET_PER_PAGE', 20 );
}

if ( ! defined( 'LEARNDASH_LMS_DEFAULT_CB_INSERT_CHUNK_SIZE' ) ) {
	define( 'LEARNDASH_LMS_DEFAULT_CB_INSERT_CHUNK_SIZE', 10 );
}

// Define what administrator cabability to check.
if ( ! defined( 'LEARNDASH_ADMIN_CAPABILITY_CHECK' ) ) {
	define( 'LEARNDASH_ADMIN_CAPABILITY_CHECK', 'manage_options' );
}

if ( ! defined( 'LEARNDASH_GROUP_LEADER_CAPABILITY_CHECK' ) ) {
	define( 'LEARNDASH_GROUP_LEADER_CAPABILITY_CHECK', 'group_leader' );
}

/**
 * LearnDash Database utility class.
 */
require_once dirname( __FILE__ ) . '/includes/class-ldlms-db.php';

/**
 * LearnDash Post Types utility class.
 */
require_once dirname( __FILE__ ) . '/includes/class-ldlms-post-types.php';


/**
 * The module base class; handles settings, options, menus, metaboxes, etc.
 */
require_once dirname( __FILE__ ) . '/includes/class-ld-semper-fi-module.php';

/**
 * SFWD_LMS
 */
require_once dirname( __FILE__ ) . '/includes/class-ld-lms.php';

/**
 * Register CPT's and Taxonomies
 */
require_once dirname( __FILE__ ) . '/includes/class-ld-cpt.php';

/**
 * Register CPT's and Taxonomies
 */
require_once dirname( __FILE__ ) . '/includes/class-ld-cpt-instance.php';

/**
 * LearnDash Menus and Tabs logic
 */
require_once LEARNDASH_LMS_PLUGIN_DIR . 'includes/admin/class-learndash-admin-menus-tabs.php';

/**
 * Registers widget for displaying a list of lessons for a course and tracks lesson progress.
 */
require_once dirname( __FILE__ ) . '/includes/class-ld-cpt-widget.php';

/**
 * Course functions
 */
require_once dirname( __FILE__ ) . '/includes/course/ld-course-functions.php';

/**
 * Course navigation
 */
require_once dirname( __FILE__ ) . '/includes/course/ld-course-navigation.php';

/**
 * Course progress functions
 */
require_once dirname( __FILE__ ) . '/includes/course/ld-course-progress.php';

/**
 * Course, lesson, topic, quiz list shortcodes and helper functions
 */
require_once dirname( __FILE__ ) . '/includes/course/ld-course-list-shortcode.php';

/**
 * Course info and navigation widgets
 */
require_once dirname( __FILE__ ) . '/includes/course/ld-course-info-widget.php';

/**
 * Quiz and Question functions
 */
require_once dirname( __FILE__ ) . '/includes/quiz/ld-quiz-functions.php';

/**
 * Implements WP Pro Quiz
 */
require_once dirname( __FILE__ ) . '/includes/quiz/ld-quiz-pro.php';

/**
 * Shortcodes for displaying Quiz and Course info
 */
require_once dirname( __FILE__ ) . '/includes/quiz/ld-quiz-info-shortcode.php';

/**
 * Quiz migration functions
 */
require_once dirname( __FILE__ ) . '/includes/quiz/ld-quiz-migration.php';

/**
 * Quiz essay question functions
 */
require_once dirname( __FILE__ ) . '/includes/quiz/ld-quiz-essays.php';

/**
 * Load scripts & styles
 */
require_once dirname( __FILE__ ) . '/includes/ld-scripts.php';

/**
 * Customizations to wp editor for LearnDash
 */
require_once dirname( __FILE__ ) . '/includes/ld-wp-editor.php';

/**
 * Handles assignment uploads and includes helper functions for assignments
 */
require_once dirname( __FILE__ ) . '/includes/ld-assignment-uploads.php';

/**
 * Group functions
 */
require_once dirname( __FILE__ ) . '/includes/ld-groups.php';

/**
 * User functions
 */
require_once dirname( __FILE__ ) . '/includes/ld-users.php';

/**
 * Certificate functions
 */
require_once dirname( __FILE__ ) . '/includes/ld-certificates.php';

/**
 * Misc functions
 */
require_once dirname( __FILE__ ) . '/includes/ld-misc-functions.php';

/**
 * WP-admin functions
 */
require_once dirname( __FILE__ ) . '/includes/admin/ld-admin.php';

/**
 * LearnDash Settings Page Base
 */
require_once dirname( __FILE__ ) . '/includes/settings/settings-loader.php';

/**
 * Custom label
 */
require_once dirname( __FILE__ ) . '/includes/class-ld-custom-label.php';

/**
 * Binary Selector
 */
require_once dirname( __FILE__ ) . '/includes/admin/class-learndash-admin-binary-selector.php';

/**
 * Data/System Upgrades
 */
require_once dirname( __FILE__ ) . '/includes/admin/class-learndash-admin-data-upgrades.php';

/**
 * Reports
 */
require_once dirname( __FILE__ ) . '/includes/admin/class-learndash-admin-settings-data-reports.php';

/**
 * Reports Functions
 */
require_once dirname( __FILE__ ) . '/includes/ld-reports.php';

/**
 * Permalinks
 */
require_once dirname( __FILE__ ) . '/includes/class-ld-permalinks.php';

/**
 * GDPR
 */
require_once dirname( __FILE__ ) . '/includes/class-ld-gdpr.php';

/**
 * Core Updater
 */
require_once dirname( __FILE__ ) . '/includes/ld-autoupdate.php';

/**
 * Addon Updater API
 */
if ( ( defined( 'LEARNDASH_ADDONS_UPDATER' ) ) && ( LEARNDASH_ADDONS_UPDATER === true ) ) {
	require_once dirname( __FILE__ ) . '/includes/class-ld-addons-updater.php';
}

/**
 * Translations
 */
if ( ( defined( 'LEARNDASH_TRANSLATIONS' ) ) && ( LEARNDASH_TRANSLATIONS === true ) ) {
	require_once dirname( __FILE__ ) . '/includes/class-ld-translations.php';

	if ( ! defined( 'LEARNDASH_TRANSLATIONS_URL_BASE' ) ) {
		define( 'LEARNDASH_TRANSLATIONS_URL_BASE', 'http://translations.learndash.com' );
	}
	if ( ! defined( 'LEARNDASH_TRANSLATIONS_URL_CACHE' ) ) {
		define( 'LEARNDASH_TRANSLATIONS_URL_CACHE', DAY_IN_SECONDS );
	}
}

/**
 * Registers Shortcodes.
 */
require_once dirname( __FILE__ ) . '/includes/settings/class-ld-shortcodes-tinymce.php';

require_once LEARNDASH_LMS_PLUGIN_DIR . '/includes/classes/class-ldlms-factory-post.php';

/**
 * Registers REST API Hooks.
 */
require_once dirname( __FILE__ ) . '/includes/rest-api/class-ld-rest-api.php';

/**
 * Load our Import/Export Utilities
 */
require_once dirname( __FILE__ ) . '/includes/import/import-loader.php';

/**
 * Support for Video Progression
 */
if ( ( defined( 'LEARNDASH_LESSON_VIDEO' ) ) && ( LEARNDASH_LESSON_VIDEO === true ) ) {
	require_once dirname( __FILE__ ) . '/includes/course/ld-course-video.php';
}

/**
 * Support for Course and/or Quiz Builder
 */
require_once dirname( __FILE__ ) . '/includes/admin/class-learndash-admin-builder.php';

/**
 * Support for Gutenberg Editor
 */
if ( ( defined( 'LEARNDASH_GUTENBERG' ) ) && ( LEARNDASH_GUTENBERG === true ) ) {
	require_once dirname( __FILE__ ) . '/includes/gutenberg/index.php';
}

/**
 * LearnDash Deprecated Functions/Classes
 */
require_once dirname( __FILE__ ) . '/includes/deprecated/deprecated-functions.php';


/**
 * Globals that hold CPT's and Pages to be set up
 */
global $learndash_taxonomies, $learndash_pages, $learndash_question_types;

$learndash_taxonomies = array(
	'ld_course_category',
	'ld_course_tag',
	'ld_lesson_category',
	'ld_lesson_tag',
	'ld_topic_category',
	'ld_topic_tag',
	'ld_quiz_category',
	'ld_quiz_tag',
	'ld_question_category',
	'ld_question_tag',
);

$learndash_pages = array(
	'group_admin_page',
	'learndash-lms-certificate_shortcodes',
	'learndash-lms-course_shortcodes',
	'learndash-lms-reports',
	'ldAdvQuiz',
);

$learndash_course_statuses = array(
	'not_started' => esc_html__( 'Not Started', 'learndash' ),
	'in_progress' => esc_html__( 'In Progress', 'learndash' ),
	'complete'    => esc_html__( 'Completed', 'learndash' ),
);

$learndash_question_types = array(
	'single'             => esc_html__( 'Single choice', 'learndash' ),
	'multiple'           => esc_html__( 'Multiple choice', 'learndash' ),
	'free_answer'        => esc_html__( '"Free" choice', 'learndash' ),
	'sort_answer'        => esc_html__( '"Sorting" choice', 'learndash' ),
	'matrix_sort_answer' => esc_html__( '"Matrix Sorting" choice', 'learndash' ),
	'cloze_answer'       => esc_html__( 'Fill in the blank', 'learndash' ),
	'assessment_answer'  => esc_html__( 'Assessment', 'learndash' ),
	'essay'              => esc_html__( 'Essay / Open Answer', 'learndash' ),
);

// This is a global variable which is set in any of the shortcode handler functions.
// The purpose is to let the plugin know when and if the any of the shortcodes were used.
global $learndash_shortcode_used;
$learndash_shortcode_used = false;

global $learndash_shortcode_atts;
$learndash_shortcode_atts = array();

global $learndash_assets_loaded;
$learndash_assets_loaded            = array();
$learndash_assets_loaded['styles']  = array();
$learndash_assets_loaded['scripts'] = array();