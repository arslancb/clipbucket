<?php

class MobileForm
        extends formObj {

    function createCheckBox( $field, $multi = FALSE ) {
        if ( $field['value'][0] == 'category' ) {
            $values_array = $field['value'][1][0];
            $type = $field['category_type'] ? $field['category_type'] : 'video';
            $catArray = getCategoryList( array("type" => $type) );
            if ( is_array( $catArray ) ) {
                $params['categories'] = $catArray;
                $params['field'] = $field;
                $params['collapsed'] = false;
                $this->listCategoryCheckBox( $params, $multi );
                return;
            } else {
                return "There is no category to select";
            }
        } /* Categories end */

        $arrayName = $this->rmBrackets( $field['name'] );
        $check = '%s'; $count = 0;
        if($multi)
        {
                global $multi_cat_id;
                @$multi_cat_id++;
        }
        foreach ( $field['value'] as $key => $value ) {
            if ( is_array( $values_array ) ) {
                foreach ( $values_array as $cat_val ) {
                    if ( $cat_val == $key || $field['checked'] == 'checked' ) {
                        $checked = sprintf($check,'checked="checked"');
                        break;
                    } else {
                        $checked = sprintf($check,'');;
                    }
                }
            }
            
            if ( !$multi ) {
                $field_name = $field['name'];
            } else {
                $field_name = $field['name'];
                $field_name = $this->rmBrackets($field_name);
                $field_name = $field_name.$multi_cat_id.'[]';
            }
            
            if ( !empty($field['id']) ) {
                $field_id = $field['id'].'_'.$count;
            } else {
                $field_id = $arrayName.'_'.$count;
            }
            
             if($field['multi']) {
                 preg_match_all('/#([0-9]+)#/',$field['checked'],$m);
                 $multiVals = $m[1];
                  if(in_array($key,$multiVals)) {
                      $checked = sprintf($check,'checked="checked"');
                  } else {
                      $checked = sprintf($check,'');
                  }
             } else {
                 if ( $key == $field['checked'] ) {
                     $checked = sprintf($check,'checked="checked"');
                 } else {
                     $checked = sprintf($check,'');
                 }
             }
             
            echo '<input name="' . $field_name . '" id="' . $field_id . '" type="checkbox" value="' . $key . '" ' . $checked . ' ' . $field['extra_tags'] . ' />';
            echo '<label for="' . $field_id . '">' . $value . '</label>';
            $count++;
        } #foreach end
    }

    function listCategoryCheckBoxCollapsed( $in, $multi ) {
        $this->listCategoryCheckBox( $in, $multi );
    }
    
    function listCategoryCheckBox ( $in, $multi ) {
        $cats = $in['categories']; $field = $in['field'];
        $count = 0; $check = '%s';
        if ( $count == 0 && !$in['children_indent']) {
            $field['sep'] = '';
        }
        
        if ( !$mutli ) {
            $field_name = $field['name'];
        } else {
            $field_name = $field['name'];
            $field_name = $this->rmBrackets($field_name);
            $field_name = $field_name.$this->multi_cat_id.'[]';
        }
        
        $values = $field['value'][1][0];
        if(!empty($values)) {
            foreach($values as $val) {
                $newVals[] = '|'.$val.'|'; 
            } 
        }
        
        if ( !empty($field['id']) ) {
            $field_id = $field['id'].'_'.$count;
        } else {
            $field_id = strtolower( str_replace( '_', '', $field['name'] ) ) . '_' . $count;
        }
        
        if ( $cats ) {
            foreach ( $cats as $cat ) {
                if(in_array('|'.$cat['category_id'].'|',$newVals)) {
                     $checked = sprintf($check,'checked="checked"');
                } else {
                     $checked = sprintf($check,'');
                }
                $field_id .= '_'.$cat['category_id'];
                echo '<input name="' . $field_name . '" id="' . $field_id . '" type="checkbox" value="' . $cat['category_id'] . '" ' . $checked . ' ' . $field['extra_tags'] . ' />';
                echo '<label for="' . $field_id . '">' . $field['sep'] .'&nbsp;'. $cat['category_name'] . '</label>';
                $count++;
                if ( $cat['children'] ) {
                    $childField = $field;
                    $childField['sep'] = $field['sep'].str_repeat('&ndash;',1);
                    $this->listCategoryCheckBox(array('categories'=>$cat['children'],'field'=>$childField,'children_indent'=>true),$multi);
                }
            }
        }
    }
    
    function createRadioButton( $field, $multi = FALSE ) {
        $count = 0;
        $arrayName = $this->rmBrackets( $field['name'] );
        foreach ( $field['value'] as $key => $value ) {
            if ( !empty( $_POST[$arrayName] ) || !empty( $field['checked'] ) ) {
                if ( $_POST[$arrayName] == $key || $field['checked'] == $key ) {
                    $checked = 'checked = "checked "';
                } else {
                    $checked = '';
                }
            } else {
                if ( $count == 0 ) {
                    $checked = 'checked = "checked "';
                } else {
                    $checked = '';
                }
            }

            if ( !empty( $field['id'] ) ) {
                $field_id = $field['id'] . '_' . $count;
            } else {
                $field_id = strtolower( str_replace( '_', '', $field['name'] ) ) . '_' . $count;
            }

            if ( !$multi ) {
                $field_name = $field['name'];
            } else {
                $field_name = $field['name'] . '[]';
            }

            echo '<input name="' . $field_name . '" id="' . $field_id . '" type="radio" value="' . $key . '" ' . $checked . ' ' . $field['extra_tags'] . ' />';
            echo '<label for="' . $field_id . '">' . $value . '</label>';
            $count++;
        }
        return;
    }

}

?>
