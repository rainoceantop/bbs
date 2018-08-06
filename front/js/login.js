//判断是否已经登录，如果已经登录跳转至首页
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        if (response.data.is_login)
            window.location.href = 'home.html'
    })
    .catch(error => {
        console.log(error)
    })


const loginContent = document.querySelector('.login-content')
const loginBtn = document.querySelector('.login-button')
loginBtn.addEventListener('click', function () {
    const username = document.querySelector('.login-username')
    const password = document.querySelector('.login-password')

    axios.post('../back/handler/loginHandler.php', {
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
