
const consultButton = document.querySelector('.consult-a');


consultButton.addEventListener('click', () => {
    checkAnswer();
});

let result = [];

function checkAnswer() {
    const questions = document.querySelectorAll('.lesson-question');
    result = [];
    questionCounter = 1;

    for (let question in questions) {
        const radioButtons = document.querySelectorAll('input[name="alternative-' + questionCounter + '"]');
        radioCounter = 1;
        
        for (const radioButton of radioButtons) {
            if (radioButton.checked) {
                if (radioCounter == 1){
                    answerLetter = 'A';
                } else if (radioCounter == 2) {
                    answerLetter = 'B';
                } else if (radioCounter == 3) {
                    answerLetter = 'C';
                } else if (radioCounter == 4) {
                    answerLetter = 'D';
                } else if (radioCounter == 5) {
                    answerLetter = 'E';
                }
                result.push(answerLetter);
            } else if (radioCounter == 5) {
                result.push(0);
            } else {
                radioCounter += 1;
            }

        }

        questionCounter += 1;
    }

    createCookie('result', result, 1);
}


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

