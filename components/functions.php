<?php

function __()
{
    return call_user_func_array(['Yii', 't'], array_merge(['app'], func_get_args()));
}


/**
 * Debuging
 */

function p()
{
    static $count = 0;
    $args = func_get_args();

    $plain = false;
    if (defined('CONSOLE') || Yii::$app->getRequest()->getIsAjax()) {
        $plain = true;
    }

    if (!empty($args)) {
        if ($plain) {
            echo "\n";
        } else {
            echo '<ol style="font-family: Courier; font-size: 12px; border: 1px solid #ddd; background-color: #eee; float: left; padding-right: 20px;">';
        }
        foreach ($args as $k => $v) {
            if ($plain) {
                echo(print_r($v, true));
            } else {
                echo('<li><pre>' . htmlspecialchars(print_r($v, true)) . "\n" . '</pre></li>');
            }
        }
        if ($plain) {
            echo "\n\n";
        } else {
            echo '</ol><div style="clear:left;"></div>';
        }
        $count++;
    }
}

function pd()
{
    $args = func_get_args();
    call_user_func_array('p', $args);
    die();
}

function plog()
{
    $file = __DIR__ . '/../plog.log';
    
    $resource = fopen($file, 'a');
    if ($resource) {
        $content = PHP_EOL . print_r(array_merge([
            'time' => date('Y-m-d H:i:s'),
        ], func_get_args()), 1);
        fwrite($resource, $content);
    }
    fclose($resource);
}

