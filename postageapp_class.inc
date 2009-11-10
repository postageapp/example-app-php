<?php
  require_once('postageapp_conf.inc');
  
  class Postage
  {
    // Sends a message to Postage App
    function send_message($message, $recipients, $variables=NULL, $headers=NULL) {
      $content = array(
        'recipients'  => $recipients,
        'variables'   => $variables,
        'headers'     => $headers,
        'uid'         => time()
      );
      if (is_string($message)) {
        $content['message_name'] = $message;
      } else {
        $content['message'] = $message;
      }
      
      return Postage::post(
              'send_message', 
              json_encode(
                array(
                  'api_key' => POSTAGE_API_KEY, 
                  'arguments' => $content
                )
              )
             );
    }
    
    // Makes a call to the Postage App API
    function post($api_method, $content) {
      $ch = curl_init(POSTAGE_HOSTNAME.'/v.1.0/'.$api_method.'.json');
      curl_setopt($ch, CURLOPT_POSTFIELDS,  $content);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));   
      curl_setopt($ch, CURLOPT_POST, 1);
      $output = curl_exec($ch);
      curl_close($ch);
      return json_decode($output);
    }
  }
?>