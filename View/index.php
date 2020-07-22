<?php 
  include $_SERVER['DOCUMENT_ROOT']."/HangManGame/Model/HangmanGameFunctions.php";
  session_start();   
  if(!isset($_SESSION["word"])){
    $_SESSION["hangmanIsStarted"] = false;}
?>


<!DOCTYPE html>
<html>
  <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Hangman Game</title>   
   <link rel="stylesheet" type="text/css" href="/HangManGame/Styles/style.css">   
  </head>
 <body> 
 <div class="position"> 
 <h1>Hangman Game<br>by Pau Rodríguez</h1> 
  
  <?php          
  if(isset($_POST['newGame'])){    
    $hg = new HangmanGameFunctions();    
    $_SESSION["hg"] = $hg;
    $_SESSION["word"] = $hg->word;
    $_SESSION["hangmanIsStarted"] = true;
  }

  //Game is started
  if($_SESSION["hangmanIsStarted"]){
    //Check button is used.
    if(isset($_POST["charInPlay"])){
      //Game is alive condition     
      if(($_SESSION["hg"]->MAX_ATTEMPTS - $_SESSION["hg"]->attempts>0) && ($_SESSION["hg"]->rightchars != $_SESSION["hg"]->totalChars)){        
        $_SESSION["hg"]->charInPlay = strtoupper($_POST["charInPlay"]);        
      }
    }    
    //Checking result of selected-by-user valid input character.
    $_SESSION["hg"]->checkingResult($_SESSION["hg"]->charInPlay);
    //Check final score
    $_SESSION["hg"]->checkingFinalScore();
    //Print the word hidding not known letters.
    echo $_SESSION["hg"]->printWord();
    //Show the appropiate picture based on remaining attempts.
    ?><br><br><img class="prop" src="/HangManGame/Sources/<?php echo $_SESSION["hg"]->showingPicture()?>.webp"><?php    
    //Show the letters used.
    echo '<br><br>Letras utilizadas: <br><br>'.$_SESSION["hg"]->showLettersUsed($_SESSION["hg"]->charInPlay). '<br><br>';
  }else{
    //Check button is used and game isn't started.
    if(isset($_POST['check'])){
      echo '<script>alert("Debes pulsar primero el botón Nueva Palabra");</script>';
    }
  }   
  ?>

<form name="userInput" action="" method="post">
    <br><br>Introduce una letra:<br>
    <input type="text" id="charInPlay" name="charInPlay" maxlength="1" size="1" pattern="[A-Za-z0-9]"/> 
    <input type="submit" name="check" value="Comprobar" onclick="return validateInput()"/><br><br>
  </form>

  <form method="post" action="">
    <input type="submit" name="newGame" class="button" value="Nueva palabra"/>
  </form>

  <!--Checking valid input options-->
  <script type="application/javascript">
    function validateInput(){
      var x=document.forms["userInput"]["charInPlay"].value;
      if (x=="" || x==" " || !isNaN(x)){
        alert("Debes introducir una letra.");
        return false;
      }
    }
  </script>
  </div>
 </body>
</html>