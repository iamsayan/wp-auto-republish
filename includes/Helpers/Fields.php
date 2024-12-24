<?php

/**
 * Fields functions.
 *
 * @since      1.3.0
 * @package    RevivePress
 * @subpackage RevivePress\Helpers
 * @author     Sayan Datta <iamsayan@protonmail.com>
 */
namespace RevivePress\Helpers;

use  RevivePress\Helpers\HelperFunctions ;
defined( 'ABSPATH' ) || exit;
/**
 * Ajax class.
 */
trait Fields
{
    use  HelperFunctions ;

    /**
     * Send AJAX response.
     *
     * @param array   $data    Data to send using ajax.
     * @param boolean $success Optional. If this is an error. Defaults: true.
     */
    protected function do_field( $data )
    {
        $data = $this->do_filter( 'admin_fields', $data, $data['id'] );
        if ( ! isset( $data['type'] ) || empty($data['type']) ) {
            $data['type'] = 'text';
        }
        $class = array( 'wpar-form-control', 'wpar-form-el' );
        if ( isset( $data['class'] ) && ! empty($data['class']) ) {
            
            if ( is_array( $data['class'] ) ) {
                $class = array_merge( $class, $data['class'] );
            } else {
                array_push( $class, $data['class'] );
            }        
}
        $name = $data['id'];
        if ( isset( $data['name'] ) ) {
            $name = $data['name'];
        }
        $attr = array();
        
        if ( isset( $data['required'] ) && true === $data['required'] ) {
            $attr[] = 'required';
            $attr[] = 'data-required="yes"';
        } else {
            $attr[] = 'data-required="no"';
        }
        
        if ( isset( $data['checked'] ) && true === $data['checked'] ) {
            $attr[] = 'checked';
        }
        if ( isset( $data['disabled'] ) && true === $data['disabled'] ) {
            $attr[] = 'disabled';
        }
        if ( isset( $data['readonly'] ) && true === $data['readonly'] ) {
            $attr[] = 'readonly';
        }
        if ( isset( $data['attributes'] ) && ! empty($data['attributes']) && is_array( $data['attributes'] ) ) {
            foreach ( $data['attributes'] as $key => $value ) {
                $attr[] = $key . '="' . $value . '"';
            }
        }
        $value = ( isset( $data['value'] ) ? $data['value'] : '' );
        
        if ( $data['type'] == 'hidden' ) {
            echo  '<input type="hidden" name="wpar_plugin_settings[' . esc_attr( $name ) . ']" id="' . esc_attr( $data['id'] ) . '" autocomplete="off" value="' . esc_attr( $value ) . '" />' ;
            return;
        }
        
        $tooltip = '';
        if ( isset( $data['description'] ) && ! empty($data['description']) ) {
            
            if ( isset( $data['tooltip'] ) && $data['tooltip'] ) {
                $tooltip = '<span class="tooltip" title="' . esc_attr( $data['description'] ) . '"><span title="" class="dashicons dashicons-editor-help"></span></span>';
            } else {
                $tooltip = '<div class="description">' . wp_kses_post( $data['description'] ) . '</div>';
            }        
}
        
        if ( $data['type'] == 'checkbox' ) {
            $value = ( ! empty($value) ? $value : '1' );
            echo  '<label class="switch">' ;
            echo  '<input type="checkbox" name="wpar_plugin_settings[' . esc_attr( $name ) . ']" id="' . esc_attr( $data['id'] ) . '" class="wpar-form-el" value="' . esc_attr( $value ) . '" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' />
				<span class="slider">
					<svg width="3" height="8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2 6" class="toggle-on" role="img" aria-hidden="true" focusable="false"><path d="M0 0h2v6H0z"></path></svg>
					<svg width="8" height="8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 6 6" class="toggle-off" role="img" aria-hidden="true" focusable="false"><path d="M3 1.5c.8 0 1.5.7 1.5 1.5S3.8 4.5 3 4.5 1.5 3.8 1.5 3 2.2 1.5 3 1.5M3 0C1.3 0 0 1.3 0 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"></path></svg>
				</span>' ;
            echo  '</label>' . wp_kses_post( $tooltip ) ;
            return;
        }
        
        
        if ( isset( $data['type'] ) ) {
            
            if ( in_array( $data['type'], array(
                'text',
                'email',
                'password',
                'date',
                'number',
            ) ) ) {
                echo  '<input type="' . esc_attr( $data['type'] ) . '" name="wpar_plugin_settings[' . esc_attr( $name ) . ']" id="' . esc_attr( $data['id'] ) . '" class="' . esc_attr( implode( ' ', array_unique( $class ) ) ) . '" autocomplete="off" value="' . esc_attr( $value ) . '" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' />' ;
            } elseif ( $data['type'] == 'textarea' ) {
                echo  '<textarea class="' . esc_attr( implode( ' ', array_unique( $class ) ) ) . '" id="' . esc_attr( $data['id'] ) . '" name="wpar_plugin_settings[' . esc_attr( $name ) . ']" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' autocomplete="off">' . wp_kses_post( $value ) . '</textarea>' ;
            } elseif ( $data['type'] == 'select' ) {
                echo  '<select id="' . esc_attr( $data['id'] ) . '" class="' . esc_attr( implode( ' ', array_unique( $class ) ) ) . '" name="wpar_plugin_settings[' . esc_attr( $name ) . ']" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' autocomplete="off">' ;
                if ( ! empty($data['options']) && is_array( $data['options'] ) ) {
                    foreach ( $data['options'] as $key => $option ) {
                        $disabled = ( strpos( $key, 'premium' ) !== false ? ' disabled' : '' );
                        echo  '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $value, false ) . esc_attr( $disabled ) . '>' . esc_html( $option ) . '</option>' ;
                    }
                }
                echo  '</select>' ;
            } elseif ( $data['type'] == 'multiple' ) {
                echo  '<select id="' . esc_attr( $data['id'] ) . '" class="' . esc_attr( implode( ' ', array_unique( $class ) ) ) . '" name="wpar_plugin_settings[' . esc_attr( $name ) . '][]" multiple="multiple" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' style="width: 95%">' ;
                
                if ( ! empty($data['options']) && is_array( $data['options'] ) ) {
                    foreach ( $data['options'] as $key => $option ) {
                        echo  '<option value="' . esc_attr( $key ) . '" ' . selected( in_array( $key, $value ), true, false ) . '>' . esc_html( $option ) . '</option>' ;
                    }
                } elseif ( ! empty($value) ) {
                    foreach ( $value as $author ) {
                        $key = $author;
                        
                        if ( 'allowed_authors' === $data['name'] ) {
                            $user = get_user_by( 'id', $key );
                            $author = $user->display_name;
                        }
                        
                        echo  '<option value="' . esc_attr( $key ) . '" selected="selected">' . esc_html( $author ) . '</option>' ;
                    }
                }
                
                echo  '</select>' ;
            } elseif ( $data['type'] == 'multiple_tax' ) {
                echo  '<select id="' . esc_attr( $data['id'] ) . '" class="' . esc_attr( implode( ' ', array_unique( $class ) ) ) . '" name="wpar_plugin_settings[' . esc_attr( $name ) . '][]" multiple="multiple" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . ' style="width: 95%">' ;
                
                if ( ! empty($data['options']) && is_array( $data['options'] ) ) {
                    foreach ( $data['options'] as $key => $option ) {
                        echo  '<optgroup label="' . esc_attr( $option['label'] ) . '">' ;
                        if ( isset( $option['categories'] ) && ! empty($option['categories']) && is_array( $option['categories'] ) ) {
                            foreach ( $option['categories'] as $cat_slug => $cat_name ) {
                                echo  '<option value="' . esc_attr( $cat_slug ) . '" ' . selected( in_array( $cat_slug, $value ), true, false ) . '>' . esc_html( $cat_name ) . '</option>' ;
                            }
                        }
                        echo  '</optgroup>' ;
                    }
                } elseif ( ! empty($value) ) {
                    foreach ( $value as $taxonomy ) {
                        $taxonomy = $this->process_taxonomy( $taxonomy );
                        $term = get_term( $taxonomy[1] );
                        
                        if ( ! is_wp_error( $term ) || ! is_null( $term ) ) {
                            $get_taxonomy_data = get_taxonomy( $term->taxonomy );
                            $cat_name = $get_taxonomy_data->label . ': ' . $term->name;
                            echo  '<option value="' . esc_attr( join( '|', $taxonomy ) ) . '" selected="selected">' . esc_html( $cat_name ) . '</option>' ;
                        }                    
}
                }
                
                echo  '</select>' ;
            } elseif ( $data['type'] == 'wp_editor' ) {
                echo  '<div class="wpar-form-control wpar-form-el wpar-editor" ' . wp_kses_post( implode( ' ', array_unique( $attr ) ) ) . '>' ;
                wp_editor( html_entity_decode( $value, ENT_COMPAT, "UTF-8" ), $data['id'], array(
                    'textarea_name' => 'wpar_plugin_settings[' . esc_attr( $name ) . ']',
                    'textarea_rows' => '8',
                    'teeny'         => true,
                    'tinymce'       => false,
                    'media_buttons' => false,
                ) );
                echo  '</div>' ;
            }
            
            echo  wp_kses_post( $tooltip ) ;
            return;
        }
    }
}