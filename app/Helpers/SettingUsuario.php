<?php

if (!function_exists('setting_usuario')) {

    function setting_usuario($key, $user = null)
    {
        if (!is_null($user)) {
            setting()->setExtraColumns(['user_id' => $user->id]);
        } else if (!is_null(Auth::user())) {
            setting()->setExtraColumns(['user_id' => Auth::user()->id]);
        }

        return setting_sitio($key);
    }

    function setting_sitio($key)
    {
        if (is_array($key)) {
            if (!is_null(array_values($key)[0])) {
                setting($key);
            } else {
                setting()->forget(array_key_first($key));
            }
            setting()->save();
        } else {
            return setting($key);
        }

        return null;
    }
}
