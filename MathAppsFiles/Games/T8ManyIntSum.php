<?php if(session_status() == PHP_SESSION_NONE) session_start();?>
<!DOCTYPE html>
<html>

<head>
  <link rel = "stylesheet" href = "NewOrg.css">
  <title> #8 Many Integers Sum </title>
</head>

<body class="wholeBody" id="body">
<div id="gameBox">

  <div id="topRow">
    <div class="topStuff" id="questions" style="color:red";>
      <div>
        Questions:
      </div>
      <div>
        <div class="scoreNum" id="answered">0</div>
      </div>
    </div>
    <div class="topStuff" id="title">(#8) Add and Subtract (Sum) Practice<br>Many Integers

<?php if (!isset($_SESSION["UsersName"])) {?> <!--add to all games-->
 <br>You are not logged in. Return to Main Page.
 <a href="..//OptionsPage.php"><button>Back to Main Page</button></a><br>
 </div></div></div>
<?php };?>

<?php if (isset($_SESSION["UsersName"])) {?>

      <div id="SecToEnd">
        You have 90 seconds to answer.
      </div>
    </div>
    <div class="topStuff" id="score">
      <div>
        Score:
      </div>
      <div>
        <div id="total" class="scoreNum">0</div>
      </div>
    </div>
  </div>

  <div id="stats">
    <div id="Gstat">Game Stats:</div>
    <div id="timeUpdate">time</div><!--timer-->
    <div class="sumStats">
      <div>2 or 3 Numbers:</div>
    </div>
    <div class="sumStats">
      <div id="twoCorr">0</div>
      <div class="for">for</div>
      <div id="twoTotal">0</div>
    </div>
    <div class="sumStats">
      <div id="twoPrct">x</div>
      <div>%</div>
    </div>
    <div class="sumStats">
      <div>>3 Numbers:</div>
    </div>
    <div class="sumStats">
      <div id="moreCorr">0</div>
      <div class="for">for</div>
      <div id="moreTotal">0</div>
    </div>
    <div class="sumStats">
      <div id="morePrct">x</div>
      <div>%</div>
    </div>
  </div>

  <div id="actionBox">
    <div id="qAndA" >
      <button class="button button1" id="startbtn" onclick="startGame()"> Start Game </button>
      <button class="button button1" id="continueButton" style="visibility:hidden" onclick="doMath()"> Next Question </button>
      <div class="lines" id="qLine">
        <div id="question" style="font-size:3vw;">
          Question Goes Here
        </div>
      </div>
      <div class="rBox">
        <div id="rLine">
          <div id="result">Feedback</div>
        </div>
        <div id="ritLine">
          <div id="right">Answer if Wrong</div>
        </div>
      </div>
      <div class="input" >Your Answer: <br>
        <input type="text" id="myText" onkeyup="enterKey(event)" value ="" autoblur>
      </div>
      <div class=button>
        <button class="button" id="answerButton" onclick="checkAnswer()" style="visibility:hidden"> Click here to check. </button>
      </div>
    </div>
  </div>

  <div id="keyboard">
    <button class="key" id="btn1" value="1" onclick="input(this)">1</button>
    <button class="key" id="btn2" value="2" onclick="input(this)" >2</button>
    <button class="key" id="btn3" value="3" onclick="input(this)" >3</button>
    <button class="key" id="btn4" value="4" onclick="input(this)" >4</button>
    <button class="key" id="btn5" value="5" onclick="input(this)" >5</button>
    <button class="key" id="btn6" value="6" onclick="input(this)" >6</button>
    <button class="key" id="btn7" value="7" onclick="input(this)" >7</button>
    <button class="key" id="btn8" value="8" onclick="input(this)" >8</button>
    <button class="key" id="btn9" value="9" onclick="input(this)" >9</button>
    <button class="key" id="btnDel" value="<<" onclick="del()" >back</button>
    <button class="key" id="btnNeg" value="-/+" onclick="NegPosSwitch()" >+/-</button>
    <button class="key" id="btn0" value="0" onclick="input(this)" >0</button>
  </div>
  
  <div id="footer">
    Refresh Page to Restart. == or ==>  <button class="key" onclick="goHome()">Click Here to Return to Game Page</button>
  </div>
</div>

<script type="text/javascript">

var a = 0;
var d = 0;
var c = 0;
var e = 0;
var ans = 0;
var correct = 0;
var yours = 0;
var answered = 0;
var score = 0;
var was = ""
var shouldBe = "";
var waiting = "";
var quant = 0;
var  twoCor = 0, twoTot = 0, twoPct = 0, moreCor = 0, moreTot = 0, morePct = 0, qKind = "";
var sgn
var t1, t2, tDiff, min, sec;//timer
var set = [], finalAns = 0, qSet = [];
var fired = true; // enter key disabled
var smile = 0, frown = 0, gameNameber = "8"; //use these along with answered to update score function //New for Db************
var fixedTime = 85000;

//start math section
function doMath() {
  document.getElementById("myText").disabled=false; //make input allowed**************
  document.getElementById("continueButton").style.visibility = 'hidden';
  document.getElementById("right").innerHTML = "";
  document.getElementById("answerButton").style.visibility = 'visible';
  if (answered < 50) {
    askQuestion();
  } else {
    t2 = Date.now();//timer
    tDiff = (t2 - t1)/1000;//timer
    min = Math.floor(tDiff/60)//timer
    sec = Math.floor(tDiff % 60);//timer
    document.getElementById("question").innerHTML = "Game Over: " + min + " minutes " + sec + " seconds";//timer
    document.getElementById("result").innerHTML = "Refresh page to start over."
    document.getElementById("answerButton").style.visibility = 'hidden';
  }
}

function askQuestion() {
  many = pickRandom()
  if (many < 4) {
    qKind = "Two";
    twoTot++;
  }
  if (many > 3) {
    qKind = "More";
    moreTot++;
  }
  makeString(many);
  //correct = ans.toString();
  document.getElementById("myText").value = "";
  document.getElementById("result").innerHTML = "???";
  document.getElementById("myText").blur();
  was = "it is";
  //shouldBe = ans;
  scramble(ans);//make scramble set
  ans = 0;//make scramble set
  answerSleep();
  return
}

function pickRandom() {
  quant = Math.ceil(Math.random()*5);
  quant = quant + 2;
  return quant;
}
//end of ask question
//start of question makeString
function makeString(i) {
  str="";
  sum = 0;
  t = "+";
  s = 0;
  while (i > 0) {
    if (i > 1) {
      s = selectNumber();
      if (s > 0) {
        str = str.concat("\(+" + s + "\) ");
      } else {str = str.concat("\(" + s + "\) ");
      }
      if (t == "+") {
        sum = sum + s;
      } else {
        sum = sum - s
      }
      t = pickSign();
      str = str.concat(" " + t + " ");
      i--;
    } else {
      var s = selectNumber();
      if (t == "+") {
        sum = sum + s;
      } else {
        sum = sum - s
      }
      if (s > 0) {
        str = str.concat("\(+" + s + "\) ");
      } else {str = str.concat("\(" + s + "\) ");
      }
      i--;
    }
  }
  document.getElementById("question").innerHTML=str;
  ans = sum;
  return ans;
}

function selectNumber() {
  a = Math.floor((Math.random() * 25)+1);

  b = Math.floor(Math.random() * 100)
  if (b % 2 == 0) {
    a = a * -1;
  }
  return a;
}

function pickSign() {
  ran=Math.ceil((Math.random())*100);
  if (ran > 50) {
    sgn = "-";
  } else {
    sgn = "+";
  }
  return sgn;
}
//end of quesion making
//end math section

//Start of Stats Calculation
function correctAnswer() {
  if (qKind == "Two") {
    twoCor++;
  }
  if (qKind == "More") {
    moreCor++;
  }
  smileScore(); //New for Db*********************
  calculatePercent();
  writeStats();
}

function wrongAnswer() {
  frownScore(); //New for Db********************
  calculatePercent();
  writeStats();
}

function calculatePercent() {
  if (twoTot > 0) {
    twoPct = (twoCor / twoTot) * 100;
    twoPct = twoPct.toFixed(1);
  }
  if (moreTot > 0) {
    morePct = (moreCor / moreTot) * 100;
    morePct = morePct.toFixed(1);
  }
}

function writeStats() {
  document.getElementById("twoCorr").innerHTML = twoCor;
  document.getElementById("twoTotal").innerHTML = twoTot;
  document.getElementById("twoPrct").innerHTML = twoPct;
  document.getElementById("moreCorr").innerHTML = moreCor;
  document.getElementById("moreTotal").innerHTML = moreTot;
  document.getElementById("morePrct").innerHTML = morePct;
}
//End of Stats Calculation
</script>
<?php };?>
<script type="text/javascript" src="TsOutsideJSgamePlay.js"></script>
</body>
</html>