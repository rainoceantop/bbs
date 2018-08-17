//判断是否已经登录，如果已经登录跳转至首页
!function () {
    if (window.is_login)
        window.location.href = 'home.html'
}()


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
                alert('登录失败， 帐号或密码错误')
            }
            console.log(response)
        })
        .catch(error => {
            console.log(error)
        })
})
