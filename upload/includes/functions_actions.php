<?php

/**
 * User: Arslan Hassan @arslancb
 * Date: 11/10/13
 * Time: 12:36 PM
 *
 * @Since : 2.7
 *
 * Add ClipBucket actions to apply custom functions on ClipBucket core functions.
 *
 */

/**
 * Register a clipbucket function
 *
 * @param String $func_name
 * @param String $place
 * @param Array $extra_params
 * @param Array $scope
 */

function cb_register_action($func_name,$place,$extra_params=Array(),$scope=array('global'))
{
    global $Cbucket;
    if(isset($Cbucket->actions_list)){
        $actions_list = $Cbucket->actions_list;
        $actions_list[$place][] = array(
            'action' => $func_name,
            'params' => $extra_params,
            'scope' => $scope
        );


        $Cbucket->actions_list = $actions_list;
    }
}


/**
 * Call a register function and returns output if available.
 *
 * @param String $place
 * @param Array $params
 * @param Array $scope
 */

function cb_do_action($place,$params=array(),$scope=array('global'))
{

    $actions = cb_get_actions($place,$scope);

    if($actions)
    {
        foreach($actions as $action)
        {
            if(isset($output)) unset($output);

            if(function_exists($action['action']))
            {
                if($params && $action['params'])
                {
                    $params = array_merge($params,$action['params']);
                }elseif($action['params'])
                {
                    $params = $action['params'];
                }

                if($params)
                {
                    $output = $action['action']($params);
                }else
                {
                    $output = $action['action']();
                }


            }

            if(isset($output) && $output) return $output;
        }
    }

}

/**
 * get list of functions available for specific place under defined scope (default:global)
 *
 * @param String $place
 * @param Array $scope
 */
function cb_get_actions($place,$scope=array('global'))
{
    global $Cbucket;

    if(isset($Cbucket->actions_list) && isset($Cbucket->actions_list[$place]))
    {
        return $Cbucket->actions_list[$place];
    }
}

/**
     * Function used to count total notifications
     */
    function count_total_notification($item=false)
    {
        global $db;
        $type = $this->type;
        $results = $db->count(tbl('notifications'),"*");
        echo  $db->query;                  
        if($db->num_rows>0)
            return $results;
        else
            return false;
    }

?>