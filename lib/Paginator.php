<?php

class Paginator {

    const ITEMS_TO_SHOW = 10;
    private $paginator_html;

    function __construct($base_url, $number_of_records, $per_page, $options = []) {
        $current_page = empty($options['page']) ? 1 : (int)$options['page'];
        $per_page     = empty($options['per_page']) ? $per_page : (int)$options['per_page'];
        $last_page    = ceil($number_of_records / $per_page);
        if ($per_page < $number_of_records) {

            $url = $this->full_base_url($base_url, $options);

            $pages = $this->get_pages($current_page, $last_page, $url);

            $this->paginator_html = '<nav><ul class="pagination">';

            foreach ($pages as $page) {
                $this->paginator_html .= $this->create_page_item($page);
            }

            $this->paginator_html .= '</ul></nav>';

        } else {
            $this->paginator_html = '';
        }
    }

    function __toString() {
        return $this->paginator_html;
    }

    private function full_base_url($base_url, $options) {
        $url_formatted_options = [];
        if (isset($options['page'])) {
            unset($options['page']);
        }

        foreach ($options as $option => $value) {
            array_push($url_formatted_options, "$option=$value");
        }
        return "$base_url?" . implode('&', $url_formatted_options);
    }


    /**
     * @param $current_page int the current page
     * @param $last_page int the last page index
     * @param $url string the base url to access the page
     * @return array
     */
    private function get_pages($current_page, $last_page, $url) {
        $right = $current_page - self::ITEMS_TO_SHOW  / 2;
        $left  = $current_page + self::ITEMS_TO_SHOW  / 2;

        $pages = [];

        $i = $right;
        while ($i <= $left) {
            if ($i >= 1 && $i <= $last_page) {
                $page = ['url' => "$url&page=$i", 'is_current' => $i == $current_page, 'text' => $i];
                array_push($pages, $page);
            }
            $i++;
        }

        // add ellipsis at the beginning if needed
        if (($current_page - self::ITEMS_TO_SHOW / 2) > 1) {
            array_unshift($pages, ['url' => '#', 'is_current' => false, 'text' => '...']);
        }

        if ($right > 1) {
            array_unshift($pages, ['url' => "$url&page=1", 'is_current' => false, 'text' => '&laquo; First']);
        }

        // add ellipsis at the end if needed
        if (($current_page + self::ITEMS_TO_SHOW / 2) < $last_page) {
            array_push($pages, ['url' => '#', 'is_current' => false, 'text' => '...']);
        }

        if ($left < $last_page) {
            array_push($pages, ['url' => "$url&page=$last_page", 'is_current' => false, 'text' => 'Last &raquo;']);
        }

        return $pages;
    }

    private function create_page_item($page) {
        $class = $page['is_current'] ? 'active' : '';
        return <<<HTML
<li class="$class">
    <a href="${page['url']}">
        ${page['text']}
    </a>
</li> 
HTML;

    }
}