<?php
/**
 * Register custom theme mods 
 */

function ab_kirki_sections( $ab ) {
	/**
	 * Add panels
	 */
	$ab->add_panel( 'ab_customize', array(
		'priority'    => 10,
		'title'       => __( 'AB Customize', 'kirki' ),
	) );

	/**
	 * Add sections
	 */
	$ab->add_section( 'ab_general', array(
		'title'       => __( 'General', 'kirki' ),
		'priority'    => 10,
		'panel'       => 'ab_customize',
	) );
	$ab->add_section( 'ab_style', array(
		'title'       => __( 'Style', 'kirki' ),
		'priority'    => 10,
		'panel'       => 'ab_customize',
	) );
	$ab->add_section( 'ab_typo', array(
		'title'       => __( 'Typo', 'kirki' ),
		'priority'    => 10,
		'panel'       => 'ab_customize',
	) );
	$ab->add_section( 'ab_woocomerce_general', array(
		'title'       => __( 'WooCommerce', 'kirki' ),
		'priority'    => 10,
		'panel'       => 'ab_customize',
	) );
	$ab->add_section( 'ab_woocomerce_product_layout', array(
		'title'       => __( 'WooCommerce Product Layout', 'kirki' ),
		'priority'    => 10,
		'panel'       => 'ab_customize',
	) );
	$ab->add_section( 'ab_woocomerce_single_product_layout', array(
		'title'       => __( 'WooCommerce Single Product Layout', 'kirki' ),
		'priority'    => 10,
		'panel'       => 'ab_customize',
	) );
}
add_action( 'customize_register', 'ab_kirki_sections' );

function ab_kirki_fields( $fields ) {

	// General

	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_scroll_top',
		'label'       => __( 'Button Scroll Top', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
	);
	$fields[] = array(
		'type'        => 'color',
		'settings'    => 'ab_scroll_top_color',
		'label'       => __( 'Button Scroll Top Color', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '#fc6702',
		'priority'    => 10,
		'output' 	  => [
			[
				'element'  => '#ab-back-to-top',
				'property' => 'border-color',
			],
			[
				'element'  => '#ab-back-to-top',
				'property' => 'color',
			]
		]
		
	);
	
	$fields[] = array(
		'type'        => 'select',
		'settings'    => 'contact',
		'label'       => esc_html__( 'Contact Site', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => 'none',
		'placeholder' => esc_html__( 'Select an option...', 'kirki' ),
		'priority'    => 10,
		'choices'     => [
			'none' => esc_html__( 'None', 'kirki' ),
			'left' => esc_html__( 'Left', 'kirki' ),
			'right' => esc_html__( 'Right', 'kirki' ),
		],
	);

	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_ring_phone',
		'label'       => __( 'Ring Phone', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
		'active_callback' => [
		    [
		        [
		            'setting'  => 'contact',
		            'operator' => '!=',
		            'value'    => 'none',
		        ],
		    ],
		],
	);
	$fields[] = array(
		'type'        => 'text',
		'settings'    => 'ab_ring_phone_number',
		'label'       => __( 'Ring Phone Number', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '',
		'priority'    => 10,
		'active_callback' => [
		    [
		        [
		            'setting'  => 'contact',
		            'operator' => '!=',
		            'value'    => 'none',
		        ],
		    ],
		],
		
	);
	$fields[] = array(
		'type'        => 'text',
		'settings'    => 'ab_ring_phone_format_before',
		'label'       => __( 'Ring Phone Number Format Before', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '/([0-9]{3})([0-9]{3})([0-9]{4})/',
		'priority'    => 10,
		'active_callback' => [
		    [
		        [
		            'setting'  => 'contact',
		            'operator' => '!=',
		            'value'    => 'none',
		        ],
		    ],
		],
		
	);
	$fields[] = array(
		'type'        => 'text',
		'settings'    => 'ab_ring_phone_format_after',
		'label'       => __( 'Ring Phone Number Format After', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '$1.$2.$3',
		'priority'    => 10,
		'active_callback' => [
		    [
		        [
		            'setting'  => 'contact',
		            'operator' => '!=',
		            'value'    => 'none',
		        ],
		    ],
		],
		
	);

	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_zalo',
		'label'       => __( 'Zalo', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
		'active_callback' => [
		    [
		        [
		            'setting'  => 'contact',
		            'operator' => '!=',
		            'value'    => 'none',
		        ],
		    ],
		],
	);
	$fields[] = array(
		'type'        => 'text',
		'settings'    => 'ab_zalo_number',
		'label'       => __( 'Zalo Number', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '',
		'priority'    => 10,
		'active_callback' => [
		    [
		        [
		            'setting'  => 'contact',
		            'operator' => '!=',
		            'value'    => 'none',
		        ],
		    ],
		],
		
	);
	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_email',
		'label'       => __( 'Email', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
		'active_callback' => [
		    [
		        [
		            'setting'  => 'contact',
		            'operator' => '!=',
		            'value'    => 'none',
		        ],
		    ],
		],
	);
	$fields[] = array(
		'type'        => 'text',
		'settings'    => 'ab_email_name',
		'label'       => __( 'Email name', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '',
		'priority'    => 10,
		'active_callback' => [
		    [
		        [
		            'setting'  => 'contact',
		            'operator' => '!=',
		            'value'    => 'none',
		        ],
		    ],
		],
		
	);
	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_form_contact',
		'label'       => __( 'Form Contact', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
		'active_callback' => [
		    [
		        [
		            'setting'  => 'contact',
		            'operator' => '!=',
		            'value'    => 'none',
		        ],
		    ],
		],
	);
	$fields[] = array(
		'type'        => 'text',
		'settings'    => 'ab_form_contact_id',
		'label'       => __( 'Form Contact ID', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '',
		'priority'    => 10,
		'active_callback' => [
		    [
		        [
		            'setting'  => 'contact',
		            'operator' => '!=',
		            'value'    => 'none',
		        ],
		    ],
		],
		
	);

	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_fb_chatbox',
		'label'       => __( 'Facebook Chatbox', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
	);
	$fields[] = array(
		'type'        => 'text',
		'settings'    => 'ab_fb_chatbox_id',
		'label'       => __( 'Fanpgae ID', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '103214008036348',
		'priority'    => 10,
		
	);
	$fields[] = array(
		'type'        => 'color',
		'settings'    => 'ab_fb_chatbox_color',
		'label'       => __( 'Chatbox Color', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => '#ff7e29',
		'priority'    => 10,
		
	);
	$fields[] = array(
		'type'        => 'text',
		'settings'    => 'ab_fb_chatbox_welcome',
		'label'       => __( 'First message welcome', 'kirki' ),
		'section'     => 'ab_general',
		'default'     => 'Hãy gửi câu hỏi của bạn cho chúng tôi nhé!',
		'priority'    => 10,
		
	);

	//WooCommmerce General
	$fields[] = array(
		'type'        => 'select',
		'settings'    => 'style',
		'label'       => esc_html__( 'Style Otions', 'kirki' ),
		'section'     => 'ab_woocomerce_general',
		'default'     => 'product-default',
		'placeholder' => esc_html__( 'Select an option...', 'kirki' ),
		'priority'    => 10,
		'choices'     => [
			'none' => esc_html__( 'none', 'kirki' ),
			'product-default' => esc_html__( 'Default', 'kirki' ),
			'product-style-01' => esc_html__( 'Style 01', 'kirki' ),
		],
	);
	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_quickview',
		'label'       => __( 'Quick View', 'kirki' ),
		'section'     => 'ab_woocomerce_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
	);
	
	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_compare',
		'label'       => __( 'Compare', 'kirki' ),
		'section'     => 'ab_woocomerce_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
	);
	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_wishlist',
		'label'       => __( 'Wishlist', 'kirki' ),
		'section'     => 'ab_woocomerce_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
	);
	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_add_to_cart_button',
		'label'       => __( 'Add to Cart Button', 'kirki' ),
		'section'     => 'ab_woocomerce_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
	);
	$fields[] = array(
		'type'        => 'switch',
		'settings'    => 'ab_hover_image',
		'label'       => __( 'Hover Image', 'kirki' ),
		'section'     => 'ab_woocomerce_general',
		'default'     => '1',
		'priority'    => 10,
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'kirki' ),
			'off' => esc_html__( 'Disable', 'kirki' ),
		],
	);
	return $fields;

}
add_filter( 'kirki/fields', 'ab_kirki_fields' );