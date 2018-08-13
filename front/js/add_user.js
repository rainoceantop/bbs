const formContent = document.querySelector('.list-content')

//获取用户组标签
axios.get('../back/model/user/getUser.php?for=getUserGroups')
    .then(response => {
        let data = response.data
        let html = ''
        html +=
            `
        <form action="../back/model/user/addUser.php?for=addUser" method="POST">
            <input type="text" name="username" placeholder="帐号">
            <input type="password" name="password" placeholder="密码">
            <input type="name" name="user" placeholder="用户名">
        `
        for (let i in data) {
            html += `<input type="checkbox" name="user_groups[]" value="${data[i].user_group_id}" title=${data[i].rights}>${data[i].user_group_name}`
        }
        html +=
            `
            <input type="submit" value="添加">
        </form>
        `

        formContent.innerHTML = html
    })
    .catch(error => console.log(error))