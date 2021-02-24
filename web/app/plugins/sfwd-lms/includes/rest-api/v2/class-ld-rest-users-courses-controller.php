<?php
/**
 * LearnDash V2 REST API Users Courses Controller.
 *
 * @package LearnDash
 * @subpackage REST_API
 * @since 3.3.0
 */

/**
 * This Controller class is used to GET/UPDATE/DELETE the association
 * between a User and Courses (sfwd-courses).
 *
 * This class extends the LD_REST_Posts_Controller_V2 class.
 *
 * @since 3.3.0
 */
if ( ( ! class_exists( 'LD_REST_Users_Courses_Controller_V2' ) ) && ( class_exists( 'LD_REST_Posts_Controller_V2' ) ) ) {
	/**
	 * Class REST API Courses Post Controller.
	 */
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound
	class LD_REST_Users_Courses_Controller_V2 extends LD_REST_Posts_Controller_V2 {

		/**
		 * Public constructor for class
		 */
		public function __construct() {
			$this->post_type  = learndash_get_post_type_slug( 'course' );
			$this->taxonomies = array();

			parent::__construct( $this->post_type );

			/**
			 * Set the rest_base after the parent __constructor
			 * as it will set these var with WP specific details.
			 */
			$this->rest_base     = $this->get_rest_base( 'users' );
			$this->rest_sub_base = $this->get_rest_base( 'users-courses' );
		}

		/**
		 * Registers the routes for the objects of the controller.
		 *
		 * @since 3.3.0
		 *
		 * @see register_rest_route()
		 */
		public function register_routes() {

			$schema = $this->get_item_schema();

			$get_item_args = array(
				'context' => $this->get_context_param( array( 'default' => 'view' ) ),
			);

			register_rest_route(
				$this->namespace,
				'/' . $this->rest_base . '/(?P<id>[\d]+)/' . $this->rest_sub_base,
				array(
					'args'   => array(
						'id' => array(
							'description' => esc_html__( 'User ID', 'learndash' ),
							'required'    => true,
							'type'        => 'integer',
						),
					),
					array(
						'methods'             => WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_items' ),
						'permission_callback' => array( $this, 'get_items_permissions_check' ),
						'args'                => $this->get_collection_params(),
					),
					array(
						'methods'             => WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'update_user_courses' ),
						'permission_callback' => array( $this, 'update_user_courses_permissions_check' ),
						'args'                => array(
							'course_ids' => array(
								'description' => sprintf(
									// translators: placeholder: Course.
									esc_html_x(
										'%s IDs to add to User.',
										'placeholder: course',
										'learndash'
									),
									LearnDash_Custom_Label::get_label( 'course' )
								),
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
						'callback'            => array( $this, 'delete_user_courses' ),
						'permission_callback' => array( $this, 'delete_user_courses_permissions_check' ),
						'args'                => array(
							'course_ids' => array(
								'description' => sprintf(
									// translators: placeholder: Course.
									esc_html_x(
										'%s IDs to remove from User.',
										'placeholder: course',
										'learndash'
									),
									LearnDash_Custom_Label::get_label( 'course' )
								),
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

			$schema['title']  = 'user-courses';
			$schema['parent'] = '';

			return $schema;
		}

		/**
		 * Checks if a given request has access to read user courses.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
		 */
		public function get_items_permissions_check( $request ) {
			if ( learndash_is_admin_user() ) {
				return true;
			} elseif ( get_current_user_id() == $request['id'] ) {
				return true;
			}
		}

		/**
		 * Checks if a given request has access to update user courses.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
		 */
		public function update_user_courses_permissions_check( $request ) {
			if ( learndash_is_admin_user() ) {
				return true;
			} elseif ( get_current_user_id() == $request['id'] ) {
				return true;
			}
		}

		/**
		 * Checks if a given request has access to delete user courses.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return true|WP_Error True if the request has read access, WP_Error object otherwise.
		 */
		public function delete_user_courses_permissions_check( $request ) {
			if ( learndash_is_admin_user() ) {
				return true;
			} elseif ( get_current_user_id() == $request['id'] ) {
				return true;
			}
		}

		/**
		 * Update a user courses.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function update_user_courses( $request ) {
			$user_id = $request['id'];
			if ( empty( $user_id ) ) {
				return new WP_Error( 'rest_post_invalid_id', esc_html__( 'Invalid User ID.', 'learndash' ) . ' ' . __CLASS__, array( 'status' => 404 ) );
			}

			$course_ids = $request['course_ids'];
			if ( ( ! is_array( $course_ids ) ) || ( empty( $course_ids ) ) ) {
				return new WP_Error(
					'rest_post_invalid_id',
					sprintf(
						// translators: placeholder: Course.
						esc_html_x(
							'Missing %s ID',
							'placeholder: Course',
							'learndash'
						),
						LearnDash_Custom_Label::get_label( 'course' )
					) . ' ' . __CLASS__,
					array( 'status' => 404 )
				);
			} else {
				$course_ids = array_map( 'intval', $course_ids );
			}

			foreach ( $course_ids as $course_id ) {
				ld_update_course_access( $user_id, $course_id, false );
			}

			$data = array();

			// Create the response object
			$response = rest_ensure_response( $data );

			// Add a custom status code
			$response->set_status( 200 );

			return $response;
		}

		/**
		 * Delete a user courses.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function delete_user_courses( $request ) {
			$user_id = $request['id'];
			if ( empty( $user_id ) ) {
				return new WP_Error( 'rest_post_invalid_id', esc_html__( 'Invalid User ID.', 'learndash' ) . ' ' . __CLASS__, array( 'status' => 404 ) );
			}

			$course_ids = $request['course_ids'];
			if ( ( ! is_array( $course_ids ) ) || ( empty( $course_ids ) ) ) {
				return new WP_Error(
					'rest_post_invalid_id',
					sprintf(
						// translators: placeholder: Course.
						esc_html_x(
							'Missing %s ID',
							'placeholder: Course',
							'learndash'
						),
						LearnDash_Custom_Label::get_label( 'course' )
					) . ' ' . __CLASS__,
					array( 'status' => 404 )
				);
			} else {
				$course_ids = array_map( 'intval', $course_ids );
			}

			foreach ( $course_ids as $course_id ) {
				ld_update_course_access( $user_id, $course_id, true );
			}

			$data = array();

			// Create the response object
			$response = rest_ensure_response( $data );

			// Add a custom status code
			$response->set_status( 200 );

			return $response;
		}

		/**
		 * Filter Users Courses query args.
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
				$user_id = $request['id'];
				if ( empty( $user_id ) ) {
					return new WP_Error( 'rest_user_invalid_id', esc_html__( 'Invalid User ID.', 'learndash' ), array( 'status' => 404 ) );
				}

				if ( is_user_logged_in() ) {
					$current_user_id = get_current_user_id();
				} else {
					$current_user_id = 0;
				}

				$query_args['post__in'] = array( 0 );
				if ( ! empty( $current_user_id ) ) {
					$course_ids = learndash_user_get_enrolled_courses( $user_id, array(), true );
					if ( ! empty( $course_ids ) ) {
						$query_args['post__in'] = $course_ids;
					}
				}
			}
			return $query_args;
		}

		/**
		 * Override the REST response links.
		 *
		 * @since 3.3.0
		 *
		 * @param object $response WP_REST_Response instance.
		 * @param object $post     WP_Post instance.
		 * @param object $request  WP_REST_Request instance.
		 */
		public function rest_prepare_response_filter( WP_REST_Response $response, WP_Post $post, WP_REST_Request $request ) {
			$user_id = (int) $request['id'];
			if ( ! empty( $user_id ) ) {
				// Need to compare the requested route to this controller route.
				$route_url    = $request->get_route();
				$ld_route_url = '/' . $this->namespace . '/' . $this->rest_base . '/' . $user_id . '/courses';
				if ( ( ! empty( $route_url ) ) && ( $ld_route_url === $route_url ) && ( $post->post_type === $this->post_type ) ) {
					$current_links = $response->get_links();

					if ( ! empty( $current_links ) ) {
						foreach ( $current_links as $rel => $links ) {
							if ( in_array( $rel, array( 'self', 'collection' ), true ) ) {
								$links_changed = false;
								foreach ( $links as $lidx => $link ) {
									if ( ( isset( $link['href'] ) ) && ( ! empty( $link['href'] ) ) ) {
										$link_href = str_replace(
											'/' . $this->namespace . '/' . $this->rest_base,
											'/' . $this->namespace . '/' . $this->get_rest_base( 'courses' ),
											$link['href']
										);
										if ( $link['href'] !== $link_href ) {
											$links[ $lidx ]['href'] = $link_href;
											$links_changed          = true;
										}
									}
								}

								if ( true === $links_changed ) {
									$response->remove_link( $rel );
									$response->add_links( array( $rel => $links ) );
								}
							}
						}
					}
				}
			}

			return $response;
		}

		// End of functions.
	}
}
