
const header = document.querySelector('header')
axios.get('_part/header.html')
    .then(response => {
        header.innerHTML = response.data
        const logAction = document.querySelector('.nav .log-in')
        const user = document.querySelector('.nav .user')
        //获取forums
        axios.get('../back/model/forum/getForum.php?for=getForumName')
            .then(response => {
                const navbar = document.querySelector('.nav')
                for (let i in response.data) {
                    navbar.innerHTML +=
                        `
                    <li class="option">
                        <a href="forum.html?forum=${i}">${response.data[i]}</a>
                    </li>
                        `
                }
            })
            .catch(error => {
                console.log(error)
            })

        //处理登录
        // axios.get('../back/handler/loginHandler.php?log=2')
        //     .then(response => {
        //         console.log(response)
        //         if (response.data.is_login) {
        //             logAction.classList.remove('log-in')
        //             logAction.classList.add('log-out')
        //             user.classList.remove('user-out')
        //             user.classList.add('user-in')
        //             logAction.innerHTML = `<a href='#!'>注销</a>`
        //             user.innerHTML = `<a href='#!'>${response.data.user}</a>`
        //             logAction.addEventListener('click', function () {
        //                 axios.get('../back/handler/loginHandler.php?log=1')
        //                     .then(response => {
        //                         console.log(response)
        //                         logAction.classList.remove('log-out')
        //                         logAction.classList.add('log-in')
        //                         user.classList.remove('user-in')
        //                         user.classList.add('user-out')
        //                         logAction.innerHTML = `<a href="login.html">登录</a>`
        //                     })
        //             })
        //         }
        //     })
        //     .catch(error => {
        //         console.log(error)
        //     })
    })
    .catch(error => {
        console.log(error)
    })
