<?php
    function my_autoload($class_name) {
    if (is_file($_SERVER['DOCUMENT_ROOT'].'wagonsroll/src/wagonsroll/' . $class_name . '.php')) {
        require_once $_SERVER['DOCUMENT_ROOT'].'wagonsroll/src/wagonsroll/' . $class_name . '.php';
    } else {
        $matching_files = glob($_SERVER['DOCUMENT_ROOT'].'wagonsroll/src/wagonsroll/*/' . $class_name . '.php');

        if (count($matching_files) === 1) {
            require_once $matching_files[0];
        } else if (count($matching_files) === 0) {
            trigger_error('Could not find class ' . $class_name . '!', E_USER_ERROR);
        } else {
            trigger_error('More than one possible match found for class ' . $class_name . '!', E_USER_ERROR);
        }
    }
}
spl_autoload_register("my_autoload");
?>