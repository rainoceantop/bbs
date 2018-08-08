
const userAvatar = document.querySelector('.user-avatar')
const listContent = document.querySelector('.list-content')

//判断用户是否已经登录,如果未登录跳转至登录页
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        let status = response.data
        if (!status.is_login) {
            window.location.href = 'login.html'
        } else {
            //已经登录，获取用户信息
            axios.get('../back/model/user/getUser.php?for=getUserById&id=' + status.id)
                .then(response => {
                    let userInfo = response.data
                    userAvatar.src = userInfo.avatar
                    listContent.innerHTML =
                        `
                <p>名称： ${userInfo.user}</p>
                <p>创建时间： ${userInfo.created_at}</p>
                    `
                })
                .catch(error => {
                    console.log(error)
                })
        }
    })
    .catch(error => {
        console.log(error)
    })

