<?php

abstract class MAPIBaseModel extends Illuminate\Database\Eloquent\Model
{
    protected $connection = 'mapi';

    // The human readable description of the model.
    protected $description = 'MAPI BaseModel';

    public static function boot()
    {
        //sql调试
        $sql_debug = config('database.sql_debug');
        if ($sql_debug) {
            DB::listen(function ($sql) {
                foreach ($sql->bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $sql->bindings[$i] = "'$binding'";
                        }
                    }
                }
                $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
                $query = vsprintf($query, $sql->bindings);
                print_r($query);
                echo '<br />';
            });
        }
    }

}
