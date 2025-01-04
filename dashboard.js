const sideMenu = document.querySelector('aside');
const menuBtn = document.querySelector('#menu_bar');
const closeBtn = document.querySelector('#close_btn');

const themeToggler = document.querySelector('.theme-toggler'); // Light Dark Themew
const square = document.querySelector('.square');
const btnAct = document.querySelector('#btn-activate');


menuBtn.addEventListener('click',()=>{
       sideMenu.style.display = "block"
})
closeBtn.addEventListener('click',()=>{
    sideMenu.style.display = "none"
})

themeToggler.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme-variables');
    themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
    themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');
});



const btnSideList = document.querySelectorAll('.sideoptions');
//thıs dorsnt work again
btnSideList.forEach(btnSide => {
    btnSide.addEventListener('click', () => {
        document.querySelector('.special')?.classList.remove('special');
        btnSide.classList.add('special');
    })
})





// btnAct.addEventListener('click', () => {
//     square.classList.toggle('hidden1')
// })



function changeClass(){
    var element = document.querySelector('#myDiv');
    element.classList.replace('oldClass','newClass');
}





/*  Products   */






// Enter Expense
function popupFn() {
    document.getElementById("overlay").style.display = "block";
    document.getElementById("popupDialog").style.display = "block";
}

function closeFn() {
    document.getElementById("overlay").style.display = "none";
    document.getElementById("popupDialog").style.display = "none";
}

// Enter New Earnings
function popupFn2() {
    document.getElementById("overlay2").style.display = "block";
    document.getElementById("popupDialog2").style.display = "block";
}

function closeFn2() {
    document.getElementById("overlay2").style.display = "none";
    document.getElementById("popupDialog2").style.display = "none";
}

// Overlay'e tıklandığında da modalları kapat
document.getElementById("overlay").addEventListener("click", closeFn);
document.getElementById("overlay2").addEventListener("click", closeFn2);

//logout
function confirmLogout(event) {
    event.preventDefault(); // Prevent default action
    if (confirm("Are you sure you want to log out?")) {
      window.location.href = "logout.php"; // Redirect to logout.php
    }
  };  

//expense
document.getElementById('expenseForm').addEventListener('submit', function (event) {
    //console.log('Submitting form...'); // added this
    event.preventDefault(); // Prevent form submission

    // Get form data
    const name = document.getElementById('expense-name').value;
    const price = document.getElementById('expense-price').value;
    const theme = document.getElementById('theme').value;
    const details = document.getElementById('details').value;

    // Send data to the backend
    fetch('submit_expense.php', {
        method: 'POST',
        headers: {// added this
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'name': name,
            'price': price,
            'theme': theme,
            'details': details
        })
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Display the server response (success or error message)
        document.getElementById('expenseForm').reset(); // Reset form
    })
    .catch(error => console.error('Error:', error));
});


//earnings
document.getElementById('earningsForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent form submission

    // Get form data
    const amount = document.getElementById('earnings-amount').value;

    // Send data to the backend
    fetch('submit_earnings.php', {
        method: 'POST',
        headers: {// added this
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'amount': amount
        })
    })
    .then(response => response.text())
    .then(data => {
        alert(data); // Display the server response (success or error message)
        document.getElementById('earningsForm').reset(); // Reset form
    })
    .catch(error => console.error('Error:', error));
});
