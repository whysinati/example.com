<?php
/*
//Create a RegEx pattern to determine the validity of the user submitted email
// - allow up to two strings with dot concatenation any letter, any case any number with _- before the @
// - require @
// - allow up to two strings with dot concatenation any letter, any case any number with - after the at
// - require at least 2 letters and only letters for the domain
$validEmail = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";

//Extract $_POST to a data array
$data = $_POST;

//Create an empty array to hold any error we detect
$errors = [];

foreach($data as $key => $value){
  echo "{$key} = {$value}<br><br>";

  //Use a switch statement to change your behavior based upon the form field
  switch($key){
      case 'email':
        if(preg_match($validEmail, $value)!==1){
            $errors[$key] = "Invalid email";
        }

      break;

      default:
        if(empty($value)){
            $errors[$key] = "Invalid {$key}";
        }
      break;
  }

}

var_dump($errors);
 ?>

<?php

class Validate{

    public $validation = [];

    public $errors = [];

    private $data = [];

    public function notEmpty($value){

        if(!empty($value)){
            return true;
        }

        return false;

    }

    public function email($value){

        if(filter_var($value, FILTER_VALIDATE_EMAIL)){
            return true;
        }

        return false;

    }

    public function check($data){

        $this->data = $data;

        foreach(array_keys($this->validation) as $fieldName){

            $this->rules($fieldName);
        }

    }

    public function rules($field){
        foreach($this->validation[$field] as $rule){
            if($this->{$rule['rule']}($this->data[$field]) === false){
                $this->errors[$field] = $rule;
            }
        }
    }

    public function error($field){
        if(!empty($this->errors[$field])){
            return $this->errors[$field]['message'];
        }

        return false;
    }

    public function userInput($key){
        return (!empty($this->data[$key])?$this->data[$key]:null);
    }
}
*/

//require '../core/About/src/Validation/Validate.php';
require '../core/processContactForm.php';

$meta=[];
$meta['title']="Contact Me";
$meta['description']="My contact page";
$meta['keywords']=false;

/*
//Declare namespaces
use About\Validation;

$valid = new About\Validation\Validate();

$args = [
  'name'=>FILTER_SANITIZE_STRING,
  'subject'=>FILTER_SANITIZE_STRING,
  'message'=>FILTER_SANITIZE_STRING,
  'email'=>FILTER_SANITIZE_EMAIL,
];

$input = filter_input_array(INPUT_POST, $args);

$message = null;

if(!empty($input)){

    $valid->validation = [
        'email'=>[[
                'rule'=>'email',
                'message'=>'Please enter a valid email'
            ],[
                'rule'=>'notEmpty',
                'message'=>'Please enter an email'
        ]],
        'name'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please enter your first name'
        ]],
        'message'=>[[
            'rule'=>'notEmpty',
            'message'=>'Please add a message'
        ]],
    ];

    $valid->check($input);

    if(empty($valid->errors)){
        require '../core/mailgun.php';
        $message = "<div class=\"message-success\">Your form has been submitted!</div>";
        //header('Location: thanks.php');
    }else{
        $message = "<div class=\"message-error\">Your form has errors!</div>";
    }
}*/
// was a closing php tag here

/*
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Contact Me - YOUR-NAME</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./dist/css/main.css" type="text/css">
  </head>
  <body>
  
    <header>
      <span class="logo">My Website</span>
      <a id="toggleMenu">Menu</a>
      <nav>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="resume.html">Resume</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </nav>
    </header>
    
    <main>
*/
$content=<<<EOT
      <h1>Contact Me - YOUR-NAME</h1>
      {$message}
      
      <form action="contact.php" method="POST">
        
        <input type="hidden" name="subject" value="New submission!">
        
        <div>
          <label for="name">Name</label>
          <input id="name" type="text" name="name" value="{$valid->userInput('name')}">
          <div class="text-error">{$valid->error('name')}</div>
        </div>

        <div>
          <label for="email">Email</label>
          <input id="email" type="text" name="email" value="{$valid->userInput('email')}">
          <div class="text-error">{$valid->error('email')}</div>
        </div>

        <div>
          <label for="message">Message</label>
          <textarea id="message" name="message">{$valid->userInput('message')}</textarea>
          <div class="text-error">{$valid->error('message')}</div>
        </div>

        <div>
          <input type="submit" value="Send">
        </div>

      </form>
EOT;

require '../core/layout.php';
/*
      </main>
    
    <script>
        var toggleMenu = document.getElementById('toggleMenu');
        var nav = document.querySelector('nav');
        toggleMenu.addEventListener(
          'click',
          function(){
            if(nav.style.display=='block'){
              nav.style.display='none';
            }else{
              nav.style.display='block';
            }
          }
        );
      </script>
  </body>
</html>
*/
/* <!DOCTYPE html>
<html lang="en">
    <head>
        <title>Name Namey Name - Contact</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <meta name="description" content="This is a awesome contact page">
        <meta name="keywords" content="contact, awesome, programmer, geek squad, robots, jobs
        ">
        <link rel="shortcut icon" href="https://zeyeland.com/images/robot.gif" />
        <link rel="stylesheet" type="text/css" href="./dist/css/main.css">
    </head>
    <body>
        <header>
            <span class="logo">My WebSite</span>
            <a id="toggleMenu">Menu<a>
            <nav>
                <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="resume.html">Resume</a></li>
                <li><a href="contact.html">Contact</a></li>
                </ul>
            </nav>
        </header>
        <form action="contact.php" method="POST">


            <div>
                <label for="name">Name</label>
                <input id="name" type="text" name="name">
            </div>

            <div>
                <label for="email">Email</label>
                <input id="email" type="text" name="email">
            </div>

            <div>
                <label for="message">Message</label>
                <textarea id="message" name="message"></textarea>
            </div>

            <div>
                <input type="hidden" name="subject" value="New submission!">
            </div>

            <div>
                <input type="submit" value="Send">
            </div>

        </form>
        <script>

                var toggleMenu = document.getElementById('toggleMenu');
                var nav = document.querySelector('nav');
                toggleMenu.addEventListener(
                  'click',
                  function(){
                    if(nav.style.display=='block'){
                      nav.style.display='none';
                    }else{
                      nav.style.display='block';
                    }
                  }
                );
        </script>
    </body>
</html>
*/