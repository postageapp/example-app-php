<?php 
  require_once('postageapp_class.inc');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang='en' xmlns='http://www.w3.org/1999/xhtml'>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="stylesheets/main.css" type="text/css" media="screen" charset="utf-8" />
    <title>Php example for PostageApp</title>
  </head>
<body>
  <div class="content_wrap">
    <div class="header">
      <img src="images/postageapp_php.gif" alt="Postageapp_php"/>
      <h1>Php example for PostageApp<br/><a target="_blank" class="small" href="">http://postageapp.com/docs</a></h1>
    </div>
  
    <div class="content">
      <?php
        if (isset($_POST['email']) && $_POST['email'] !='') {
          $response = process_submited_form();
        }
      ?>
      <h2>Fill in the form on the right to send a message through PostageApp</h2>

      <div class="left_column">
        <?php
          if (POSTAGE_API_KEY == 'ENTER YOUR API KEY HERE') {
            echo "<p>Before sending a message you'll need to add your API key to  postageapp_conf.inc and restart your server.</p>";
          } else {
            echo '<p>Your API key is: <span class="api_key">'.POSTAGE_API_KEY.'</span></p>';
          }
        
        ?>
        <p>
          Enter a subject, your email address and a value for the {{name}} variable.
        </p>
        
        <?php
          if($response!=null) {
            echo '
              <div class="response">
                <p>Here is the response received from PostageApp:</p>
            ';
            echo '<pre class="debug_dump">';
            print_r ($response);
            echo '</pre></div>';
          }
        
        ?>
      </div>

      <div class="right_column">
        <form method="post">
          <p><label>Subject:</label> <input type="text" value="My name is {{name}}" name="subject"/></p>
          <p><label>My email address:</label> <input type="text" name="email"/></p>
          <p><label>{{name}} variable:</label> <input type="text" name="variable"/></p>
          <p>
            <label>Plain text content:</label>
            <br/>
            <textarea rows="5" name="plain_text_content" cols="40">Hi,
  My name is {{name}} and this is a test.</textarea>
          </p>
          <p>
            <label>HTML content:</label> 
            <br/>
            <textarea rows="5" name="html_text_content" cols="40">&lt;p&gt;Hi,&lt;/p&gt;
  &lt;p&gt;My name is &lt;b&gt;{{name}}&lt;/b&gt; and this is a test.&lt;/p&gt;
            </textarea>
          </p>
          <p><label> </label><input type="submit" value="Send message" name="commit"/></p>
        </form>
      </div>
    </div>
    <div class="footer">
      developed by THE WORKING GROUP
    </div>
  </div>
</body>
</html>

<?php
  function process_submited_form() {
    # Setup the headers
    $headers = array(
      'From'      => 'my_test@somewhere.com',
      'Reply-to'  => 'my_test@somewhere.com',
      'Subject'   => $_POST['subject']
    );

    # Who's going to receive this email
    $recipients = $_POST['email'];

    # The content of the message
    $message = array(
      'text/plain' => $_POST['plain_text_content'],
      'text/html' => $_POST['html_text_content']
  
    );
  
    # Some variables
    $variables = array('name' => $_POST['variable']);

    # Send it all
    $response = Postage::send_message($message, $recipients, $variables, $headers);
    if ($response->response->status == 'ok') {
      echo '<div class="flash_message notice">Your message has been sent. Check your project in PostageApp</div>';
    } else {
      echo '<div class="flash_message error">There was an error sending your message</div>';
    }
    return $response;
  }
?>

