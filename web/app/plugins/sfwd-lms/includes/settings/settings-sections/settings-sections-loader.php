<?php
/**
 * LearnDash Settings Sections Loader.
 *
 * @package LearnDash
 * @subpackage Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/class-ld-settings-section-side-submit.php';
require_once __DIR__ . '/class-ld-settings-section-side-quick-links.php';

// Custom Labels Page.
require_once __DIR__ . '/class-ld-settings-section-custom-labels.php';

// Course Options.
require_once __DIR__ . '/class-ld-settings-section-courses-management-display.php';
require_once __DIR__ . '/class-ld-settings-section-courses-taxonomies.php';
require_once __DIR__ . '/class-ld-settings-section-courses-cpt.php';

// Lessons Options.
//require_once 'class-ld-settings-section-lessons-display-order.php';
require_once __DIR__ . '/class-ld-settings-section-lessons-taxonomies.php';
require_once __DIR__ . '/class-ld-settings-section-lessons-cpt.php';

// Topics Options.
require_once __DIR__ . '/class-ld-settings-section-topics-taxonomies.php';
require_once __DIR__ . '/class-ld-settings-section-topics-cpt.php';

// Quizzes Options
require_once __DIR__ . '/class-ld-settings-section-quizzes-management-display.php';
require_once __DIR__ . '/class-ld-settings-section-quizzes-email-settings.php';
require_once __DIR__ . '/class-ld-settings-section-quizzes-taxonomies.php';
require_once __DIR__ . '/class-ld-settings-section-quizzes-cpt.php';

// Question Options.
require_once __DIR__ . '/class-ld-settings-section-questions-taxonomies.php';
require_once __DIR__ . '/class-ld-settings-section-questions-management-display.php';

// Certificate Options.
require_once __DIR__ . '/class-ld-settings-section-certificates-cpt.php';
require_once __DIR__ . '/class-ld-settings-section-certificates-styles.php';

// Groups
require_once __DIR__ . '/class-ld-settings-section-groups-group-leader-user.php';
require_once __DIR__ . '/class-ld-settings-section-groups-management-display.php';
require_once __DIR__ . '/class-ld-settings-section-groups-cpt.php';
require_once __DIR__ . '/class-ld-settings-section-groups-taxonomies.php';
require_once __DIR__ . '/class-ld-settings-section-groups-membership.php';


// Settings General tab.
require_once __DIR__ . '/class-ld-settings-section-courses-themes.php';
require_once __DIR__ . '/class-ld-settings-section-general-per-page.php';
require_once __DIR__ . '/class-ld-settings-section-general-admin-user.php';
//require_once( __DIR__ . '/class-ld-settings-section-general-login-registration.php' );

if ( ( defined( 'LEARNDASH_REST_API_ENABLED' ) ) && ( true === LEARNDASH_REST_API_ENABLED ) ) {
	require_once __DIR__ . '/class-ld-settings-section-general-rest-api.php';
}

// Data Upgrades tab.
require_once __DIR__ . '/class-ld-settings-section-data-upgrades.php';

// PayPal tab.
require_once __DIR__ . '/class-ld-settings-section-paypal.php';

// Support tab.
require_once __DIR__ . '/class-ld-settings-section-support-learndash.php';
require_once __DIR__ . '/class-ld-settings-section-support-server.php';
require_once __DIR__ . '/class-ld-settings-section-support-wordpress.php';
require_once __DIR__ . '/class-ld-settings-section-support-templates.php';
require_once __DIR__ . '/class-ld-settings-section-support-database-tables.php';
require_once __DIR__ . '/class-ld-settings-section-support-wordpress-themes.php';
require_once __DIR__ . '/class-ld-settings-section-support-wordpress-plugins.php';
require_once __DIR__ . '/class-ld-settings-section-support-copy-system-info.php';
require_once __DIR__ . '/class-ld-settings-section-support-data-reset.php';


// Translations tab.
if ( ( defined( 'LEARNDASH_TRANSLATIONS' ) ) && ( LEARNDASH_TRANSLATIONS === true ) ) {
	require_once __DIR__ . '/class-ld-settings-section-translations-refresh.php';
	require_once __DIR__ . '/class-ld-settings-section-translations-learndash.php';
}

// Import/Export.
//require_once( LEARNDASH_LMS_PLUGIN_DIR . 'includes/settings/settings-sections-pages/class-ld-settings-page-import-export.php' );

// Assignments
require_once __DIR__ . '/class-ld-settings-section-assignments-cpt.php';

// Shows settings section on the WP Settings > Permalinks page.
require_once __DIR__ . '/class-ld-settings-section-permalinks.php';
require_once __DIR__ . '/class-ld-settings-section-permalinks-taxonomies.php';
