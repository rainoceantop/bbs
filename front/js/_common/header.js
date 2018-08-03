
const header = document.querySelector('header')
axios.get('_part/header.html')
    .then(response => {
        header.innerHTML = response.data
        const logAction = document.querySelector('.nav .log-in')
        const user = document.querySelector('.nav .user')
        axios.get('../back/loginHandler.php?log=2')
            .then(response => {
                console.log(response)
                if (response.data.is_login) {
                    logAction.classList.remove('log-in')
                    logAction.classList.add('log-out')
                    user.classList.remove('user-out')
                    user.classList.add('user-in')
                    logAction.innerHTML = `注销`
                    user.innerHTML = `${response.data.user}`
                    logAction.addEventListener('click', function () {
                        axios.get('../back/loginHandler.php?log=1')
                            .then(response => {
                                console.log(response)
                                logAction.classList.remove('log-out')
                                logAction.classList.add('log-in')
                                user.classList.remove('user-in')
                                user.classList.add('user-out')
                                logAction.innerHTML = `<a href="login.html">登录</a>`
                            })
                    })
                }
            })
            .catch(error => {
                console.log(error)
            })
    })
    .catch(error => {
        console.log(error)
    })
