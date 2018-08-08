
const header = document.querySelector('header')
axios.get('_part/header.html')
    .then(response => {
        header.innerHTML = response.data

        //获取forums
        axios.get('../back/model/forum/getForum.php?for=getForumName')
            .then(response => {
                const navbar = document.querySelector('.forum-nav .nav')
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


        //用户profile按钮
        const menuBtn = document.querySelector('.profile-btn')
        const profile = document.querySelector('.profile')

        menuBtn.addEventListener('click', toggleMenu)
        let showMenu = false
        function toggleMenu() {
            if (!showMenu) {
                menuBtn.classList.add('close')
                profile.classList.add('show')
                showMenu = true
            } else {
                menuBtn.classList.remove('close')
                profile.classList.remove('show')
                showMenu = false
            }
        }

        //处理登录
        const logAction = document.querySelector('.profile .profile-nav .log-action')
        const user = document.querySelector('.profile .profile-nav .user')

        axios.get('../back/handler/loginHandler.php?log=2')
            .then(response => {
                console.log(response)
                if (response.data.is_login) {
                    logAction.classList.remove('log-in')
                    logAction.classList.add('log-out')
                    user.classList.remove('user-out')
                    user.classList.add('user-in')
                    logAction.innerHTML = `<a href='#!'>注销</a>`
                    user.innerHTML = `<a href='my.html'>${response.data.user}</a>`
                    logAction.addEventListener('click', function () {
                        console.log('----')
                        axios.get('../back/handler/loginHandler.php?log=1')
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
                if (error.response) {
                    console.log(error.response.data);
                    console.log(error.response.status);
                    console.log(error.response.headers);
                }

            })

    })
    .catch(error => {
        console.log(error)
    })

