<?php

namespace MyApp\Libs;


class Session
{
    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }

    public static function put($name, $value)
    {
        return $_SESSION[$name] = $value;
    }

    public static function get($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }else {
            return  false;
        }
    }

    public static function delete($name)
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

//
    public static function flash($name, $string = null)
    {
        if (self::exists($name)) {
            $sessionValue = $_SESSION[$name];
            self::delete($name);
//            $_SESSION[$name] = null;
            return $sessionValue;
        } else {
            self::put($name, $string);
        }
    }


//    public static function flash( $name = '', $message = '', $class = 'success fadeout-message' )
//    {
//        //We can only do something if the name isn't empty
//        if( !empty( $name ) )
//        {
//            //No message, create it
//            if( !empty( $message ) && empty( $_SESSION[$name] ) )
//        {
//            if( !empty( $_SESSION[$name] ) )
//            {
//                unset( $_SESSION[$name] );
//            }
//            if( !empty( $_SESSION[$name.'_class'] ) )
//            {
//                unset( $_SESSION[$name.'_class'] );
//            }
//
//            $_SESSION[$name] = $message;
//            $_SESSION[$name.'_class'] = $class;
//        }
//        //Message exists, display it
//        elseif( !empty( $_SESSION[$name] ) && empty( $message ) )
//        {
//            $class = !empty( $_SESSION[$name.'_class'] ) ? $_SESSION[$name.'_class'] : 'success';
//            echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
//            unset($_SESSION[$name]);
//            unset($_SESSION[$name.'_class']);
//        }
//    }
//    }
}
