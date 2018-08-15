
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        let status = response.data
        console.log(status)
        if (!status.is_login) {
            window.location.href = 'login.html'
        } else {
            //获取profile列表卡
            const profileList = document.querySelector('.profile-list')
            axios.get('_part/profile_list.html')
                .then(response => {
                    profileList.innerHTML = response.data
                    //获取用户头像
                    axios.get('../back/model/user/getUser.php?for=getUserById&id=' + status.id)
                        .then(response => {
                            const profileAvatar = document.querySelector('.profile-avatar')
                            profileAvatar.innerHTML += `<img class="user-avatar" src="${response.data.avatar}">`
                            profileAvatar.innerHTML += `<p>${response.data.user}</p>`
                        })
                        .catch(error => {
                            console.log(error)
                        })

                    //根据权限显示操作链接
                    const addUserPage = profileList.querySelector('.add-user-page')
                    const showUserPage = profileList.querySelector('.show-user-page')
                    const setTagsPage = profileList.querySelector('.set-tags-page')
                    const addUserGroupPage = profileList.querySelector('.add-user-group-page')
                    const showUserGroupPage = profileList.querySelector('.show-user-group-page')
                    if (!parseInt(status.is_admin)) {
                        //判断用户是否可添加用户（组）
                        axios.get('../back/handler/rightsHandler.php?check=canAddUsers&user_id=' + status.id)
                            .then(response => {
                                if (response.data) {
                                    addUserPage.style.display = 'block'
                                    addUserGroupPage.style.display = 'block'
                                }
                            })
                            .catch(error => console.log(error))
                        //判断用户是否可查看用户（组）
                        axios.get('../back/handler/rightsHandler.php?check=canReadUsers&user_id=' + status.id)
                            .then(response => {
                                if (response.data) {
                                    showUserPage.style.display = 'block'
                                    showUserGroupPage.style.display = 'block'
                                }
                            })
                            .catch(error => console.log(error))
                        //判断用户是否可操作板块标签
                        axios.get('../back/handler/rightsHandler.php?check=canModifyFT&user_id=' + status.id)
                            .then(response => {
                                if (response.data) {
                                    setTagsPage.style.display = 'block'
                                }
                            })
                            .catch(error => console.log(error))
                    } else {
                        addUserPage.style.display = 'block'
                        showUserPage.style.display = 'block'
                        addUserGroupPage.style.display = 'block'
                        showUserGroupPage.style.display = 'block'
                        setTagsPage.style.display = 'block'
                    }
                })
                .catch(error => {
                    console.log(error)
                })
        }
    })
    .catch(error => {
        console.log(error)
    })






