const header = document.querySelector('header')
axios.get('_part/header.html')
    .then(response => {
        header.innerHTML = response.data
    })
    .catch(error => {
        console.log(error)
    })




const loginContent = document.querySelector('.login-content')
const loginBtn = document.querySelector('.login-button')
loginBtn.addEventListener('click', function () {
    const username = document.querySelector('.login-username')
    const password = document.querySelector('.login-password')

    axios.post('../back/loginHandler.php', {
        username: username.value,
        password: password.value
    })
        .then(response => {
            if (response.data == 'SUCCESS') {
                loginContent.innerHTML = '登录成功'
                setTimeout(function () {
                    window.location.href = 'home.html'
                }, 200)
            } else {
                console.log('失败')
            }
            console.log(response)
        })
        .catch(error => {
            console.log(error)
        })
})
