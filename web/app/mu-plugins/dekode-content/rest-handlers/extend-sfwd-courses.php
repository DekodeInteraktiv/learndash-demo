<?php
/**
 * Extend Learndash courses endpoint
 */
$object = 'sfwd-courses';

// Add the full taxonomy term objects.
register_rest_field( $object, 'ld_course_category', [
    'get_callback' => function ( $object ) {
        $terms = get_the_terms( $object['id'], 'ld_course_category' );
        if ( empty( $terms->errors ) ) {
            return $terms;
        }
        return [];
    },
    'schema'       => [
        'context'     => [ 'view' ],
        'description' => 'Learndash course categories',
        'type'        => 'array',
        'items'       => [
            'type' => 'object',
        ],
        'readonly'    => true,
    ],
] );

// Add the full taxonomy term objects.
register_rest_field( $object, 'ld_course_tag', [
    'get_callback' => function ( $object ) {
        $terms = get_the_terms( $object['id'], 'ld_course_tag' );
        if ( empty( $terms->errors ) ) {
            return $terms;
        }
        return [];
    },
    'schema'       => [
        'context'     => [ 'view' ],
        'description' => 'Learndash course tags',
        'type'        => 'array',
        'items'       => [
            'type' => 'object',
        ],
        'readonly'    => true,
    ],
] );
