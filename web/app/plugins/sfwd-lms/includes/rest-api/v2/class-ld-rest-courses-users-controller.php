<?php
/**
 * LearnDash V2 REST API Courses Users Controller.
 *
 * @package LearnDash
 * @subpackage REST_API
 * @since 3.3.0
 */

/**
 * This Controller class is used to GET/UPDATE/DELETE the association
 * between a Course (sfwd-courses) and Users enrolled.
 *
 * This class extends the LD_REST_Posts_Controller_V2 class.
 *
 * @since 3.3.0
 */
if ( ( ! class_exists( 'LD_REST_Courses_Users_Controller_V2' ) ) && ( class_exists( 'LD_REST_Users_Controller_V2' ) ) ) {
	/**
	 * Class REST API Courses Users Controller.
	 */
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound
	class LD_REST_Courses_Users_Controller_V2 extends LD_REST_Users_Controller_V2 {

		/**
		 * Public constructor for class
		 */
		public function __construct() {
			$this->rest_sub_base = $this->get_rest_base( 'courses-users' );
			parent::__construct();
		}

		/**
		 * Registers the routes for the objects of the controller.
		 *
		 * @since 3.3.0
		 *
		 * @see register_rest_route() in WordPress core.
		 */
		public function register_routes() {
			$collection_params = $this->get_collection_params();
			$schema            = $this->get_item_schema();

			$get_item_args = array(
				'context' => $this->get_context_param( array( 'default' => 'view' ) ),
			);
			if ( isset( $schema['properties']['password'] ) ) {
				$get_item_args['password'] = array(
					'description' => esc_html__( 'The password for the post if it is password protected.', 'learndash' ),
					'type'        => 'string',
				);
			}

			/**
			 * Set the rest_base after the parent __constructor
			 * as it will set these var with WP specific details.
			 */
			$courses_rest_base = $this->get_rest_base( 'courses' );

			register_rest_route(
				$this->namespace,
				'/' . $courses_rest_base . '/(?P<id>[\d]+)/' . $this->rest_sub_base,
				array(
					'args'   => array(
						'id' => array(
							'description' => sprintf(
								// translators: placeholder: Course.
								esc_html_x(
									'%s ID.',
									'placeholder: Course',
									'learndash'
								),
								LearnDash_Custom_Label::get_label( 'course' )
							),
							'required'    => true,
							'type'        => 'integer',
						),
					),
					array(
						'methods'             => WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_courses_users' ),
						'permission_callback' => array( $this, 'get_courses_users_permissions_check' ),
						'args'                => $this->get_collection_params(),
					),
					array(
						'methods'             => WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'update_courses_users' ),
						'permission_callback' => array( $this, 'update_courses_users_permissions_check' ),
						'args'                => array(
							'user_ids' => array(
								'description' => esc_html__( 'User IDs to update in Course. Limit 50 per request.', 'learndash' ),
								'required'    => true,
								'type'        => 'array',
								'items'       => array(
									'type' => 'integer',
								),
							),
						),
					),
					array(
						'methods'             => WP_REST_Server::DELETABLE,
						'callback'            => array( $this, 'delete_courses_users' ),
						'permission_callback' => array( $this, 'delete_courses_users_permissions_check' ),
						'args'                => array(
							'user_ids' => array(
								'description' => esc_html__( 'User IDs to remove from Course. Limit 50 per request.', 'learndash' ),
								'required'    => true,
								'type'        => 'array',
								'items'       => array(
									'type' => 'integer',
								),
							),
						),
					),
					'schema' => array( $this, 'get_public_item_schema' ),
				)
			);
		}

		/**
		 * Gets public schema.
		 *
		 * @since 3.3.0
		 *
		 * @return array
		 */
		public function get_public_item_schema() {

			$schema = parent::get_public_item_schema();

			$schema['title']  = 'course-users';
			$schema['parent'] = 'course';

			return $schema;
		}

		/**
		 * Filter Course Users query args.
		 *
		 * @since 3.3.0
		 *
		 * @param array           $query_args Key value array of query var to query value.
		 * @param WP_REST_Request $request    The request used.
		 *
		 * @return array Key value array of query var to query value.
		 */
		public function rest_query_filter( $query_args, $request ) {
			$query_args = parent::rest_query_filter( $query_args, $request );

			$route_url    = $request->get_route();
			$ld_route_url = '/' . $this->namespace . '/' . $this->rest_base . '/' . absint( $request['id'] ) . '/' . $this->rest_sub_base;
			if ( ( ! empty( $route_url ) ) && ( $ld_route_url === $route_url ) ) {
				$course_id = (int) $request['id'];
				if ( ! empty( $course_id ) ) {
					$query_args['include'] = array( 0 );

					if ( LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Section_General_Admin_User', 'courses_autoenroll_admin_users' ) === 'yes' ) {
						$exclude_admin = true;
					} else {
						$exclude_admin = false;
					}

					$course_users_query = learndash_get_users_for_course( $course_id, array(), $exclude_admin );
					if ( is_a( $course_users_query, 'WP_User_Query' ) ) {
						$query_args['include'] = $course_users_query->get_results();
					}
				}
			}

			return $query_args;
		}

		/**
		 * Checks if a given request has access to read course users.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
		 */
		public function get_courses_users_permissions_check( $request ) {
			if ( learndash_is_admin_user() ) {
				return true;
			}
		}

		/**
		 * Checks if a given request has access to update a course users.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return true|WP_Error True if the request has access to update the item, WP_Error object otherwise.
		 */
		public function update_courses_users_permissions_check( $request ) {
			if ( learndash_is_admin_user() ) {
				return true;
			}
		}

		/**
		 * Checks if a given request has access to delete a course users.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return true|WP_Error True if the request has access to delete the item, WP_Error object otherwise.
		 */
		public function delete_courses_users_permissions_check( $request ) {
			if ( learndash_is_admin_user() ) {
				return true;
			}
		}

		/**
		 * Retrieves a course users.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function get_courses_users( $request ) {
			return $this->get_items( $request );
		}

		/**
		 * Updates a course users.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function update_courses_users( $request ) {
			$course_id = $request['id'];
			if ( empty( $course_id ) ) {
				return new WP_Error(
					'rest_post_invalid_id',
					sprintf(
						// translators: placeholder: Course.
						esc_html_x(
							'Invalid %s ID.',
							'placeholder: Course',
							'learndash'
						),
						LearnDash_Custom_Label::get_label( 'course' )
					) . ' ' . __CLASS__,
					array( 'status' => 404 )
				);
			}

			$user_ids = $request['user_ids'];
			if ( ( ! is_array( $user_ids ) ) || ( empty( $user_ids ) ) ) {
				return new WP_Error( 'rest_post_invalid_id', esc_html__( 'Missing User IDs.', 'learndash' ), array( 'status' => 404 ) );
			} else {
				$user_ids = array_map( 'intval', $user_ids );
			}

			foreach ( $user_ids as $user_id ) {
				ld_update_course_access( $user_id, $course_id );
			}

			$data = array();

			// Create the response object
			$response = rest_ensure_response( $data );

			// Add a custom status code
			$response->set_status( 200 );

			return $response;
		}

		/**
		 * Delete course users.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function delete_courses_users( $request ) {
			$course_id = $request['id'];
			if ( empty( $course_id ) ) {
				return new WP_Error(
					'rest_post_invalid_id',
					sprintf(
						// translators: placeholder: Course.
						esc_html_x(
							'Invalid %s ID.',
							'placeholder: Course',
							'learndash'
						),
						LearnDash_Custom_Label::get_label( 'course' )
					) . ' ' . __CLASS__,
					array( 'status' => 404 )
				);
			}

			$user_ids = $request['user_ids'];
			if ( ( ! is_array( $user_ids ) ) || ( empty( $user_ids ) ) ) {
				return new WP_Error( 'rest_post_invalid_id', esc_html__( 'Missing User IDs.', 'learndash' ), array( 'status' => 404 ) );
			} else {
				$user_ids = array_map( 'intval', $user_ids );
			}

			foreach ( $user_ids as $user_id ) {
				ld_update_course_access( $user_id, $course_id, true );
			}

			$data = array();

			// Create the response object
			$response = rest_ensure_response( $data );

			// Add a custom status code
			$response->set_status( 200 );

			return $response;
		}

		// End of functions
	}
}
