<?php
class FetchedDataURL extends FetchedDataHTML {
    public $content;
    public $headers;
    public $url;

    public function __construct($content, $headers, $url) {
        $this->content = $content;
        $this->headers = $headers;
        $this->url = $url;
    }

    public function detect_encoding() {
        // First, try to get encoding from META http-equiv tag
        $encoding = $this->_detect_encoding_using_meta($this->content);

        // If no META encoding specified, try to use encoding from HTTP response
        if (is_null($encoding)) {
            $matches = null;
            foreach ($this->headers as $header) {
                if (preg_match("/Content-Type: .*charset=\s*([^\s;]+)/i", $header, $matches)) {
                    $encoding = strtolower($matches[1]);
                }
            }
        }

        // At last, fall back to default encoding
        if (is_null($encoding)) {
            $encoding = "iso-8859-1";
        }

        return $encoding;
    }

    function get_additional_data($key) {
        switch ($key) {
            case 'Content-Type':
                $matches = null;
                foreach ($this->headers as $header) {
                    if (preg_match("/Content-Type: (.*)/", $header, $matches)) {
                        return $matches[1];
                    }
                }
                return null;
        }
    }

    function get_uri() {
        return $this->url;
    }

    function get_content() {
        return $this->content;
    }

    function set_content($data) {
        $this->content = $data;
    }
}
