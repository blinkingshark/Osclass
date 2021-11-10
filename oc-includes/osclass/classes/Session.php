<?php

/*
 * Osclass - software for creating and publishing online classified advertising platforms
 * Maintained and supported by Mindstellar Community
 * https://github.com/mindstellar/Osclass
 * Copyright (c) 2021.  Mindstellar
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *                     GNU GENERAL PUBLIC LICENSE
 *                        Version 3, 29 June 2007
 *
 *  Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 *  Everyone is permitted to copy and distribute verbatim copies
 *  of this license document, but changing it is not allowed.
 *
 *  You should have received a copy of the GNU Affero General Public
 *  License along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Class Session
 */
class Session
{
    //attributes
    private static $instance;
    private $session;

    /**
     * @return \Session
     */
    public static function newInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function session_start()
    {
        $currentCookieParams = session_get_cookie_params();
        if (defined('COOKIE_DOMAIN')) {
            $currentCookieParams['domain'] = COOKIE_DOMAIN;
        }
        if (isset($_SERVER['HTTPS'])) {
            $currentCookieParams['secure'] = true;
        }
        session_set_cookie_params(
            $currentCookieParams['lifetime'],
            $currentCookieParams['path'],
            $currentCookieParams['domain'],
            $currentCookieParams['secure'],
            true
        );

        if (!isset($_SESSION)) {
            session_name('osclass');
            if (!$this->_session_start()) {
                session_id(uniqid('', true));
                session_start();
                session_regenerate_id();
            }
        }

        $this->session = $_SESSION;
        if (!$this->_get('messages')) {
            $this->_set('messages', array());
        }
        if (!$this->_get('keepForm')) {
            $this->_set('keepForm', array());
        }
        if (!$this->_get('form')) {
            $this->_set('form', array());
        }
    }

    /**
     * @return bool
     */
    public function _session_start()
    {
        $sn = session_name();
        if (isset($_COOKIE[$sn])) {
            $sessid = $_COOKIE[$sn];
        } elseif (isset($_GET[$sn])) {
            $sessid = $_GET[$sn];
        } else {
            return session_start();
        }

        if (!preg_match('/^[a-zA-Z0-9,\-]{22,40}$/', $sessid)) {
            return false;
        }

        return session_start();
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function _get($key)
    {
        return $this->session[$key] ?? '';
    }

    /**
     * @param $key
     *
     * @return bool
     * @since 4.0.0
     */
    public function _has($key)
    {
         return isset($this->session[$key]);
           
    }
    /**
     * @param $key
     * @param $value
     */
    public function _set($key, $value)
    {
        $_SESSION[$key]      = $value;
        $this->session[$key] = $value;
    }

    public function session_destroy()
    {
        session_destroy();
    }

    /**
     * @param $key
     */
    public function _drop($key)
    {
        unset($_SESSION[$key], $this->session[$key]);
    }

    /**
     * @param $value
     */
    public function _setReferer($value)
    {
        $_SESSION['osc_http_referer']            = $value;
        $this->session['osc_http_referer']       = $value;
        $_SESSION['osc_http_referer_state']      = 0;
        $this->session['osc_http_referer_state'] = 0;
    }

    /**
     * @return string
     */
    public function _getReferer()
    {
        return $this->session['osc_http_referer'] ?? '';
    }

    public function _view()
    {
        print_r($this->session);
    }

    /**
     * @param $key
     * @param $value
     * @param $type
     */
    public function _setMessage($key, $value, $type)
    {
        $messages         = $this->_get('messages');
        $messages[$key][] = array('msg' => str_replace(PHP_EOL, '<br />', $value), 'type' => $type);
        $this->_set('messages', $messages);
    }

    /**
     * @param $key
     *
     * @return string|array
     */
    public function _getMessage($key)
    {
        $messages = $this->_get('messages');

        return $messages[$key] ?? '';
    }

    /**
     * @param $key
     */
    public function _dropMessage($key)
    {
        $messages = $this->_get('messages');
        unset($messages[$key]);
        $this->_set('messages', $messages);
    }

    /**
     * @param $key
     */
    public function _keepForm($key)
    {
        $aKeep       = $this->_get('keepForm');
        $aKeep[$key] = 1;
        $this->_set('keepForm', $aKeep);
    }

    /**
     * @param string $key
     */
    public function _dropKeepForm($key = '')
    {
        $aKeep = $this->_get('keepForm');
        if ($key) {
            unset($aKeep[$key]);
            $this->_set('keepForm', $aKeep);
        } else {
            $this->_set('keepForm', array());
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function _setForm($key, $value)
    {
        $form       = $this->_get('form');
        $form[$key] = $value;
        $this->_set('form', $form);
    }

    /**
     * @param string $key
     *
     * @return string|array
     */
    public function _getForm($key = '')
    {
        $form = $this->_get('form');
        if ($key) {
            return $form[$key] ?? '';
        }

        return $form;
    }

    /**
     * @return string|array
     */
    public function _getKeepForm()
    {
        return $this->_get('keepForm');
    }

    public function _viewMessage()
    {
        print_r($this->session['messages']);
    }

    public function _viewForm()
    {
        print_r($_SESSION['form']);
    }

    public function _viewKeep()
    {
        print_r($_SESSION['keepForm']);
    }

    public function _clearVariables()
    {
        $form  = $this->_get('form');
        $aKeep = $this->_get('keepForm');
        if (is_array($form)) {
            foreach ($form as $key => $value) {
                if (!isset($aKeep[$key])) {
                    unset($_SESSION['form'][$key], $this->session['form'][$key]);
                }
            }
        }

        if (isset($this->session['osc_http_referer_state'])) {
            $this->session['osc_http_referer_state']++;
            $_SESSION['osc_http_referer_state']++;
            if ((int)$this->session['osc_http_referer_state'] >= 2) {
                $this->_dropReferer();
            }
        }
    }

    public function _dropReferer()
    {
        unset(
            $_SESSION['osc_http_referer'],
            $this->session['osc_http_referer'],
            $_SESSION['osc_http_referer_state'],
            $this->session['osc_http_referer_state']
        );
    }
}
