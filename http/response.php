<?php
namespace Http;

trait Response {

    public $code;

    public $status = array(
        100 => ['message' => 'Continue', 'status' => '100'],
        101 => ['message' => 'Switching Protocols', 'status' => '101'],
        102 => ['message' => 'Processing', 'status' => '102'],            // RFC2518
        // Success
        200 => ['message' => 'OK', 'status' => '200'],
        201 => ['message' => 'Created', 'status' => '201'],
        202 => ['message' => 'Accepted', 'status' => '202'],
        203 => ['message' => 'Non-Authoritative Information', 'status' => '203'],
        204 => ['message' => 'No Content', 'status' => '204'],
        205 => ['message' => 'Reset Content', 'status' => '205'],
        206 => ['message' => 'Partial Content', 'status' => '206'],
        207 => ['message' => 'Multi-Status', 'status' => '207'],          // RFC4918
        208 => ['message' => 'Already Reported', 'status' => '208'],      // RFC5842
        226 => ['message' => 'IM Used', 'status' => '226'],               // RFC3229
        // Redirect
        300 => ['message' => 'Multiple Choices', 'status' => '300'],
        301 => ['message' => 'Moved Permanently', 'status' => '301'],
        302 => ['message' => 'Found', 'status' => '302'],
        303 => ['message' => 'See Other', 'status' => '303'],
        304 => ['message' => 'Not Modified', 'status' => '304'],
        305 => ['message' => 'Use Proxy', 'status' => '305'],
        307 => ['message' => 'Temporary Redirect', 'status' => '307'],
        308 => ['message' => 'Permanent Redirect', 'status' => '308'],    // RFC7238
        // Client ERRORS
        400 => ['message' => 'Bad Request', 'status' => '400'],
        401 => ['message' => 'Unauthorized', 'status' => '401'],
        402 => ['message' => 'Payment Required', 'status' => '402'],
        403 => ['message' => 'Forbidden', 'status' => '403'],
        404 => ['message' => 'Not Found', 'status' => '404'],
        405 => ['message' => 'Method Not Allowed', 'status' => '405'],
        406 => ['message' => 'Not Acceptable', 'status' => '406'],
        407 => ['message' => 'Proxy Authentication Required', 'status' => '407'],
        408 => ['message' => 'Request Timeout', 'status' => '408'],
        409 => ['message' => 'Conflict', 'status' => '409'],
        410 => ['message' => 'Gone', 'status' => '410'],
        411 => ['message' => 'Length Required', 'status' => '411'],
        412 => ['message' => 'Precondition Failed', 'status' => '412'],
        413 => ['message' => 'Payload Too Large', 'status' => '413'],
        414 => ['message' => 'URI Too Long', 'status' => '414'],
        415 => ['message' => 'Unsupported Media Type', 'status' => '415'],
        416 => ['message' => 'Range Not Satisfiable', 'status' => '416'],
        417 => ['message' => 'Expectation Failed', 'status' => '417'],
        418 => ['message' => 'I\'m a teapot', 'status' => '418'],             // RFC2324
        421 => ['message' => 'Misdirected Request', 'status' => '421'],       // RFC7540
        422 => ['message' => 'Unprocessable Entity', 'status' => '422'],      // RFC4918
        423 => ['message' => 'Locked', 'status' => '423'],                    // RFC4918
        424 => ['message' => 'Failed Dependency', 'status' => '424'],         // RFC4918
        425 => ['message' => 'Reserved for WebDAV advanced collections expired proposal', 'status' => '425'],   // RFC2817
        426 => ['message' => 'Upgrade Required', 'status' => '426'],          // RFC2817
        428 => ['message' => 'Precondition Required', 'status' => '428'],     // RFC6585
        429 => ['message' => 'Too Many Requests', 'status' => '429'],         // RFC6585
        431 => ['message' => 'Request Header Fields Too Large', 'status' => '431'],                             // RFC6585
        451 => ['message' => 'Unavailable For Legal Reasons', 'status' => '451'],                               // RFC7725
        // SERVER ERROR
        500 => ['message' => 'Internal Server Error', 'status' => '500'],
        501 => ['message' => 'Not Implemented', 'status' => '501'],
        502 => ['message' => 'Bad Gateway', 'status' => '502'],
        503 => ['message' => 'Service Unavailable', 'status' => '503'],
        504 => ['message' => 'Gateway Timeout', 'status' => '504'],
        505 => ['message' => 'HTTP Version Not Supported', 'status' => '505'],
        506 => ['message' => 'Variant Also Negotiates (Experimental)', 'status' => '506'],                      // RFC2295
        507 => ['message' => 'Insufficient Storage', 'status' => '507'],                                        // RFC4918
        508 => ['message' => 'Loop Detected', 'status' => '508'],                                               // RFC5842
        510 => ['message' => 'Not Extended', 'status' => '510'],                                                // RFC2774
        511 => ['message' => 'Network Authentication Required', 'status' => '511'],                             // RFC6585
    );

    /**
     * Get the code message
     * @param  int $code
     * @return array ['message', 'status']
    */
    public function code($code) {
        $this->code = (int) $code;
        if (!$this->isValid()) return ['message' => "The HTTP status code {$this->code} is invalid.", 'status' => ''];
        http_response_code($this->code);
        return (isset($this->status[$this->code]))? $this->status[$this->code]: ['message' => "Unknown Status", 'status' => "{$this->code}"];
    }

    /**
     * Verify if response is valid
     *
     * @return bool
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
     */
    public function isValid() {
        return $this->code > 100 && $this->code < 600;
    }

}
