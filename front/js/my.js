const listContent = document.querySelector('.list-content')


setTimeout(getListContent, 500)
function getListContent() {
    if (!window.is_login) {
        window.location.href = 'login.html'
    } else {
        //已经登录，获取用户帖子信息
        axios.get('../back/model/user/getUser.php?for=getUserById&id=' + window.user_id)
            .then(response => {
                console.log(response.data)
                let userInfo = response.data
                let html = `
                    <p><span>名称：</span>${userInfo.user}</p>
                    <p><span>创建时间：</span>${userInfo.created_at}</p>
                    <p><span>最后登录：</span>${userInfo.last_online}</p>
                    `
                listContent.innerHTML = html
            })
            .catch(error => {
                console.log(error)
            })
    }
}