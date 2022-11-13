<?php
class NetlifyCall {

  private $token;
  private $endpoint;

  public function __construct($endpoint, $token)
    {
      $this->token = $token;
      $this->endpoint = $endpoint;
    }

  protected function getUrl() {
    return "https://api.netlify.com/api/v1/".$this->endpoint;
  }

  public function call_cURL() {
    $output = $this->_execute($this->getUrl());
    return json_decode($output);
  }

  private function _execute($url) {
    $ch = curl_init($url);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
          'Content-Type: application/json',
          'Authorization: Bearer '.$this->token
        )
    );
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
  }
}
