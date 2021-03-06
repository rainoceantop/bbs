
//获取所有标签组和标签
let tag_groups, tags = ''

//获取标签组
axios.get('../back/model/tag/getTag.php?for=getTagGroups')
    .then(response => {
        tag_groups = response.data
        console.log(tag_groups)
    })
    .catch(error => {
        console.log(error)
    })
//获取标签
axios.get('../back/model/tag/getTag.php?for=getTags')
    .then(response => {
        tags = response.data
    })
    .catch(error => {
        console.log(error)
    })




//查看当前用户是否有权发布文章
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        //如果不是管理员则确认有没权限
        if (response.data.is_admin != '1') {
            axios.get('../back/handler/rightsHandler.php?check=canPostThread&user_id=' + response.data.id)
                .then(response => {
                    console.log(response.data)
                    if (response.data != 1) {
                        alert('抱歉，您无权发表文章')
                        window.location.href = 'home.html'
                    }
                })
                .catch(error => console.log(error))
        }

    })
    .catch(error => console.log(error))



const titleField = document.querySelector('.title-field')
const forumField = document.querySelector('#forums')
const tagField = document.querySelector('.tags-area')
const bodyField = document.querySelector('.body-field')
const showContent = document.querySelector('.show-markdown-content-area')
const createButton = document.querySelector('.create-button')

//获取板块名称
axios.get('../back/model/forum/getForum.php?for=getForumName')
    .then(response => {
        console.log(response.data)
        for (let forum_id in response.data) {
            forumField.innerHTML += `<option value="${forum_id}">${response.data[forum_id]}</option>`
        }
        //初始化forum板块，设置默认
        console.log(forumField.value)
        let html = ''
        for (let i in tag_groups) {
            if (tag_groups[i].forum_id == forumField.value) {
                html += `<span>${tag_groups[i].tag_group_name}</span>`
                html += `<select class="tags" name="tags[]">`
                for (let j in tags) {
                    if (tags[j].tag_group_id == tag_groups[i].tag_group_id) {
                        html += `<option value="${tags[j].tag_id}">${tags[j].tag_name}</option>`
                    }
                }
                html += `</select>`
            }
        }
        tagField.innerHTML = html
    })
    .catch(error => {
        console.log(error)
    })



//监听文本输入域
let converter = new showdown.Converter()
bodyField.addEventListener('input', showMarkdownStyle)

//监听thread发表按钮
createButton.addEventListener('click', postThread)


//发表文章
function postThread(e) {
    e.preventDefault()
    let title = titleField.value
    let forum = forumField.value

    title = title.replace(/(^\s*)|(\s*$)/g, '')
    forum = forum.replace(/(^\s*)|(\s*$)/g, '')

    if (title.length > 3 && forum.length > 3) {
        //获取标签
        let tagsArr = document.querySelectorAll('.tags')
        let tags = []
        tagsArr.forEach(item => {
            tags.push(item.value)
        })
        //获取用户登录信息
        if (window.is_login) {
            let html = converter.makeHtml(bodyField.value)
            let params = {
                thread_title: titleField.value,
                forum_id: forumField.value,
                thread_body: html,
                thread_head: window.user,
                user_id: window.user_id,
                tags: tags
            }
            axios.post('../back/model/thread/addThread.php', params)
                .then(response => {
                    window.location.href = `detail.html?id=${response.data}`
                })
                .catch(error => {
                    console.log(error)
                })

        } else {
            window.location.href = 'login.html'
        }
    } else {
        alert('请至少输入三个以上字符')
    }
}

//显示markdown转换后的样式
function showMarkdownStyle() {
    let html = converter.makeHtml(this.value)
    showContent.innerHTML = html
}

!function () {
    //选择forum板块，显示标签组
    forumField.addEventListener('change', function () {
        let html = ''
        for (let i in tag_groups) {
            if (tag_groups[i].forum_id == this.value) {
                html += `<span>${tag_groups[i].tag_group_name}</span>`
                html += `<select class="tags" name="tags[]">`
                for (let j in tags) {
                    if (tags[j].tag_group_id == tag_groups[i].tag_group_id) {
                        html += `<option value="${tags[j].tag_id}">${tags[j].tag_name}</option>`
                    }
                }
                html += `</select>`
            }
        }
        tagField.innerHTML = html
    })


}()

