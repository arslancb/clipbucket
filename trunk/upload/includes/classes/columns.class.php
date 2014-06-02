<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 11/7/13
 * Time: 12:09 PM
 * To change this template use File | Settings | File Templates.
 */

class cb_columns {

    protected $columns = array();
    protected $object = null;
    protected $temp_actions = array();

    /**
     * Constructor sets the object if current object
     * is null
     */
    function cb_columns() {

        if( is_null( $this->get_object() ) ) {
            $this->set_object( 'videos' );
        }

    }

    /**
     * Returns current object
     *
     * @return STRING|NULL
     */
    function get_object() {
        return $this->object;
    }

    /**
     * Sets the current object
     *
     * @param string $object Object is required
     * @return object $this
     */
    function set_object( $object ) {
        $this->object = $object;
        return $this;
    }

    /**
     * Alias to set_object method
     *
     * @param string $object
     * @return object $this
     */
    function object( $object ) {
        return $this->set_object( $object );
    }

    /**
     * Register columns for current object
     *
     * @param mixed $columns
     * @return object $this
     */
    function register_columns( $columns ) {
        $num_of_args = func_num_args();

        if( $num_of_args > 1 ) {
            $columns = func_get_args();
        }

        $columns = is_array( $columns ) ? $columns : explode( ',', $columns );

        if( $columns ) {
            $object = $this->get_object();
            $columns = array_map( 'trim', $columns  );

            if ( !isset( $this->columns[ $object ] ) ) {
                $this->columns[ $object ] = array();
            }

            $this->columns[ $object ] = $columns;

            return $this;
        }

        return false;
    }

    /**
     * Get the columns for current object
     *
     * @return array
     */
    function get_columns() {
        $columns = $this->columns[ $this->get_object() ];

        if ( empty( $columns ) ) {
            return false;
        }

        return $this->perform_temp_actions( $columns );
    }

    /**
     * Add new columns for current object
     * Note:- Following changes are made globally
     *
     * Usage:-
     * Method can be used in following manners
     * <code>$cb_columns->object( 'videos' )->add_column( 'views,date_added' )</code>
     * <code>$cb_columns->object( 'videos' )->add_column( 'views', 'date_added' )</code>
     * <code>$cb_columns->object( 'videos' )->add_column( 'views,date_added' )</code>
     *
     * @param $columns
     * @return mixed
     */
    function add_column( $columns ) {
        $num_of_args = func_num_args();

        if( $num_of_args > 1 ) {
            $columns = func_get_args();
        }

        $columns = is_array( $columns ) ? $columns : explode( ',', $columns );

        if( $columns ) {
            $object = $this->get_object();
            $columns = array_map( 'trim', $columns  );

            if ( !isset( $this->columns[ $object ] ) ) {
                $this->columns[ $object ] = array();
            }

            $new_columns = array_merge( $this->columns[ $object ], $columns );

            /**
             * Make sure columns are unique
             */
            $new_columns = array_unique( $new_columns );

            return $this->register_columns( $new_columns );
        }

        return false;
    }

    /**
     * Remove column from current object
     * Note:- Following changes are made globally
     *
     * @param $columns
     * @return mixed
     */
    function remove_column ( $columns ) {
        $num_of_args = func_num_args();

        if( $num_of_args > 1 ) {
            $columns = func_get_args();
        }

        $columns = is_array( $columns ) ? $columns : explode( ',', $columns );

        if( $columns ) {
            $object = $this->get_object();
            $columns = array_map( 'trim', $columns );

            foreach ( $columns as $column ) {
                $key = array_search( $column, $this->columns[ $object ] );

                if ( $key !== false ) {
                    unset( $this->columns[ $object ][ $key ] );
                }

            }

            return $this->register_columns( $this->columns[ $object ] );
        }

        return false;
    }

    /**
     * This changes the column name.
     * Note:- Following changes are made globally
     *
     * @param string|array $from
     * @param string|array $to
     * @return array
     */
    function change_column( $from, $to ) {

        $from = is_array( $from ) ? $from : explode( ",", $from );
        $to = is_array( $to ) ? $to : explode( ",", $to );
        $replacements = array();

        if ( isset( $from ) and isset( $to ) ) {
            $total_from = count( $from );
            $total_to = count( $to );

            if ( $total_from == $total_to ) {

                $columns = $this->get_columns();

                for ( $i=0; $i <= $total_from; $i++ ) {
                    $column = $from[ $i ];

                    if( !in_array( $column, $columns ) ) {
                        continue;
                    }

                    $replacements[] = $from[ $i ].' AS '.$to[ $i ];
                }

                if( !empty( $replacements ) ) {

                    $this->remove_column( $from );
                    $this->add_column( $replacements );

                }
            }
        }

        return $this->get_columns();
    }

    /**
     * This setups the temp actions to be performed on
     * returned columns
     *
     * @param array $columns
     * @param string $action
     * @param null $to
     * @return $this
     */
    private function setup_temp_actions( $columns, $action = 'add', $to = null ) {

        $columns = is_array( $columns ) ? $columns : explode( ",", $columns );

        if ( $action == 'change' and !is_null( $to ) ) {
            $to = is_array( $to ) ? $to : explode( ",", $to );
        }

        $action_items = array( 'columns' => $columns );

        if ( !is_null( $to ) ) {
            $action_items[ 'change_to' ] = $to;
        }

        $this->temp_actions[ $action ] = $action_items;

        return $this;
    }

    /**
     * Performs the temp actions on $columns
     *
     * @param $columns
     * @return array
     */
    private function perform_temp_actions( $columns ) {
        $temp_actions = $this->temp_actions;

        if ( !empty( $temp_actions ) ) {


            if( isset( $temp_actions[ 'change' ] ) ) {
                $action_items = $temp_actions[ 'change' ];

                $from = $action_items[ 'columns' ];
                $to = $action_items[ 'change_to' ];
                $total = array( 'from' => count( $from ), 'to' => count( $to ) );

                if ( $total[ 'from' ] == $total[ 'to' ] ) {

                    for ( $i=0; $i <= $total[ 'from' ]; $i++ ) {
                        $column = $from[ $i ];

                        if( !in_array( $column, $columns ) ) {
                            continue;
                        }

                        $replacements[] = $from[ $i ].' AS '.$to[ $i ];
                    }

                    if( !empty( $replacements ) ) {
                        $temp_actions[ 'add' ][ 'columns' ] = ( isset( $temp_actions[ 'add' ][ 'columns' ] ) and is_array( $temp_actions[ 'add' ][ 'columns' ] ) ) ? array_merge( $replacements, $temp_actions[ 'add' ][ 'columns' ] ) : $replacements;
                        $temp_actions[ 'remove' ][ 'columns' ] = ( isset( $temp_actions[ 'remove' ][ 'columns' ] ) and is_array( $temp_actions[ 'remove' ][ 'columns' ] ) ) ? array_merge( $from, $temp_actions[ 'remove' ][ 'columns' ] ) : $from;
                    }

                }
            }

            if ( isset( $temp_actions[ 'remove' ] ) ) {
                $action_items = $temp_actions[ 'remove' ];
                $rcolumns = $action_items[ 'columns' ];
                $rcolumns = array_map( 'trim', $rcolumns );

                foreach( $rcolumns as $column ) {
                    $key = array_search( $column, $columns );

                    if ( $key !== false ) {
                        unset( $columns[ $key ] );
                    }
                }
            }

            if ( isset( $temp_actions[ 'add' ] ) ) {
                $action_items = $temp_actions[ 'add' ];
                $acolumns = array_map( 'trim', $action_items[ 'columns' ] );

                $columns = is_array( $acolumns ) ? array_merge( $columns, $acolumns ) : $columns;
            }
        }

        /**
         * Make sure $columns are unique
         */
        $columns = array_unique( $columns );

        /**
         * Empty the temp actions
         */
        $this->temp_actions = array();

        return $columns;
    }

    /**
     * Add columns temporarily. Parameters can either a string or an array.
     *
     * Usage:-
     * $cb_columns->object( 'videos' )->temp_add( 'last_commented' )->get_columns();
     *
     * @param $columns
     * @return $this
     */
    function temp_add( $columns ) {
        return $this->setup_temp_actions( $columns, 'add' );
    }

    /**
     * Remove columns temporarily. Parameters can either a string or an array.
     *
     * Usage:-
     * $cb_columns->object( 'videos' )->temp_remove( 'last_commented' )->get_columns();
     *
     * @param $columns
     * @return $this
     */
    function temp_remove( $columns ) {
        return $this->setup_temp_actions( $columns, 'remove' );
    }

    /**
     * Change columns temporarily. Parameters can either a string or an array.
     * If passing an array, make $from and $to have equal number of indexes
     *
     * Usage:-
     * $cb_columns->object( 'videos' )->temp_change( 'views', 'video_views' )->get_columns();
     *
     * @param $from
     * @param $to
     * @return $this
     */
    function temp_change( $from, $to ) {
        return $this->setup_temp_actions( $from, 'change', $to );

    }
    

}