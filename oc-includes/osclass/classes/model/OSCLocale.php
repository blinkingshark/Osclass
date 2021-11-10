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
 * OSCLocale DAO
 */
class OSCLocale extends DAO
{
    /**
     *
     * @var \OSCLocale
     */
    private static $instance;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTableName('t_locale');
        $this->setPrimaryKey('pk_c_code');
        $array_fields = array(
            'pk_c_code',
            's_name',
            's_short_name',
            's_description',
            's_version',
            's_direction',
            's_author_name',
            's_author_url',
            's_currency_format',
            's_dec_point',
            's_thousands_sep',
            'i_num_dec',
            's_date_format',
            's_stop_words',
            'b_enabled',
            'b_enabled_bo'
        );
        $this->setFields($array_fields);
    }

    /**
     * @return \OSCLocale
     */
    public static function newInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Return all locales enabled.
     *
     * @access public
     *
     * @param bool $isBo
     * @param bool $indexedByPk
     *
     * @return array
     * @since  unknown
     *
     */
    public function listAllCodes()
    {
        $this->dao->select('pk_c_code');
        $this->dao->from($this->getTableName());
        $result = $this->dao->get();

        if ($result == false) {
            return array();
        }

        $aResults = $result->result();
        $aCodes   = array();

        foreach ($aResults as $result) {
            $aCodes[] = $result['pk_c_code'];
        }

        return $aCodes;
    }

    /**
     * Return all locales enabled.
     *
     * @access public
     *
     * @param bool $isBo
     * @param bool $indexedByPk
     *
     * @return array
     * @since  unknown
     *
     */
    public function listAllEnabled($isBo = false, $indexedByPk = false)
    {
        $this->dao->select();
        $this->dao->from($this->getTableName());
        if ($isBo) {
            $this->dao->where('b_enabled_bo', 1);
        } else {
            $this->dao->where('b_enabled', 1);
        }
        $this->dao->orderBy('s_name', 'ASC');
        $result = $this->dao->get();

        if ($result == false) {
            return array();
        }

        $aResults = $result->result();

        if ($indexedByPk) {
            $aTmp = array();
            for ($i = 0, $iMax = count($aResults); $i < $iMax; $i++) {
                $aTmp[(string)$aResults[$i][$this->getPrimaryKey()]] = $aResults[$i];
            }
            $aResults = $aTmp;
        }

        return $aResults;
    }

    /**
     * Return all locales by code
     *
     * @access public
     *
     * @param string $code
     *
     * @return array
     * @since  2.3
     */
    public function findByCode($code)
    {
        $this->dao->select();
        $this->dao->from($this->getTableName());
        $this->dao->where('pk_c_code', $code);
        $result = $this->dao->get();

        if ($result == false) {
            return array();
        }

        return $result->result();
    }

    /**
     * Delete all related to locale code.
     *
     * @access public
     *
     * @param string $locale
     *
     * @return bool
     * @since  unknown
     */
    public function deleteLocale($locale)
    {
        osc_run_hook('delete_locale', $locale);

        $array_where = array('fk_c_locale_code' => $locale);
        $this->dao->delete(DB_TABLE_PREFIX . 't_category_description', $array_where);
        $this->dao->delete(DB_TABLE_PREFIX . 't_item_description', $array_where);
        $this->dao->delete(DB_TABLE_PREFIX . 't_keywords', $array_where);
        $this->dao->delete(DB_TABLE_PREFIX . 't_user_description', $array_where);
        $this->dao->delete(DB_TABLE_PREFIX . 't_pages_description', $array_where);

        return $this->dao->delete($this->getTableName(), array('pk_c_code' => $locale));
    }
    /**
     * Insert or update location info in database
     *
     * @param array  $aLocale
     * @param string $localeCode pk_c_code
     */
    public function insertLocaleInfo($aLocale, $localeCode = '')
    {
        if (is_array($aLocale)) {
            if ($localeCode === '') {
                $localeCode = $aLocale['locale_code'];
            }
            $values         = array(
                'pk_c_code'         => $aLocale['locale_code'],
                's_name'            => $aLocale['name'],
                's_short_name'      => $aLocale['short_name'],
                's_description'     => $aLocale['description'],
                's_version'         => $aLocale['version'],
                's_direction'       => $aLocale['direction'],
                's_author_name'     => $aLocale['author_name'],
                's_author_url'      => $aLocale['author_url'],
                's_currency_format' => $aLocale['currency_format'],
                's_date_format'     => $aLocale['date_format'],
                'b_enabled'         => 0,
                'b_enabled_bo'      => 1
            );
            $existingLocale = $this->findByCode($localeCode);
            if ($existingLocale) {
                // don't overwrite existing values use array_merge
                unset($existingLocale['s_version']);
                $values = array_merge($values, $existingLocale);
                return $this->update($values, ['pk_c_code' => $localeCode]);
            }
            return $this->insert($values);
        }
        return false;
    }
}

/* file end: ./oc-includes/osclass/model/OSCLocale.php */
