//查看当前用户是否有权查看内容
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        //如果不是管理员则确认有没权限
        if (!response.data.is_admin == '1') {
            axios.get('../back/handler/rightsHandler.php?check=canReadThread&user_id=' + response.data.id)
                .then(response => {
                    if (!response.data) {
                        alert('抱歉，您无权访问')
                        window.location.href = 'home.html'
                    } else {
                        loadPage()
                    }
                })
                .catch(error => console.log(error))
        }
    })
    .catch(error => console.log(error))

function loadPage() {
    //获取url的id参数
    let uri = window.location.search
    let params = new URLSearchParams(uri)
    let id = params.get('id')

    //查询当前id的信息                                  
    axios.get('../back/model/thread/getThread.php?for=getThreadDetail&id=' + id)
        .then(response => {
            const location = document.querySelector('.location')
            const content = document.querySelector('.post-content')
            console.log(response.data)
            let data = response.data[0][0]

            let tagHtml = ''
            for (let j = 0; j < data.tags.length; j++) {
                tagHtml += `<span class="tag"><a href='home.html?tagId=${data.tags[j].id}'>${data.tags[j].name}</a></span>`
            }
            //获取板块名
            axios.get('../back/model/forum/getForum.php?for=getForumNameById&id=' + data.forum_id)
                .then(response => {
                    //显示位置
                    location.innerHTML = `<a href='home.html'>首页</a> / <a href='forum.html?forum=${response.data[0]}'>${response.data[1]}</a> / <span>${data.thread_title}</span>`
                })
                .catch(error => {
                    console.log(error)
                })



            //显示内容信息
            content.innerHTML =
                `
            <div class="post-header">
            <div class="post-title">
                ${data.thread_title} <span class="tag-field">${tagHtml}</span>
            </div>
            <div class="post-info">
                ${data.thread_head} &sdot; ${data.posted_time} &sdot;
                <i class="fas fa-eye"></i> <span id="thread-views"></span>
            </div>
            </div>
            <div class="post-body">
                ${data.thread_body}    
            </div>
            <div class="post-footer">
                <div class="like">
                    <i class="far fa-star"></i> Like it
                </div>
                <div class="avatar">
                    <img src="${data.avatar}" alt="">
                </div>
            </div>
            `



            const viewsArea = content.querySelector('#thread-views')
            //获取帖子阅读量
            axios.get('../back/log/thread.log.json')
                .then(response => {
                    for (let i in response.data.views) {
                        if (response.data.views[i].id == id) {
                            viewsArea.innerHTML = response.data.views[i].amount + 1
                            break;
                        }
                    }
                })
                .catch(error => console.log(error))
            //更新帖子阅读量
            axios.get('../back/model/thread/updThread.php?for=views&id=' + id)
                .then(response => console.log(response.data))
                .catch(error => console.log(error))

            //回复帖子域
            let replyForm = document.querySelector('.reply-form')
            let replyBody = replyForm.querySelector('textarea')
            let replyFormSubmitButton = replyForm.querySelector('.reply-form-submit-button')
            //初始化变量，如果回复的是评论则修改
            let replied_index = 0
            let to_user_id = data.head_id
            let is_filed = parseInt(data.is_filed)


            //显示回复
            const replyItems = document.querySelector('.reply-items')
            const replyLabel = document.querySelector('.reply-label')
            //获取回复
            axios.get('../back/model/reply/getReply.php?for=getRepliesByThreadId&thread_id=' + id)
                .then(response => {
                    let resp = response.data
                    //评论数统计
                    replyLabel.innerHTML = `<h3>最新回复(${resp.length})</h3>`

                    let html = ''
                    for (let i in resp) {
                        html +=
                            `
                    <div class="reply-item">
                    <div class="reply-avatar">
                        <img src="${resp[i].from_user_avatar}">
                    </div>
                    <div class="reply-info">
                        <div class="reply-header">
                            <div>
                                <span class="user-name">${resp[i].from_user_name}</span>
                                <span class="date">${resp[i].replied_time}</span>
                            </div>
                            <div>
                                <span>
                                    <a href="#!" class="reply-button" data-rname="${resp[i].from_user_name}" data-rid="${resp[i].from_user_id}" data-index="${resp[i].replied_id}"><i class="fas fa-reply"></i></a>
                                    ${parseInt(i) + 1}楼
                                </span>
                            </div>
                        </div>
                        <div class="reply-body">
                        `
                        if (resp[i].replied_index != 0) {
                            html +=
                                `
                            <div class="replied-show">
                                <div class="avatar">
                                    <img src="${resp[i].to_user_avatar}">
                                </div>
                                <div class="name">${resp[i].to_user_name}</div>
                                <div class="info">${resp[i].to_user_replied}</div>
                            </div>
                            `
                        }
                        html +=
                            `
                            ${resp[i].replied_body}
                        </div>
                    </div>
                </div>
                    `
                    }
                    replyItems.innerHTML = html

                    //如果帖子已经归档，不开放评论
                    if (is_filed) {
                        replyForm.innerHTML = ''
                    } else {
                        //监听回复按钮点击事件
                        const replyButton = replyItems.querySelectorAll('.reply-button')
                        replyButton.forEach(button => {
                            //回复帖子或者留言开关
                            let toggle = false
                            button.addEventListener('click', function () {
                                if (!toggle) {
                                    to_user_id = this.dataset.rid
                                    replied_index = this.dataset.index
                                    replyBody.placeholder = `回复 ${this.dataset.rname}：`
                                    window.location.hash = "#reply-form"
                                    replyBody.focus()
                                    toggle = true
                                } else {
                                    to_user_id = data.head_id
                                    replied_index = 0
                                    replyBody.placeholder = ''
                                    window.location.hash = "#reply-form"
                                    replyBody.focus()
                                    toggle = false
                                }
                            })
                        })
                    }
                })
                .catch(error => {
                    console.log(error)
                })

            //如果帖子没有归档， 监听提交按钮
            if (!is_filed) {
                replyFormSubmitButton.addEventListener('click', function (e) {
                    e.preventDefault()
                    axios.get('../back/handler/rightsHandler.php?check=canReplyThread&user_id=' + window.user_id)
                        .then(response => {
                            if (!response.data) {
                                alert('抱歉，您无权回复')
                                return false
                            } else {

                                let params = {
                                    thread_id: id,
                                    replied_body: replyBody.value,
                                    replied_index: replied_index,
                                    from_user_id: window.user_id,
                                    to_user_id: to_user_id
                                }
                                axios.post('../back/model/reply/addReply.php', params)
                                    .then(response => {
                                        if (response.data == 'SUCCESS') {
                                            replyBody.value = ''
                                            window.location.reload()
                                        }
                                        else {
                                            alert('回复评论出错，可能未登录或其他原因')
                                        }
                                    })
                                    .catch(error => {
                                        console.log(error)
                                    })
                            }
                        })
                        .catch(error => console.log(error))
                })
            }

        })
        .catch(error => {
            console.log(error)
        })
}