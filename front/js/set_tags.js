let can_modify = false

//获取用户是否有权修改标签
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        //如果不是管理员则确认有没权限
        if (response.data.is_admin != '1') {
            axios.get('../back/handler/rightsHandler.php?check=canModifyFT&user_id=' + response.data.id)
                .then(response => {
                    if (response.data == 1) {
                        can_modify = true
                    } else {
                        can_modify = false
                    }
                })
                .catch(error => console.log(error))
        } else {
            can_modify = true
        }
    })
    .catch(error => console.log(error))



//获取板块，标签组，标签的dom节点
const forumArea = document.querySelector('.forums-area')
const tagGroupsArea = document.querySelector('.tag-groups-area')
const tagsArea = document.querySelector('.tags-area')


//在板块域展示板块
axios.get('../back/model/forum/getForum.php?for=getForumName')
    .then(response => {
        let html = '板块：'
        for (let i in response.data)
            html += `
            <div class="forum-item">
            <a class='forum-display-button' href='#!'>${response.data[i]}</a> 
            <span class="forum-delete-button" data-forumid=${i}>X</span>
            </div>
            `
        html += `<a class='forum-add-button' href='#!'>添加板块</a>`
        forumArea.innerHTML = html

        //监听添加forum按钮
        const forumAddButton = forumArea.querySelector('.forum-add-button')
        const forumsSetArea = document.querySelector('.forums-section .forums-set-area')
        const forumInputText = forumsSetArea.firstElementChild
        const forumInputButton = forumsSetArea.lastElementChild
        let addShow = false
        forumAddButton.addEventListener('click', function (e) {
            console.log('==========')
            e.preventDefault()
            if (!addShow) {
                forumsSetArea.style.display = 'inline';
                addShow = true
                //监听foruma添加按钮点击

                forumInputButton.addEventListener('click', function () {
                    let params = {
                        forum_name: forumInputText.value
                    }
                    axios.post('../back/model/forum/addForum.php', params)
                        .then(response => {
                            if (response.data == 'SUCCESS')
                                location.reload()
                            else
                                console.log(response.data)
                        })
                        .catch(error => {
                            console.log(error)
                        })
                })
            } else {
                forumsSetArea.style.display = 'none'
                addShow = false
            }

        })


        let tagGroups, tags = ''
        //获取标签组和标签
        axios.get('../back/model/tag/getTag.php?for=getTagGroups')
            .then(response => {
                tagGroups = response.data
            })
            .catch(error => {
                console.log(error)
            })

        axios.get('../back/model/tag/getTag.php?for=getTags')
            .then(response => {
                tags = response.data
            })
            .catch(error => {
                console.log(error)
            })
        //板块删除按钮
        const forumItems = document.querySelectorAll('.forum-item')
        forumItems.forEach(item => {
            console.log(can_modify)
            if (can_modify) {
                item.addEventListener('mouseover', function () {
                    item.lastElementChild.style.visibility = 'visible'
                })
                item.addEventListener('mouseout', function () {
                    item.lastElementChild.style.visibility = 'hidden'
                })
            }
            //监听删除板块按钮点击事件
            const forumDeleteButton = item.lastElementChild
            forumDeleteButton.addEventListener('click', function () {
                axios.get('../back/model/forum/delForum.php?for=delForumById&id=' + forumDeleteButton.dataset.forumid)
                    .then(response => {
                        if (response.data == 'SUCCESS') {
                            item.style.display = 'none';
                        } else {
                            console.log(response.data)
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    })
            })



            //点击forum板块，显示板块的tag-group,隐藏标签
            item.addEventListener('click', function () {
                let html = '标签组'
                let forum_id = item.lastElementChild.dataset.forumid
                for (let i in tagGroups)
                    if (tagGroups[i].forum_id == forum_id) {
                        html += `
                <div class="tag-groups-item">
                <a class='tag-groups-display-button' href='#!'>${tagGroups[i].tag_group_name}</a> 
                <span class="tag-groups-delete-button" data-taggroupid=${tagGroups[i].tag_group_id}>X</span>
                </div>
                `

                    }
                html += `<a class='tag-group-add-button' href='#!'>添加标签组</a>`
                tagGroupsArea.innerHTML = html
                tagsArea.innerHTML = ''


                //监听添加i标签组按钮
                const tagGroupAddButton = tagGroupsArea.querySelector('.tag-group-add-button')
                const tagGroupsSetArea = document.querySelector('.tag-groups-section .tag-groups-set-area')
                const tagGroupsInputText = tagGroupsSetArea.firstElementChild
                const tagGroupsInputButton = tagGroupsSetArea.lastElementChild
                let addShow_sec = false

                tagGroupAddButton.addEventListener('click', function (e) {
                    e.preventDefault()
                    if (!addShow_sec) {
                        tagGroupsSetArea.style.display = 'inline';
                        addShow_sec = true
                        //监听标签组添加按钮点击

                        tagGroupsInputButton.addEventListener('click', function () {

                            console.log(tagGroupsInputText.value)
                            let params = {
                                forum_id: forum_id,
                                tag_group_name: tagGroupsInputText.value
                            }
                            axios.post('../back/model/tag/addTag.php?for=tagGroups', params)
                                .then(response => {
                                    if (response.data == 'SUCCESS')
                                        location.reload()
                                    else
                                        console.log(response.data)
                                })
                                .catch(error => {
                                    console.log(error)
                                })
                        })
                    } else {
                        tagGroupsSetArea.style.display = 'none'
                        addShow_sec = false
                    }

                })


                const tagGroupItems = document.querySelectorAll('.tag-groups-item')
                tagGroupItems.forEach(item => {
                    if (can_modify) {
                        item.addEventListener('mouseover', function () {
                            item.lastElementChild.style.visibility = 'visible'
                        })
                        item.addEventListener('mouseout', function () {
                            item.lastElementChild.style.visibility = 'hidden'
                        })
                    }
                    //监听标签组删除按钮点击事件
                    const tagGroupDeleteButton = item.lastElementChild
                    tagGroupDeleteButton.addEventListener('click', function () {
                        axios.get('../back/model/tag/delTag.php?for=delTagGroupById&id=' + tagGroupDeleteButton.dataset.taggroupid)
                            .then(response => {
                                if (response.data == 'SUCCESS') {
                                    item.style.display = 'none';
                                } else {
                                    console.log(response.data)
                                }
                            })
                            .catch(error => {
                                console.log(error)
                            })
                    })




                    //点击标签组，显示标签
                    item.addEventListener('click', function () {
                        let html = '标签'
                        let tag_group_id = item.lastElementChild.dataset.taggroupid
                        for (let i in tags)
                            if (tags[i].tag_group_id == item.lastElementChild.dataset.taggroupid) {
                                html += `
                <div class="tags-item">
                <a class='tags-display-button' href='#!'>${tags[i].tag_name}</a> 
                <span class="tags-delete-button" data-tagid=${tags[i].tag_id}>X</span>
                </div>
                `

                            }
                        html += `<a class='tag-add-button' href='#!'>添加标签组</a>`
                        tagsArea.innerHTML = html



                        //监听添加i标签组按钮
                        const tagAddButton = tagsArea.querySelector('.tag-add-button')
                        const tagsSetArea = document.querySelector('.tags-section .tags-set-area')
                        const tagsInputText = tagsSetArea.firstElementChild
                        const tagsInputButton = tagsSetArea.lastElementChild
                        let addShow_thr = false

                        tagAddButton.addEventListener('click', function (e) {
                            e.preventDefault()
                            if (!addShow_thr) {
                                tagsSetArea.style.display = 'inline';
                                addShow_thr = true
                                //监听标签组添加按钮点击

                                tagsInputButton.addEventListener('click', function () {

                                    console.log("forum_id是" + forum_id)
                                    let params = {
                                        forum_id: forum_id,
                                        tag_group_id: tag_group_id,
                                        tag_name: tagsInputText.value
                                    }
                                    axios.post('../back/model/tag/addTag.php?for=tags', params)
                                        .then(response => {
                                            if (response.data == 'SUCCESS')
                                                location.reload()
                                            else
                                                console.log(response.data)
                                        })
                                        .catch(error => {
                                            console.log(error)
                                        })
                                })
                            } else {
                                tagsSetArea.style.display = 'none'
                                addShow_thr = false
                            }

                        })



                        const tagsItems = document.querySelectorAll('.tags-item')
                        tagsItems.forEach(item => {
                            if (can_modify) {
                                item.addEventListener('mouseover', function () {
                                    item.lastElementChild.style.visibility = 'visible'
                                })
                                item.addEventListener('mouseout', function () {
                                    item.lastElementChild.style.visibility = 'hidden'
                                })
                            }
                            //监听标签组删除按钮点击事件
                            const tagDeleteButton = item.lastElementChild
                            tagDeleteButton.addEventListener('click', function () {
                                axios.get('../back/model/tag/delTag.php?for=delTagById&id=' + tagDeleteButton.dataset.tagid)
                                    .then(response => {
                                        if (response.data == 'SUCCESS') {
                                            item.style.display = 'none';
                                        } else {
                                            console.log(response.data)
                                        }
                                    })
                                    .catch(error => {
                                        console.log(error)
                                    })
                            })

                        })
                        console.log(tags)
                    })

                })
                console.log(tagGroups)
            })
        })

    })
