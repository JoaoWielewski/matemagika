const dailyGameContainer = document.querySelector('.daily-game-container');
const startButton = document.querySelector('.daily-game-start-btn');
const dailyGameExplanation = document.querySelector('.daily-game-explanation');
const dailyGameRecord = document.querySelector('.daily-game-record');
const dailyGameScore = document.querySelector('.daily-game-score');
const dailyGameTime = document.querySelector('.daily-game-time');
const dailyGameExplanationEnd = document.querySelector('.daily-game-explanation-end');
const endButton = document.querySelector('.daily-game-end-btn');

const operationPatterns = ['x o x', '(x o x) o x', 'x o (x o x)', '(x o x) o (x o x)'];
const operators = ['+', '-', '×'];
let score = 0;
let gameOver = false;


startButton.addEventListener('click', () => {
    console.log('a');
    createCookie('started', true, 1);
    startButton.classList.add('hide');
    countdownTimer();
    createInput();
    createInput();
    createInput();
})


async function createInput() {
    await sleep(250);

    const questionInput = document.createElement("input");
    questionInput.classList.add("daily-game-input");
    dailyGameContainer.appendChild(questionInput);

    let leftPosition = Math.floor(Math.random() * (70 - 15)) + 15;
    let topPosition = Math.floor(Math.random() * (80 - 25)) + 25;
    questionInput.style.left = leftPosition + '%';
    questionInput.style.top = topPosition + '%';

    let operationNumber = Math.floor(Math.random() * operationPatterns.length);
    operationArray = operationPatterns[operationNumber].split('');
    for (let i = 0; i < operationArray.length; i++) {
        if (operationArray[i] == 'x') {
            bigNumber = Math.round(Math.random() * 10);

            if (bigNumber) {
                operationArray[i] = Math.floor(Math.random() * 9);
            } else {
                operationArray[i] = Math.floor(Math.random() * (99 - 10)) + 10;
            }

        } else if (operationArray[i] == 'o') {
            let operatorNumber = Math.floor(Math.random() * operators.length);
            operationArray[i] = operators[operatorNumber];
        }
    }

    operationString = operationArray.join('');
    questionInput.placeholder = operationString;

    formattedOperation = operationString.replace(/×/g, '*')
    correctAnswer = eval(formattedOperation);

    questionInput.addEventListener('change', () => {
        formattedOperation = questionInput.placeholder.replace(/×/g, '*')
        correctAnswer = eval(formattedOperation);

        if (parseInt(questionInput.value) == correctAnswer) {
            const correctSymbol = document.createElement('div');
            correctSymbol.innerHTML = '<i class="fa-solid fa-check"></i>';
            correctSymbol.classList.add('correct-answer');
            dailyGameContainer.appendChild(correctSymbol);
            symbolAnimation(questionInput, correctSymbol, leftPosition, topPosition);
            score += 1;

            dailyGameScore.innerHTML = '<i class="fa-solid fa-check"></i> ' + score;
            createInput();

        } else {
            const wrongSymbol = document.createElement('div');
            wrongSymbol.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            wrongSymbol.classList.add('wrong-answer')
            dailyGameContainer.appendChild(wrongSymbol);
            symbolAnimation(questionInput, wrongSymbol, leftPosition, topPosition);
            createInput();

        }
        questionInput.classList.add('hide');
    })

    decreaseInputSize(questionInput);

}


function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
  }


async function decreaseInputSize(input) {
    let startingInputHeight = input.clientHeight;
    let inputWidth = input.clientWidth;
    let inputHeight = input.clientHeight;

    let widthDifference = inputWidth / 100;
    let heightDifference = inputHeight / 100;

    while (true) {
        input.style.width = (inputWidth - widthDifference - 3) + 'px';
        input.style.height = (inputHeight - heightDifference - 2) + 'px';
        await sleep(250);

        inputWidth = input.clientWidth;
        inputHeight = input.clientHeight;

        if(startingInputHeight / 3 > inputHeight) {
            input.classList.add("hide");
            await sleep(3000);
            if (!input.classList.contains('answered') && !gameOver) {
                createInput();
            }
            break
        }

        if(startingInputHeight / 1.1 > inputHeight){
            input.style.backgroundColor = 'rgb(212, 248, 194)';
            input.style.borderColor = 'rgb(70, 191, 3)';
        }

        if(startingInputHeight / 1.2 > inputHeight){
            input.style.backgroundColor = 'rgb(224, 248, 194)';
            input.style.borderColor = 'rgb(110, 191, 3)';
        }

        if(startingInputHeight / 1.3 > inputHeight){
            input.style.backgroundColor = 'rgb(236, 248, 194)';
            input.style.borderColor = 'rgb(150, 191, 3)';
        }

        if(startingInputHeight / 1.4 > inputHeight){
            input.style.backgroundColor = 'rgb(248, 248, 194)';
            input.style.borderColor = 'rgb(191, 191, 3)';
        }

        if(startingInputHeight / 1.5 > inputHeight){
            input.style.backgroundColor = 'rgb(248, 236, 194)';
            input.style.borderColor = 'rgb(191, 160, 3)';
        }

        if(startingInputHeight / 1.6 > inputHeight){
            input.style.backgroundColor = 'rgb(248, 224, 190)';
            input.style.borderColor = 'rgb(191, 128, 3)';
        }

        if(startingInputHeight / 1.7 > inputHeight){
            input.style.backgroundColor = 'rgb(248, 212, 184)';
            input.style.borderColor = 'rgb(191, 96, 3)';
        }

        if(startingInputHeight / 1.8 > inputHeight){
            input.style.backgroundColor = 'rgb(248, 194, 178)';
            input.style.borderColor = 'rgb(191, 64, 3)';
        }

        if(startingInputHeight / 1.9 > inputHeight){
            input.style.backgroundColor = 'rgb(248, 182, 175)';
            input.style.borderColor = 'rgb(191, 32, 3)';
        }

        if(startingInputHeight / 2 > inputHeight){
            input.style.backgroundColor = 'rgb(248, 170, 170)';
            input.style.borderColor = 'rgb(191, 0, 3)';
        }

        
    }
}


async function symbolAnimation(input, symbol, leftPercentage, topPercentage) {
    symbol.style.left = leftPercentage + 5 + '%';
    symbol.style.top = topPercentage + '%';
    
    symbol.style.transform = 'rotate(720deg)';
    await sleep(100);
    symbol.style.transform = 'rotate(0deg)';
    await sleep(1250);
    symbol.classList.add('hide');
    input.classList.add('answered');

}


function countdownTimer() {
    let now = new Date();
    let targetTime = new Date();
    targetTime.setMinutes(now.getMinutes() + 0);
    targetTime.setSeconds(now.getSeconds() + 31);

    let timeUpdate = setInterval(async function() {

        let now = new Date();
        let timeDifference = targetTime - now;

        let minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

        if (seconds == 0){
            seconds = '00';
        }

        if (seconds.toString().length == 1) {
            seconds = '0' + seconds;
        }

        dailyGameTime.innerHTML = '<i class="fa-solid fa-clock"></i> ' + minutes + ':' + seconds;

        if(timeDifference < 0) {
            clearInterval(timeUpdate);
            dailyGameTime.innerHTML = '<i class="fa-solid fa-clock"></i> 0:00';
            gameOver = true;

            const inputs = document.querySelectorAll('.daily-game-input');
            inputs.forEach(input => input.classList.add('hide'));

            dailyGameExplanationEnd.innerHTML = 'O tempo acabou! Você ganhou ' + score * 5 + ' moedas e ' + score * 10 + ' pontos de experiência. Aperte o botão acima para coletar suas recompensas.';
            dailyGameExplanationEnd.classList.remove('hide');
            endButton.classList.remove('hide');

            await sleep(1000);
            const otherInputs = document.querySelectorAll('.daily-game-input');
            otherInputs.forEach(input => input.classList.add('hide'));
        }

    }, 1000);
    

}

endButton.addEventListener('click', () => {
    createCookie('result', score, 1);
})


function createCookie(name, value, minutes) {
    var expires;
      
    if (minutes) {
        var date = new Date();
        date.setTime(date.getTime() + (minutes * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
      
    console.log(name + "=" + 
    value + expires + "; path=/")
    
    document.cookie = name + "=" + 
        value + expires + "; path=/";
}