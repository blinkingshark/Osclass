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
 * Class Preference
 */
class Preference extends DAO
{
    /**
     *
     * @var \Preference
     */
    private static $instance;
    /**
     * array for save preferences
     *
     * @var array
     */
    private $pref;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTableName('t_preference');
        /* $this->set_primary_key($key); // no primary key in preference table */
        $this->setFields(array('s_section', 's_name', 's_value', 'e_type'));
        $this->toArray();
    }

    /**
     * Modify the structure of table.
     *
     * @access public
     * @since  unknown
     */
    public function toArray()
    {
        $this->dao->select();
        $this->dao->from($this->getTableName());
        $result = $this->dao->get();

        if ($result == false) {
            return false;
        }

        if ($result->numRows() == 0) {
            return false;
        }

        $aTmpPref = $result->result();
        foreach ($aTmpPref as $tmpPref) {
            $this->pref[$tmpPref['s_section']][$tmpPref['s_name']] = $tmpPref['s_value'];
        }

        return true;
    }

    /**
     * @return \Preference
     */
    public static function newInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Find a value by its name
     *
     * @access public
     *
     * @param $name
     *
     * @return bool
     * @since  unknown
     *
     */
    public function findValueByName($name)
    {
        $this->dao->select('s_value');
        $this->dao->from($this->getTableName());
        $this->dao->where('s_name', $name);
        $result = $this->dao->get();

        if ($result == false) {
            return false;
        }

        if ($result->numRows() == 0) {
            return false;
        }

        $row = $result->row();

        return $row['s_value'];
    }

    /**
     * Find array preference for a given section
     *
     * @access public
     *
     * @param string $name
     *
     * @return array|bool
     * @since  unknown
     *
     */
    public function findBySection($name)
    {
        $this->dao->select();
        $this->dao->from($this->getTableName());
        $this->dao->where('s_section', $name);
        $result = $this->dao->get();

        if ($result == false) {
            return array();
        }

        if ($result->numRows() == 0) {
            return false;
        }

        return $result->result();
    }

    /**
     * Get value, given a preference name and a section name.
     *
     * @access public
     *
     * @param string $key
     * @param string $section
     *
     * @return string
     * @since  unknown
     */
    public function get($key, $section = 'osclass')
    {
        return $this->pref[$section][$key] ?? '';
    }

    /**
     * Get value, given a preference name and a section name.
     *
     * @access public
     *
     * @param string $section
     *
     * @return array
     * @since  unknown
     */
    public function getSection($section = 'osclass')
    {
        if (isset($this->pref[$section]) && is_array($this->pref[$section])) {
            return $this->pref[$section];
        }

        return array();
    }

    /**
     * Set preference value, given a preference name and a section name.
     *
     * @access public
     *
     * @param string $key
     * @param string $value
     * @param string $section
     *
     * @since  unknown
     */
    public function set($key, $value, $section = 'osclass')
    {
        $this->pref[$section][$key] = $value;
    }

    /**
     * Replace preference value, given preference name, preference section and value.
     *
     * @access public
     *
     * @param string $key
     * @param string $value
     * @param string $section
     * @param string $type
     *
     * @return boolean
     * @since  unknown
     */
    public function replace($key, $value, $section = 'osclass', $type = 'STRING')
    {
        static $aValidEnumTypes = array('STRING', 'INTEGER', 'BOOLEAN');
        $array_replace = array(
            's_name'    => $key,
            's_value'   => $value,
            's_section' => $section,
            'e_type'    => in_array($type, $aValidEnumTypes) ? $type : 'STRING'
        );

        return $this->dao->replace($this->getTableName(), $array_replace);
    }
}

/* file end: ./oc-includes/osclass/model/Preference.php */
