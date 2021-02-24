<?php
/**
 * LearnDash V2 REST API Groups Admin/Leader Users Controller.
 *
 * @package LearnDash
 * @subpackage REST_API
 * @since 3.3.0
 */

/**
 * This Controller class is used to GET/UPDATE/DELETE the association
 * between a Course (sfwd-courses) and Group Admin (Group Leader).
 *
 * This class extends the LD_REST_Users_Controller_V2 class.
 *
 * @since 3.3.0
 */
if ( ( ! class_exists( 'LD_REST_Groups_Leaders_Controller_V2' ) ) && ( class_exists( 'LD_REST_Users_Controller_V2' ) ) ) {
	/**
	 * Class REST API Courses Post Controller.
	 */
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound
	class LD_REST_Groups_Leaders_Controller_V2 extends LD_REST_Users_Controller_V2 {

		/**
		 * Public constructor for class
		 */
		public function __construct() {

			parent::__construct();

			/**
			 * Set the rest_base after the parent __constructor
			 * as it will set these var with WP specific details.
			 */
			$this->rest_base     = $this->get_rest_base( 'groups' );
			$this->rest_sub_base = $this->get_rest_base( 'groups-leaders' );
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
			if ( isset( $schema['properties']['password'] ) ) {
				$get_item_args['password'] = array(
					'description' => esc_html__( 'The password for the post if it is password protected.', 'learndash' ),
					'type'        => 'string',
				);
			}

			register_rest_route(
				$this->namespace,
				'/' . $this->rest_base . '/(?P<id>[\d]+)/' . $this->rest_sub_base,
				array(
					'args'   => array(
						'id' => array(
							'description' => esc_html__( 'Group ID', 'learndash' ),
							'required'    => true,
							'type'        => 'integer',
						),
					),
					array(
						'methods'             => WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_groups_leaders' ),
						'permission_callback' => array( $this, 'get_groups_leaders_permissions_check' ),
						'args'                => $this->get_collection_params(),
					),
					array(
						'methods'             => WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'update_groups_leaders' ),
						'permission_callback' => array( $this, 'update_groups_leaders_permissions_check' ),
						'args'                => array(
							'user_ids' => array(
								'description' => esc_html__( 'Group Leader User IDs to enroll into Group.', 'learndash' ),
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
						'callback'            => array( $this, 'delete_groups_leaders' ),
						'permission_callback' => array( $this, 'delete_groups_leaders_permissions_check' ),
						'args'                => array(
							'user_ids' => array(
								'description' => esc_html__( 'Group Leader User IDs to remove from Group.', 'learndash' ),
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

			$schema['title']  = 'group-leaders';
			$schema['parent'] = 'groups';

			return $schema;
		}

		/**
		 * Permissions check for getting group leaders.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return true|WP_Error True if the request has read access, otherwise WP_Error object.
		 */
		public function get_groups_leaders_permissions_check( $request ) {
			if ( learndash_is_admin_user() ) {
				return true;
			}
		}

		/**
		 * Checks permission to update group leaders.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return true|WP_Error True if the request has access to update the item, WP_Error object otherwise.
		 */
		public function update_groups_leaders_permissions_check( $request ) {
			if ( learndash_is_admin_user() ) {
				return true;
			}
		}

		/**
		 * Checks permission to delete group leaders.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return true|WP_Error True if the request has access to update the item, WP_Error object otherwise.
		 */
		public function delete_groups_leaders_permissions_check( $request ) {
			if ( learndash_is_admin_user() ) {
				return true;
			}
		}

		/**
		 * Get a group leaders.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function get_groups_leaders( $request ) {
			return parent::get_items( $request );
		}

		/**
		 * Updates a group leaders.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function update_groups_leaders( $request ) {
			$group_id = $request['id'];
			if ( empty( $group_id ) ) {
				return new WP_Error( 'rest_post_invalid_id', esc_html__( 'Invalid group ID.', 'learndash' ) . ' ' . __CLASS__, array( 'status' => 404 ) );
			}

			$user_ids = $request['user_ids'];
			if ( ( ! is_array( $user_ids ) ) || ( empty( $user_ids ) ) ) {
				return new WP_Error( 'rest_post_invalid_id', esc_html__( 'Missing Group Leader User IDs.', 'learndash' ) . ' ' . __CLASS__, array( 'status' => 404 ) );
			} else {
				$user_ids = array_map( 'intval', $user_ids );
			}

			foreach ( $user_ids as $user_id ) {
				ld_update_leader_group_access( $user_id, $group_id, false );
			}

			$data = array();

			// Create the response object
			$response = rest_ensure_response( $data );

			// Add a custom status code
			$response->set_status( 200 );

			return $response;
		}

		/**
		 * Delete a group leaders.
		 *
		 * @since 3.3.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 *
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function delete_groups_leaders( $request ) {
			$group_id = $request['id'];
			if ( empty( $group_id ) ) {
				return new WP_Error( 'rest_post_invalid_id', esc_html__( 'Invalid group ID.', 'learndash' ) . ' ' . __CLASS__, array( 'status' => 404 ) );
			}

			$user_ids = $request['user_ids'];
			if ( ( ! is_array( $user_ids ) ) || ( empty( $user_ids ) ) ) {
				return new WP_Error( 'rest_post_invalid_id', esc_html__( 'Missing Group Leader User IDs.', 'learndash' ) . ' ' . __CLASS__, array( 'status' => 404 ) );
			} else {
				$user_ids = array_map( 'intval', $user_ids );
			}

			foreach ( $user_ids as $user_id ) {
				ld_update_leader_group_access( $user_id, $group_id, true );
			}

			$data = array();

			// Create the response object
			$response = rest_ensure_response( $data );

			// Add a custom status code
			$response->set_status( 200 );

			return $response;
		}

		/**
		 * Filter Groups Leaders Users query args.
		 *
		 * @since 3.3.0
		 *
		 * @param array           $query_args Key value array of query var to query value.
		 * @param WP_REST_Request $request    The request used.
		 *
		 * @return array          $query_args Key value array of query var to query value.
		 */
		public function rest_query_filter( $query_args, $request ) {
			$query_args = parent::rest_query_filter( $query_args, $request );

			$route_url    = $request->get_route();
			$ld_route_url = '/' . $this->namespace . '/' . $this->rest_base . '/' . absint( $request['id'] ) . '/' . $this->rest_sub_base;
			if ( ( ! empty( $route_url ) ) && ( $ld_route_url === $route_url ) ) {
				$group_id = (int) $request['id'];
				if ( empty( $group_id ) ) {
					return new WP_Error( 'rest_post_invalid_id', esc_html__( 'Invalid Group ID.', 'learndash' ), array( 'status' => 404 ) );
				}

				if ( is_user_logged_in() ) {
					$current_user_id = get_current_user_id();
				} else {
					$current_user_id = 0;
				}

				$query_args['include'] = array( 0 );
				if ( ! empty( $current_user_id ) ) {
					$group_user_ids = learndash_get_groups_administrator_ids( $group_id );
					if ( ! empty( $group_user_ids ) ) {
						$query_args['include'] = $group_user_ids;
					}
				}
			}

			return $query_args;
		}

		// End of functions
	}
}
