const groupList = document.querySelector('.user-group-list')
const userList = document.querySelector('.user-list')
const action = document.querySelector('.action')


//获取用户组
axios.get('../back/model/user/getUser.php?for=getUserGroups')
    .then(response => {
        let groups = response.data

        //获取已经加入用户组的用户
        axios.get('../back/model/user/getUser.php?for=getGroupsUsers')
            .then(response => {
                let user_groups = response.data
                for (let i in groups) {
                    groupList.innerHTML += `<p><a href="#!" data-gid=${groups[i].user_group_id} class="show-group-users">${groups[i].user_group_name}</a></p>`
                }
                //监听用户组点击事件
                const showGroupUsers = document.querySelectorAll('.show-group-users')
                showGroupUsers.forEach(item => {
                    item.addEventListener('click', function () {
                        action.innerHTML = ''
                        let gid = item.dataset.gid
                        let html = ''
                        for (let j in user_groups) {
                            if (JSON.parse(user_groups[j].user_groups).indexOf(gid) > -1)
                                html += `<p>${user_groups[j].name}</p>`
                        }
                        html += `
                        <p><a href="#!" class="add-to-group">添加</a></p>
                        <p><a href="#!" class="rm-from-group">删除</a></p>
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
                                    <input type="submit" value="修改">
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
                                    <input type="submit" value="修改">
                                    </form>
                                    `
                                    action.innerHTML = html
                                })
                                .catch(error => console.log(error))
                        })
                    })
                })
            })
            .catch(error => console.log(error))
    })
    .catch(error => console.log(error))

