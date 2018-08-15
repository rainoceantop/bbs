const groupList = document.querySelector('.user-group-list')
const userList = document.querySelector('.user-list')
const action = document.querySelector('.action')


//获取用户组
axios.get('../back/model/user/getUser.php?for=getUserGroups')
    .then(response => {
        let groups = response.data
        console.log(groups)
        //获取已经加入用户组的用户
        axios.get('../back/model/user/getUser.php?for=getGroupsUsers')
            .then(response => {
                let user_groups = response.data
                let html =
                    `
                <table>
                    <thead>
                        <tr>
                            <th style="width:10vw">id</th>
                            <th style="width:25vw">名称</th>
                            <th style="width:40vw">权限</th>
                            <th style="width:25vw">操作</th>
                        </tr>
                    </thead>
                    <tbody style="line-height: 2rem;">
                `
                if (groups.length > 0) {
                    for (let i in groups) {
                        html +=
                            `
                    <tr>
                        <td>${groups[i].user_group_id}</td>
                        <td>${groups[i].user_group_name}</td>
                        <td>${groups[i].rights}</td>
                        <td><span data-gid="${groups[i].user_group_id}" data-gname="${groups[i].user_group_name}" class="delete-group-button">删除组</span> | <span data-gid="${groups[i].user_group_id}" class="show-group-users">查看组员</span></td>
                    </tr>
                    `
                    }
                } else {
                    html +=
                        `
                    <tr><td colspan="4">暂无用户组</td></tr>
                    `
                }

                html +=
                    `
                    </tbody>
                </table>
                `
                groupList.innerHTML = html
                //监听显示用户组点击事件
                const showGroupUsers = document.querySelectorAll('.show-group-users')
                showGroupUsers.forEach(item => {
                    item.addEventListener('click', function () {
                        action.innerHTML = ''
                        let gid = item.dataset.gid
                        let html = ''
                        let users = Array()
                        for (let j in user_groups) {
                            if (JSON.parse(user_groups[j].user_groups).indexOf(gid) > -1)
                                users.push(user_groups[j].name)
                        }
                        html += users
                        html +=
                            `
                            <hr>
                        <span class="add-to-group">添加组员</span>
                        <span class="rm-from-group">删除组员</span>
                        `
                        userList.innerHTML = html

                        //用户组的用户增加或删除事件
                        const addToGroup = userList.querySelector('.add-to-group')
                        const rmFromGroup = userList.querySelector('.rm-from-group')
                        addToGroup.addEventListener('click', function () {
                            let gid = item.dataset.gid
                            axios.get('../back/model/user/getUser.php?for=getUsersWithout&id=' + gid)
                                .then(response => {
                                    let unAddedUsers = response.data
                                    let html =
                                        `
                                    <form method="post" action="../back/model/user/updateUser.php?for=updateUserGroups&action=add">
                                    <input type="hidden" value="${gid}" name="group_id" >
                                    `
                                    for (let i in unAddedUsers) {
                                        html +=
                                            `
                                            <input type="checkbox" name="users[]" value="${unAddedUsers[i].user_id}"}>${unAddedUsers[i].user_name}
                                        `
                                    }
                                    html +=
                                        `
                                        <hr>
                                    <input type="submit" value="添加">
                                    </form>
                                    `
                                    action.innerHTML = html
                                })
                                .catch(error => console.log(error))
                        })
                        rmFromGroup.addEventListener('click', function () {
                            let gid = item.dataset.gid
                            axios.get('../back/model/user/getUser.php?for=getUsersWithin&id=' + gid)
                                .then(response => {
                                    let addedUsers = response.data
                                    console.log(addedUsers)
                                    let html =
                                        `
                                    <form method="post" action="../back/model/user/updateUser.php?for=updateUserGroups&action=remove">
                                    <input type="hidden" value="${gid}" name="group_id" >
                                    `
                                    for (let i in addedUsers) {
                                        html +=
                                            `
                                            <input type="checkbox" name="users[]" value="${addedUsers[i].user_id}"}>${addedUsers[i].user_name}
                                        `
                                    }
                                    html +=
                                        `
                                        <hr>
                                    <input type="submit" value="删除">
                                    </form>
                                    `
                                    action.innerHTML = html
                                })
                                .catch(error => console.log(error))
                        })
                    })
                })

                //监听删除组点击事件
                const deleteGroupButton = document.querySelectorAll('.delete-group-button')
                deleteGroupButton.forEach(item => {
                    item.addEventListener('click', function () {
                        if (confirm(`确定删除id编号为：${item.dataset.gid}，名称为 "${item.dataset.gname}" 的用户组吗？`)) {
                            axios.get('../back/model/user/delUser.php?for=deleteGroup&id=' + item.dataset.gid)
                                .then(response => {
                                    alert(response.data)
                                    item.parentElement.parentElement.style.display = 'none'
                                })
                                .catch(error => conso.log(error))
                        }

                    })
                })
            })
            .catch(error => console.log(error))
    })
    .catch(error => console.log(error))

