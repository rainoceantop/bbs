
const userAvatar = document.querySelector('.user-avatar')
const listContent = document.querySelector('.list-content')

//判断用户是否已经登录,如果未登录跳转至登录页
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        let status = response.data
        if (!status.is_login) {
            window.location.href = 'login.html'
        } else {
            //已经登录，获取用户帖子信息
            axios.get('../back/model/thread/getThread.php?for=getUserThreads&id=' + status.id)
                .then(response => {
                    let userInfo = response.data[0]
                    let html = ''
                    for (let i in userInfo) {
                        html += `<div><a href="detail.html?id=${userInfo[i].thread_id}">${userInfo[i].thread_title}</a></div>`
                    }
                    listContent.innerHTML = html
                })
                .catch(error => {
                    console.log(error)
                })
        }
    })
    .catch(error => {
        console.log(error)
    })

