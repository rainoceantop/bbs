//查看当前用户是否有权查看用户
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        //如果不是管理员则确认有没权限
        if (response.data.is_admin != '1') {
            let user_id = response.data.id
            axios.get('../back/handler/rightsHandler.php?check=canReadUsers&user_id=' + response.data.id)
                .then(response => {
                    if (!response.data) {
                        alert('抱歉，您无权查看用户')
                        window.location.href = 'home.html'
                    } else {
                        //查看当前用户是否有权删除用户
                        axios.get('../back/handler/rightsHandler.php?check=canDeleteUsers&user_id=' + response.data.id)
                            .then(response => {
                                if (response.data == 1)
                                    loadPage(true, user_id, false)
                                else
                                    loadPage(false, user_id, false)
                            })
                            .catch(error => console.log(error))
                    }
                })
                .catch(error => console.log(error))
        } else {
            loadPage(true, user_id, true)
        }
    })
    .catch(error => console.log(error))


function loadPage(can_delete, user_id, is_admin) {

    const showUsers = document.querySelector('.show-users')

    //获取所有用户信息
    axios.get('../back/model/user/getUser.php?for=getUsers&id=' + user_id)
        .then(response => {
            let users = response.data
            let html = ''
            if (users.length > 0) {
                for (let i in users) {
                    html +=
                        `
                    <div class="user-item">
                    <div class="avatar">
                        <img src="${users[i].avatar}">
                    </div>
                    <div class="info">
                        <p>
                            <span>id编号：${users[i].id}</span>
                            <span>名称：${users[i].name}</span>
                            <span>创建于： ${users[i].created_at}</span>
                        </p>
                        <p>
                            <span>最后登录： ${users[i].last_online}</span>
                            <span>用户组：${users[i].user_groups}</span>
                        </p>
                    </div>
                    <div class="setting">
                    `
                    if (is_admin) {
                        html += `<span class="admin-user-button" data-user_id=${users[i].id} data-user_name=${users[i].name}><i class="fas fa-key" title="升级管理员"></i>升级</span>`
                    }
                    html +=
                        `
                        <span class="delete-user-button" data-user_id=${users[i].id} data-user_name=${users[i].name}><i class="fas fa-ban" title="删除用户"></i>删除</span>
                    </div>
                    </div>
                    `
                }
            } else {
                html += '<p>暂无数据</p>'
            }

            showUsers.innerHTML = html
            console.log('can delete:' + can_delete)
            const deleteUserButton = showUsers.querySelectorAll('.delete-user-button')
            const adminUserButton = showUsers.querySelectorAll('.admin-user-button')
            //监听用户删除
            deleteUserButton.forEach(item => {
                item.addEventListener('click', function () {
                    if (can_delete) {
                        if (confirm(`确定删除id编号：${item.dataset.user_id}，名称为 "${item.dataset.user_name}" 的用户吗？`)) {
                            axios.get(`../back/model/user/delUser.php?for=deleteUser&id=${item.dataset.user_id}`)
                                .then(response => {
                                    alert(response.data)
                                    item.parentElement.parentElement.style.display = 'none'
                                })
                                .catch(error => console.log(error))
                        }
                    } else {
                        alert('你无权删除用户')
                    }
                })
            })
            //监听用户升级管理员
            adminUserButton.forEach(item => {
                item.addEventListener('click', function () {
                    if (confirm(`确定将id编号：${item.dataset.user_id}，名称为 "${item.dataset.user_name}" 的用户升级为管理员吗？`)) {
                        axios.get(`../back/model/user/updateUser.php?for=adminUser&id=${item.dataset.user_id}`)
                            .then(response => {
                                alert(response.data)
                                item.parentElement.parentElement.style.display = 'none'
                            })
                            .catch(error => console.log(error))
                    }
                })
            })
        })
        .catch(error => console.log(error))
}
