# Extension
Back to [index](../index.md)

- [Introduction](#introduction)
- [Setup](#setup)
- [Available Filters](#available-filters)

<a name="introduction"></a>
## Introduction
This Twig extension provides several filters that can be used in Twig template. 

**Please Note: This package contains Twig extension it does not mean you will need to install Twig. You only need to install it if you want to use it.**

<a name="setup"></a>
## Setup
    $twig = new Twig_Environment($loader);
    $twig->addExtension(new \IdeasBucket\Common\Twig\Extension);
    
<a name="available-filters"></a>
## Available Filters
- [bin2hex](#bin2hex)
- [slug](#slug)
- [md5](#md5)    
- [hash](#hash) 
    
<a name="bin2hex"></a>
### bin2hex
Returns an ASCII string containing the hexadecimal representation of str. The conversion is done byte-wise with the high-nibble first.  
    
    Usage {{ test|bin2hex }}
    
    // Outputs
    74657374
    
<a name="slug"></a>
### slug
Returns normalized version of string that can be use as index that is easy to remember. Requires **php_intl** extension.  
    
    Usage {{ Hello World|slug }}
    
    // Outputs - By default uses - as separator and converts to lower case.
    hello-world
     
    Usage {{ Hello World|slug(false, '_') }}
        
    // Outputs - Using different separator and no string coversion
    Hello_World 
    
<a name="md5"></a>
### md5
Returns md5 hash of a input  
    
    Usage {{ Hello World|md5 }}
    
    // Outputs
    b10a8db164e0754105b7a99be72e3fe5
    
<a name="hash"></a>
### hash
Returns hash of a input  
    
    Usage {{ Hello World|hash('sha1') }}
    
    // Outputs
    0a4d55a8d778e5022fab701977c5d840bbc486d0
You can choose any algorithm that are compatible with native PHP `hash` function.     