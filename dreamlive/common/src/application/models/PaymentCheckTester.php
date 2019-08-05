<?php
class PaymentCheckTester
{
    //监控规则配置
    private static $RULE_CONFIG_RISK = [
            'testuser_limt'     => [
                                    'status'    => true,
                                    'type'      => 'backtrace',
                                    'args'      => [ 'max_trace_up' => 3,
                                                     'callback'     => 'notMixedTradeUsers',
                                                     'method'       => [
                                                                ['name'=>'Gift::send', 'arg_index' => [0,1] ],
                                                      ],
                                    ],
             ],
            'checker'           => [ 'status'    => true, 'type'      => 'callback'],
    ];

    public static function ruleCheckAction($rule, $rule_args)
    {
        if(!array_key_exists($rule, self::$RULE_CONFIG_RISK) || !self::$RULE_CONFIG_RISK[$rule]['status'] ) {
            return true;
        }
        switch(self::$RULE_CONFIG_RISK[$rule]['type']) {
        case 'backtrace':
            if(! method_exists(__CLASS__, self::$RULE_CONFIG_RISK[$rule]['args']['callback']) ) {
                return true;
            }
            $trace = debug_backtrace(
                DEBUG_BACKTRACE_PROVIDE_OBJECT,
                1 + self::$RULE_CONFIG_RISK[$rule]['args']['max_trace_up']
            );
            if(count($trace) > 1 ) {
                unset($trace[0]);
            }
            foreach( $trace as $caller_info ) {
                foreach(self::$RULE_CONFIG_RISK[$rule]['args']['method'] as $trace_target ) {
                    list($class_name, $method_name) = self::parseClassnameAndMethod($trace_target['name']);
                    if($class_name ) {
                        if(!isset($caller_info['class']) || $caller_info['class']!=$class_name ) { continue;
                        }
                        if($caller_info['function'] != $method_name ) { continue;
                        }
                    }
                    if($caller_info['function'] != $method_name ) { continue;
                    }
                    $func = self::$RULE_CONFIG_RISK[$rule]['args']['callback'];
                    $args = array();
                    foreach($trace_target['arg_index'] as $index ) {
                        $args[ $index ] = array_key_exists($index, $caller_info['args']) ? $caller_info['args'][$index] : null;
                    }
                    if(! self::$func($args) ) {
                        return false;
                    }
                }
            }

            break;
        case 'callback':
            if(isset($rule_args[0]) && isset($rule_args[1]) ) {
                list($class_name, $method_name) = self::parseClassnameAndMethod($rule_args[0]);
                $func = $class_name . '_' . $method_name . '_Checker';
                $args = $rule_args[1];
                if(method_exists(__CLASS__, $func) ) {
                    return self::$func($args);
                }
            }
            break;
        }

        return true;
    }

    private static function parseClassnameAndMethod($args)
    {
        $class_name = '';
        $method_name = '';
        if(!$args ) {
            //throw new Exception('[' . __FUNCTION__ . '] empty arg_string');
            return array($class_name, $method_name);
        }
        if(is_string($args) ) {
            $methods = explode('::', trim($args), 2);
            $class_name = isset($methods[1]) ? $methods[0] : '';
            $method_name = $class_name ? $methods[1] : $methods[0];
        }
        if(is_array($args) ) {
            $class_name = isset($args[1]) ? $args[0] : '';
            $method_name = $class_name ? $args[1] : $args[0];
        }
        return array($class_name, $method_name);
    }

    //交易 uid 是否是同一范围 (同是普通账户, 或者同是测试账户)
    private static function notMixedTradeUsers($user_ids)
    {
        $user_ids = array_filter($user_ids, 'is_numeric');
        if(!$user_ids ) { return true;
        }
        static $user_id_list = null;
        if(is_null($user_id_list) ) {
            $config = new Config();
            $account_specific = $config->getConfig("china", "account_tester", "server", '1.0.0.0');
            $account_specific = json_decode(trim($account_specific['value']), true);
            if($account_specific && ($account_specific=array_filter($account_specific['user_ids'], 'is_numeric')) ) {
                $user_id_list = $account_specific;
            }
        }
        if($user_id_list ) {
            $user_ids_sub = array_diff($user_ids, $user_id_list);
            return empty($user_ids_sub) || count($user_ids_sub) == count($user_ids);
        }

        return true;
    }

    //$sender, $receiver, $liveid, $giftid, $consume, $price, $ticket, $num=1
    private static function Gift_send_Checker($args)
    {
        if(count($args) < 2 ) {
            return false;
        }

        return self::notMixedTradeUsers([ $args[0], $args[1] ]);
    }

    //$sender,$liveid,$receiver,$giftid,$price,$ticket,$num
    private static function Gift_sendStarGift_Checker($args)
    {
        if(count($args) < 2 ) {
            return false;
        }

        return self::notMixedTradeUsers([ $args[0], $args[1] ]);
    }

}
?>
