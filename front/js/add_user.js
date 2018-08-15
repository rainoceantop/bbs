//查看当前用户是否有权添加用户
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        //如果不是管理员则确认有没权限
        if (!response.data.is_admin == '1') {
            axios.get('../back/handler/rightsHandler.php?check=canAddUsers&user_id=' + response.data.id)
                .then(response => {
                    if (!response.data) {
                        alert('抱歉，您无权添加用户')
                        window.location.href = 'home.html'
                    }
                })
                .catch(error => console.log(error))
        }
    })
    .catch(error => console.log(error))


const formContent = document.querySelector('.add-user-form')

//获取用户组标签
axios.get('../back/model/user/getUser.php?for=getUserGroups')
    .then(response => {
        let data = response.data
        let html = ''
        html +=
            `
        <form action="../back/model/user/addUser.php?for=addUser" method="POST">
            <input type="text" name="username" placeholder="帐号" required>
            <input type="password" name="password" placeholder="密码" required>
            <input type="name" name="user" placeholder="用户名" required>
        `
        for (let i in data) {
            html += `<input type="checkbox" name="user_groups[]" value="${data[i].user_group_id}" title=${data[i].rights}>${data[i].user_group_name}`
        }
        html +=
            `
            <button type="submit">添加</button>
        </form>
        `

        formContent.innerHTML = html
    })
    .catch(error => console.log(error))