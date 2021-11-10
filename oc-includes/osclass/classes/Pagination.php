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
 * Class Pagination
 */
class Pagination
{
    protected $total;
    protected $selected;
    protected $class_first;
    protected $class_last;
    protected $class_prev;
    protected $class_next;
    protected $text_first;
    protected $text_last;
    protected $text_prev;
    protected $text_next;
    protected $class_selected;
    protected $class_non_selected;
    protected $delimiter;
    protected $force_limits;
    protected $sides;
    protected $url;
    protected $firstUrl;
    protected $nofollow;
    protected $listClass;

    /**
     * Pagination constructor.
     *
     * @param null $params
     *
     */
    public function __construct($params = null)
    {
        $this->total              = isset($params['total']) ? $params['total'] + 1 : osc_search_total_pages() + 1;
        $this->selected           = isset($params['selected']) ? $params['selected'] + 1 : osc_search_page() + 1;
        $this->class_first        = $params['class_first'] ?? 'searchPaginationFirst';
        $this->class_last         = $params['class_last'] ?? 'searchPaginationLast';
        $this->class_prev         = $params['class_prev'] ?? 'searchPaginationPrev';
        $this->class_next         = $params['class_next'] ?? 'searchPaginationNext';
        $this->text_first         = $params['text_first'] ?? '&laquo;';
        $this->text_last          = $params['text_last'] ?? '&raquo;';
        $this->text_prev          = $params['text_prev'] ?? '&lt;';
        $this->text_next          = $params['text_next'] ?? '&gt;';
        $this->class_selected     = $params['class_selected'] ?? 'searchPaginationSelected';
        $this->class_non_selected = $params['class_non_selected'] ?? 'searchPaginationNonSelected';
        $this->delimiter          = $params['delimiter'] ?? ' ';
        $this->force_limits       = isset($params['force_limits']) && $params['force_limits'];
        $this->sides              = $params['sides'] ?? 2;
        $this->url                = $params['url'] ?? osc_update_search_url(array('iPage' => '{PAGE}'));
        $this->firstUrl           = $params['first_url'] ?? $this->url;
        $this->nofollow           = $params['nofollow'] ?? false;
        $this->listClass          = $params['list_class'] ?? false;
    }

    /**
     * @return string
     */
    public function doPagination()
    {
        if ($this->total > 1) {
            $links = $this->get_links();
            if ($this->listClass !== false) {
                return '<ul class="' . $this->listClass . '">' . implode($this->delimiter, $links) . '</ul>';
            }

            return '<ul>' . implode($this->delimiter, $links) . '</ul>';
        }

        return '';
    }

    /**
     * @return array
     */
    public function get_links()
    {
        $pages   = $this->get_pages();
        $links   = array();
        $isFirst = 0;
        $isLast  = 0;


        $attrs = array();
        if ($this->nofollow) {
            $attrs['rel'] = 'nofollow';
        }

        if (isset($pages['first'])) {
            if (!$isFirst) {
                $this->class_first .= ' list-first';
                $isFirst++;
            }
            $attrs['class'] = $this->class_first;
            $attrs['href']  = str_replace(array(urlencode('{PAGE}'), '{PAGE}'), '', $this->firstUrl);
            $links[]        = $this->createATag($this->text_first, $attrs);
        }
        if (isset($pages['prev'])) {
            if (!$isFirst) {
                $this->class_prev .= ' list-first';
                $isFirst++;
            }
            $attrs['class'] = $this->class_prev;
            if ($pages['prev'] == 1) {
                $attrs['href'] = str_replace(array(urlencode('{PAGE}'), '{PAGE}'), '', $this->firstUrl);
            } else {
                $attrs['href'] = str_replace(array(
                    urlencode('{PAGE}'),
                    '{PAGE}'
                ), array($pages['prev'], $pages['prev']), $this->url);
            }
            $links[] = $this->createATag($this->text_prev, $attrs);
        }
        foreach ($pages['pages'] as $p) {
            $isLast++;
            if (!isset($pages['next']) && !isset($pages['last']) && ($isLast == count($pages['pages']))) {
                $classfirst_selected     = $this->class_selected . ' list-last';
                $classfirst_non_selected = $this->class_non_selected . ' list-last';
            }
            if (!$isFirst) {
                $classfirst_selected     = $this->class_selected . ' list-first';
                $classfirst_non_selected = $this->class_non_selected . ' list-first';
                $isFirst++;
            } else {
                $classfirst_selected     = $this->class_selected;
                $classfirst_non_selected = $this->class_non_selected;
            }
            if ($p == 1) {
                $attrs['href'] = str_replace(array(urlencode('{PAGE}'), '{PAGE}'), '', $this->firstUrl);
            } else {
                $attrs['href'] = str_replace(array(urlencode('{PAGE}'), '{PAGE}'), array(
                    $p,
                    $p
                ), $this->url);
            }
            if ($p == $this->selected) {
                $links[] = $this->createSpanTag($p, array('class' => $classfirst_selected));
            } else {
                $attrs['class'] = $classfirst_non_selected;
                $links[]        = $this->createATag($p, $attrs);
            }
        }
        if (isset($pages['next'])) {
            if (!isset($pages['last'])) {
                $this->class_next .= ' list-last';
            }
            $attrs['class'] = $this->class_next;
            $attrs['href']  = str_replace(array(urlencode('{PAGE}'), '{PAGE}'), array(
                $pages['next'],
                $pages['next']
            ), $this->url);
            $links[]        = $this->createATag($this->text_next, $attrs);
        }
        if (isset($pages['last'])) {
            $this->class_last .= ' list-last';
            $attrs['class']   = $this->class_last;
            $attrs['href']    = str_replace(array(urlencode('{PAGE}'), '{PAGE}'), array(
                $pages['last'],
                $pages['last']
            ), $this->url);
            $links[]          = $this->createATag($this->text_last, $attrs);
        }

        return $links;
    }

    /**
     * @return array
     */
    public function get_pages()
    {
        $pages = $this->get_raw_pages();

        if (!$this->force_limits) {
            if ($pages['first'] == $pages['pages'][0]) {
                unset($pages['first']);
            }
            if ($pages['last'] == $pages['pages'][count($pages['pages']) - 1]) {
                unset($pages['last']);
            }
        }
        if ($pages['prev'] === '') {
            unset($pages['prev']);
        }
        if ($pages['next'] === '') {
            unset($pages['next']);
        }

        return $pages;
    }

    /**
     * @param null $params
     *
     * @return array
     */
    public function get_raw_pages($params = null)
    {
        $pages = array();

        $pages['first'] = 1;
        $pages['prev']  = ($this->selected > 1) ? $this->selected - 1 : '';

        for ($p = ($this->selected - $this->sides); $p < $this->selected; $p++) {
            if ($p >= 1) {
                $pages['pages'][] = $p;
            }
        }

        $pages['pages'][] = $this->selected;

        for ($p = ($this->selected + 1); $p <= ($this->selected + $this->sides); $p++) {
            if ($p < $this->total) {
                $pages['pages'][] = $p;
            }
        }

        $pages['next'] = ($this->selected < ($this->total - 1)) ? $this->selected + 1 : '';
        $pages['last'] = $this->total - 1;

        return $pages;
    }

    /**
     * @param $text
     * @param $attrs
     *
     * @return string
     */
    protected function createATag($text, $attrs)
    {
        $att = array();
        foreach ($attrs as $k => $v) {
            $att[] = $k . '="' . osc_esc_html($v) . '"';
        }

        return '<li><a ' . implode(' ', $att) . '>' . $text . '</a></li>';
    }

    /**
     * @param $text
     * @param $attrs
     *
     * @return string
     */
    protected function createSpanTag($text, $attrs)
    {
        $att = array();
        foreach ($attrs as $k => $v) {
            $att[] = $k . '="' . osc_esc_html($v) . '"';
        }

        return '<li><span ' . implode(' ', $att) . '>' . $text . '</span></li>';
    }
}

/* file end: ./oc-includes/osclass/classes/Pagination.php */

