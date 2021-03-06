
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
        const menuBtn = document.querySelector('.menu-btn')
        const menu = document.querySelector('.menu')
        const menuBranding = document.querySelector('.menu-branding')
        const menuNav = document.querySelector('.menu-nav')
        const menuItems = document.querySelectorAll('.menu-item')

        menuBtn.addEventListener('click', toggleMenu)
        let showMenu = false
        function toggleMenu() {
            if (!showMenu) {
                menuBtn.classList.add('close')
                menu.classList.add('show')
                menuBranding.classList.add('show')
                menuNav.classList.add('show')
                menuItems.forEach(item => item.classList.add('show'))
                showMenu = true
            } else {
                menuBtn.classList.remove('close')
                menu.classList.remove('show')
                menuBranding.classList.remove('show')
                menuNav.classList.remove('show')
                menuItems.forEach(item => item.classList.remove('show'))
                showMenu = false
            }
        }

        //处理登录
        const logAction = document.querySelector('.menu .menu-nav .log-action')
        const user = document.querySelector('.menu .menu-nav .user')
        const branding = document.querySelector('.menu .menu-branding')
        setTimeout(checkLogin, 500)
        function checkLogin() {

            if (window.is_login) {
                logAction.classList.remove('log-in')
                logAction.classList.add('log-out')
                user.classList.remove('user-out')
                user.classList.add('user-in')
                logAction.innerHTML = `<a href='#!'>注销</a>`
                user.innerHTML = `<a href='my.html'>个人资料</a>`
                branding.innerHTML =
                    `

                    <img src="${window.user_avatar}" alt="" width="100px" height="100px" />
                    <form id="change-avatar-form" action="../back/model/user/updateUser.php?for=avatar&id=${window.user_id}" method="POST" enctype="multipart/form-data">
                        <input style="display:none" type="file" name="avatar" class="change-avatar">
                    </form>
                    <p style="color:white;font-weight:bold;">${window.user}</p>
                `
                //监听鼠标经过头像
                const avatar = branding.querySelector('img')
                const changeAvatar = branding.querySelector('.change-avatar')
                const changeAvatarForm = branding.querySelector('#change-avatar-form')
                avatar.addEventListener('click', function () {
                    changeAvatar.click()
                })
                changeAvatar.addEventListener('change', function () {
                    changeAvatarForm.submit()
                })
                logAction.addEventListener('click', function () {
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
        }

        branding

    })
    .catch(error => {
        console.log(error)
    })

