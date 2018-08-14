//存储用户登录变量
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        window.is_login = response.data.is_login
        window.user = response.data.user
        window.user_id = response.data.id
        window.user_avatar = response.data.avatar
    })
    .catch(error => {
        console.log(error)
    })