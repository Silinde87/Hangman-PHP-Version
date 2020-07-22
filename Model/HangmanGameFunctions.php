<?php

class HangmanGameFunctions{
    public $totalChars=0, $rightchars=0, $attempts  = 0;    
    public $MAX_ATTEMPTS = 6;
    public $word='', $printedWord="", $charInPlay, $lettersUsed="";    
    public $abc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public $letters;
    public $imgNum=22;
    
    
    //Constructor initializing all the game needs.
    function __construct(){        
        $char_arr = str_split($this->abc);        
        $this->word = $this->choosingWord();       
        $this->totalChars = strlen($this->word)-2;
        $this->letters = array_fill_keys($char_arr,false);
    }


    /*
        Choosing a random word from a list
        Return value: String with a random word
    */
    function choosingWord(){
        if(file_exists($_SERVER['DOCUMENT_ROOT']."/HangManGame/Sources/wordlist.txt")){
            $f_contents = file($_SERVER['DOCUMENT_ROOT']."/HangManGame/Sources/wordlist.txt");
            $word = $f_contents[rand(0, count($f_contents) - 1)];
            $word = strtoupper($word);
            $imgNum = 22;
            return $word;
        }else{            
            echo '<script type="application/javascript">
                alert("Falta el archivo de texto");
            </script>';
            return;
        }
        

    }

    /*
        Checking result of selected-by-user 
        valid input character.
    */
    function checkingResult($c){        
        switch ($this->matchingChar($c)) {
            case 1:
              //Case repeated letter
              if(($this->MAX_ATTEMPTS - $this->attempts) == 0) break;          
              echo "¡REPETISTE!, te quedan ".($this->MAX_ATTEMPTS - $this->attempts)." intentos<br><br>";
              $this->imgNum = ($this->MAX_ATTEMPTS - $this->attempts);
             break;
            case 2:
              //case right letter
              if($this->totalChars - $this->rightchars == 1){
                $this->rightchars++;
                break;
              }else{
                echo "¡ACIERTO! La letra ".$this->charInPlay." ha sido encontrada<br><br>";
                $this->rightchars++;
                break;
              }
            case 3:
              //case wrong letter
              if(($this->MAX_ATTEMPTS - $this->attempts) == 0) break; 
              echo "¡FALLASTE!, te quedan ".($this->MAX_ATTEMPTS - $this->attempts)." intentos<br><br>";   
              $this->imgNum = ($this->MAX_ATTEMPTS - $this->attempts);
              break;
            default:
               break;
        }
    }

    /*
        This method checks the final score.
    */
    function checkingFinalScore(){
     if(($this->MAX_ATTEMPTS - $this->attempts) == 0 | ($this->totalChars - $this->rightchars) == 0){
        if(($this->totalChars - $this->rightchars) == 0){
          echo "¡HAS GANADO! La palabra es:<br><br>";
          $this->imgNum = 22;
          $_SESSION["hangmanIsStarted"] = false;
        }else if(($this->MAX_ATTEMPTS - $this->attempts) == 0){
          foreach (str_split($this->word) as $c) {
            $this->letters[$c] = true;
          }
          echo "HAS PERDIDO. La palabra era:<br><br>";
          $this->imgNum = ($this->MAX_ATTEMPTS - $this->attempts);
          $_SESSION["hangmanIsStarted"] = false;
        }
      }
    }

    /*
        This method finds the char at word and changes the value at array
        Return values: 1 if the character is repeated, 2 if it's a match, 3 if it's a failñ
    */
    function matchingChar($c){        
        if(is_null($c)){
            return 0;
        }else{
            if($this->letters[$c] == true){
                $this->attempts++;            
                //Case repeating letter
                return 1;
            }else{            
                if (strpos($this->word, $c) !== false) {
                    $this->letters[$c]=true;                
                    //Case right letter
                    return 2;
                }else{
                    $this->attempts++;                
                    //Case wrong letter
                    return 3;
                }
            }
        }
    }
    
    /*
        This method prints the word with blank spaces while the game works.
        Return value: String with the Word hidding unkown characters
    */
    function printWord(){
        $this->printedWord = "";
        $this->rightchars = 0;
        for ($i=0; $i < $this->totalChars; $i++) { 
            if($this->word{$i}=='-'){
                $this->printedWord .= '- ';
                continue;
            }else if($this->word{$i}==' '){
                $this->printedWord .= str_repeat('&nbsp;', 5);
                continue;
            }
            if($this->letters[$this->word{$i}]==true){
                $this->printedWord .= $this->word{$i}.' ';
                $this->rightchars++;
            }else{
                $this->printedWord .= '__ ';
            }        
        }
        return $this->printedWord;
    }

    function showingPicture(){
        return $this->imgNum;
    }

    function showLettersUsed($c){        
        if(is_null($c)) return "Ninguna todavía";
        if(strpos($this->lettersUsed,$c) !== false){
            $lettersUsedArr = str_split($this->lettersUsed);
            sort($lettersUsedArr);        
            return implode(" - ", $lettersUsedArr);
        }else{
            $this->lettersUsed .= $c;
            $lettersUsedArr = str_split($this->lettersUsed);
            sort($lettersUsedArr);        
            return implode(" - ", $lettersUsedArr);
        }
    }

    
}
?>
