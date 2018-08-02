
let loginBtn = document.querySelector('.login-button')
loginBtn.addEventListener('click', function () {
    let username = document.querySelector('.login-username')
    let password = document.querySelector('.login-password')
    axios.post('../back/loginHandler.php', {
        username: 'helloworld',
        password: '123456'
    })
        .then(response => {
            console.log(response.data)
        })
        .catch(error => {
            console.log(error)
        })
})
