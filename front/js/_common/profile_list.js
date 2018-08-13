
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        let status = response.data
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
                })
                .catch(error => {
                    console.log(error)
                })
        }
    })
    .catch(error => {
        console.log(error)
    })






