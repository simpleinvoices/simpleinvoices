<?php
require_once ('library/pdf/fetcher._interface.class.php');

class MyFetcherLocalFile extends Fetcher {
    public $_content;

    public function MyFetcherLocalFile($html_to_pdf) {
        $this->_content = $html_to_pdf;
    }

    public function get_data($dummy1) {
        return new FetchedDataURL($this->_content, array(), "");
    }

    public function get_base_url() {
        return "";
    }
}
