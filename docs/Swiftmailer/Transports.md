# SwiftMailer Transports
Back to [index](../index.md)

- [Introduction](#introduction)
- [Setup](#setup)
- [Available Transport](#available-transport)

<a name="introduction"></a>
## Introduction
Mailer transport allows user to use send email in different. 

**Please Note: This package contains SwifMailer transport it does not mean you will need to install SwiftMailer. You only need to install it if you want to use it.**

_Almost all transports except MailTrapper transports are the ported from Laravel Framework you can find more information [here](https://laravel.com/docs/5.3/mail#introduction)._
 
 <a name="available-transport"></a>
 ## Available transports
- [MailTrap](#mailtrap)
- [Mailgun](#mailgun)
- [Mandrill](#mandrill)
- [AWS SES](#ses)
- [Sparkpost](#spark)

<a name="mailtrap"></a>
### MailTrap
Setup and usage

    $transport = (new \IdeasBucket\Common\Swiftmailer\Transport\Mailtrap)
                 ->setUsername('your username')
                 ->setPassword('your password');
                 
    $mailer = Swift_Mailer::newInstance($transport);
    
    // Create a message
    $message = Swift_Message::newInstance('Wonderful Subject')
               ->setFrom(array('john@doe.com' => 'John Doe'))
               ->setTo(array('receiver@domain.org', 'other@domain.org' => 'A name'))
               ->setBody('Here is the message itself');
    
    // Send the message
    $result = $mailer->send($message); 

<a name="mailgun"></a>
### Mailgun

*Requires Guzzle which can be installed using composer*

    composer require guzzlehttp/guzzle

Setup and usage

    $transport = (new \IdeasBucket\Common\Swiftmailer\Transport\Mailgun(new GuzzleHttp\Client, 'YOUR API KEY', 'YOUR DOMAIN));
                 
    $mailer = Swift_Mailer::newInstance($transport);
    
    // Create a message
    $message = Swift_Message::newInstance('Wonderful Subject')
               ->setFrom(array('john@doe.com' => 'John Doe'))
               ->setTo(array('receiver@domain.org', 'other@domain.org' => 'A name'))
               ->setBody('Here is the message itself');
    
    // Send the message
    $result = $mailer->send($message);
          
<a name="mandrill"></a>
### Mandrill

*Requires Guzzle which can be installed using composer*

    composer require guzzlehttp/guzzle

Setup and usage

    $transport = (new \IdeasBucket\Common\Swiftmailer\Transport\Mandrill(new GuzzleHttp\Client, 'YOUR API KEY'));
                 
    $mailer = Swift_Mailer::newInstance($transport);
    
    // Create a message
    $message = Swift_Message::newInstance('Wonderful Subject')
               ->setFrom(array('john@doe.com' => 'John Doe'))
               ->setTo(array('receiver@domain.org', 'other@domain.org' => 'A name'))
               ->setBody('Here is the message itself');
    
    // Send the message
    $result = $mailer->send($message);
                   
<a name="ses"></a>
### AWS SES

*To use the Amazon SES driver you must first install the Amazon AWS SDK for PHP. You may install this library by adding the following line to your `composer.json` file's `require` section and running the `composer update` command:*

    "aws/aws-sdk-php": "~3.0"

Setup and usage

    use Aws\Ses\SesClient;
    
    $client = SesClient::factory([
        'profile' => '<profile in your aws credentials file>',
        'region'  => '<region name>'
    ]);
    
    $transport = (new \IdeasBucket\Common\Swiftmailer\Transport\Ses($client);
                 
    $mailer = Swift_Mailer::newInstance($transport);
    
    // Create a message
    $message = Swift_Message::newInstance('Wonderful Subject')
               ->setFrom(array('john@doe.com' => 'John Doe'))
               ->setTo(array('receiver@domain.org', 'other@domain.org' => 'A name'))
               ->setBody('Here is the message itself');
    
    // Send the message
    $result = $mailer->send($message);
                        
<a name="sparkpost"></a>
### SparkPost

*Requires Guzzle which can be installed using composer*

    composer require guzzlehttp/guzzle

Setup and usage

    $transport = (new \IdeasBucket\Common\Swiftmailer\Transport\SparkPost(new GuzzleHttp\Client, 'YOUR API KEY'));
                 
    $mailer = Swift_Mailer::newInstance($transport);
    
    // Create a message
    $message = Swift_Message::newInstance('Wonderful Subject')
               ->setFrom(array('john@doe.com' => 'John Doe'))
               ->setTo(array('receiver@domain.org', 'other@domain.org' => 'A name'))
               ->setBody('Here is the message itself');
    
    // Send the message
    $result = $mailer->send($message);                     
    