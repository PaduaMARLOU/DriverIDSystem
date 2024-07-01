//open form
document.addEventListener('DOMContentLoaded', () => {
    const btnPopUp = document.querySelector('.btn-pop-up');
    const registration = document.querySelector('.registration');

    if (btnPopUp && registration) {
        btnPopUp.addEventListener('click', () => {
            registration.classList.add('active-popup');
        });
    } else {
        console.error('Button or registration form not found.');
    }
});

//close form
document.addEventListener('DOMContentLoaded', () => {
    const btnClose = document.querySelector('.close');
    const registration = document.querySelector('.registration');

    if (btnClose && registration) {
        btnClose.addEventListener('click', () => {
            registration.classList.remove('active-popup');
        });
    } else {
        console.error('Button or registration form not found.');
    }
});

// Prevent form from closing when the register button is clicked
document.addEventListener('DOMContentLoaded', () => {
    const registerButton = document.getElementById('btn-register');
    const registration = document.querySelector('.registration');

    if (registerButton && registration) {
        registerButton.addEventListener('click', (event) => {
            // Prevent the default form submission behavior
            event.preventDefault();

            // Remove the class that triggers the closing animation
            registration.classList.remove('active-popup');

            // Submit the form
            playSound();
            document.querySelector('form').submit();
        });
    } else {
        console.error('Register button or registration form not found.');
    }
});

function validateLname(input) {
    let letters = /^[A-Za-z\s]+$/;
    if(!input.value.match(letters)) {
        document.getElementById('lnameErr').innerHTML = 'Please input only letters';
        document.getElementById('btn-register').disabled = true;
    } else {
        document.getElementById('lnameErr').innerHTML = '';
        document.getElementById('btn-register').disabled = false;
    }
}

function validateFname(input) {
    let letters = /^[A-Za-z\s]+$/;
    if(!input.value.match(letters)) {
        document.getElementById('fnameErr').innerHTML = 'Please input only letters';
        document.getElementById('btn-register').disabled = true;
    } else {
        document.getElementById('fnameErr').innerHTML = '';
        document.getElementById('btn-register').disabled = false;
    }
}

function validateMname(input) {
    let letters = /^[A-Za-z\s]+$/;
    if(!input.value.match(letters)) {
        document.getElementById('mnameErr').innerHTML = 'Please input only letters';
        document.getElementById('btn-register').disabled = true;
    } else {
        document.getElementById('mnameErr').innerHTML = '';
        document.getElementById('btn-register').disabled = false;
    }
}

function validateNname(input) {
    let letters = /^[A-Za-z\s]+$/;
    if(!input.value.match(letters)) {
        document.getElementById('nnameErr2').innerHTML = 'Please input only letters';
        document.getElementById('btn-register').disabled = true;
    } else {
        document.getElementById('nnameErr2').innerHTML = '';
        document.getElementById('btn-register').disabled = false;
    }
}
function validateAge(input) {
    let numbers = /^[0-9]+$/;
    let inputValue = input.value;

    // Check if the input value contains only numbers
    if (!inputValue.match(numbers)) {
        document.getElementById('ageErr').innerHTML = 'Please input only numbers';
        document.getElementById('btn-register').disabled = true;
    } else {
        document.getElementById('ageErr').innerHTML = '';
        document.getElementById('btn-register').disabled = true;
    }

    // Limit the input to 2 digits
    if (inputValue.length > 1) {
        // Truncate the input value to 3 digits
        input.value = inputValue.slice(0, 1);
    }
}


document.getElementById('birth_date').addEventListener('input', function (birth_date) {
    let inputValue = birth_date.target.value;
    let numsOnly = inputValue.replace(/[^0-9]/g, ''); 
    let formattedValue = numsOnly.slice(0, 8); 

    if (formattedValue.length >= 4) {
        let yr = formattedValue.slice(0, 4);
        let month = formattedValue.slice(4, 6);
        let day = formattedValue.slice(6, 8);
        
        if (parseInt(month) > 12) {
            month = '12'; 
        }

        if (parseInt(day) > 31) {
            day = '31';
        }

        formattedValue = yr + '-' + month + '-' + day;
    }

    birth_date.target.value = formattedValue;
});

function validateNum(input) {
    let numbers = /^[0-9]+$/;
    let inputValue = input.value;

    // Check if the input value contains only numbers
    if (!inputValue.match(numbers)) {
        document.getElementById('phone-numErr').innerHTML = 'Please input only numbers';
        document.getElementById('btn-register').disabled = true;
    } else {
        document.getElementById('phone-numErr').innerHTML = '';
        document.getElementById('btn-register').disabled = true;
    }

    // Limit the input to 11 digits
    if (inputValue.length > 10) {
        // Truncate the input value to 11 digits
        input.value = inputValue.slice(0, 10);
    }
}

function validateReligion(input) {
    let letters = /^[A-Za-z\s]+$/;
    if(!input.value.match(letters)) {
        document.getElementById('religionErr2').innerHTML = 'Please input only letters';
        document.getElementById('btn-register').disabled = true;
    } else {
        document.getElementById('religionErr2').innerHTML = '';
        document.getElementById('btn-register').disabled = true;
    }
}

function validateHeight(input) {
    let numbers = /^[0-9]+$/;
    let inputValue = input.value;

    // Check if the input value contains only numbers
    if (!inputValue.match(numbers)) {
        document.getElementById('heightErr').innerHTML = 'Please input only numbers';
        document.getElementById('btn-register').disabled = true;
    } else {
        document.getElementById('heightErr').innerHTML = '';
        document.getElementById('btn-register').disabled = true;
    }

    // Limit the input to 3 digits
    if (inputValue.length > 2) {
        // Truncate the input value to 3 digits
        input.value = inputValue.slice(0, 2);
    }
}

function validateWeight(input) {
    let numbers = /^[0-9]+$/;
    let inputValue = input.value;

    // Check if the input value contains only numbers
    if (!inputValue.match(numbers)) {
        document.getElementById('weightErr2').innerHTML = 'Please input only numbers';
        document.getElementById('btn-register').disabled = true;
    } else {
        document.getElementById('weightErr2').innerHTML = '';
        document.getElementById('btn-register').disabled = true;
    }

    // Limit the input to 3 digits
    if (inputValue.length > 2) {
        // Truncate the input value to 3 digits
        input.value = inputValue.slice(0, 2);
    }
}  
//sound
let registerButton = document.getElementById('btn-register');

function playSound() {
    let audio = new Audio("switch-sound (1).mp3");
    audio.play();
}

registerButton.addEventListener('click', function(event) {
    // Play sound before form submission
    event.playSound();
    
    // Allow form submission to proceed
    setTimeout(function() {
        document.getElementById('btn-register').submit();
    }, 1000); // Adjust the delay time as needed
});

document.getElementById('success').innerHTML = "Registered Successfully!";
let audio = new Audio("switch-sound (1).mp3");
audio.play();


let closeButton = document.getElementById('close-btn');

function playSound() {
    let audio = new Audio("switch-sound (1).mp3");
    audio.play();
}

//close button sound effect
registerButton.addEventListener('click', function(event) {
    event.playSound();

    setTimeout(function() {
        document.getElementById('close-btn').submit()
    }, 1000);
});

//menu sound button
let menuButton = document.getElementById('menu-btn');

function playSound() {
    let audio = new Audio("switch-sound (1).mp3");
    audio.play();
}

// Add an event listener to the menu button to trigger the playSound function
menuButton.addEventListener('click', function() {
    playSound();
});

 