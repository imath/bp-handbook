<?php

class BP_Handbook_Addons_Importer extends BP_Handbook_Importer {
	/**
	 * Initializes object.
	 */
	public function init() {
		parent::do_init(
			'add-ons',
			'add-ons',
			'https://raw.githubusercontent.com/buddypress/buddypress/master/docs/developer/manifest.json'
		);

		add_filter( 'handbook_label', array( $this, 'change_handbook_label' ), 10, 2 );
	}

	/**
	 * Overrides the default handbook label since post type name does not directly
	 * translate to post type label.
	 *
	 * @param string $label     The default label, which is merely a sanitized
	 *                          version of the handbook name.
	 * @param string $post_type The handbook post type.
	 * @return string
	 */
	public function change_handbook_label( $label, $post_type ) {
		if ( $this->get_post_type() === $post_type ) {
			$label = __( 'Add-ons Handbook', 'wporg' );
		}

		return $label;
	}
}

