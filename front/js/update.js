//查看当前用户是否有权编辑
axios.get('../back/handler/loginHandler.php?log=2')
    .then(response => {
        //如果不是管理员则确认有没权限
        if (!response.data.is_admin == '1') {
            axios.get('../back/handler/rightsHandler.php?check=canEditThread&user_id=' + response.data.id)
                .then(response => {
                    console.log(response.data)
                    if (response.data != 1) {
                        alert('抱歉，您无权编辑')
                        window.location.href = 'home.html'
                    }
                })
                .catch(error => console.log(error))
        }
    })
    .catch(error => console.log(error))


//获取url的id参数
let uri = window.location.search
let params = new URLSearchParams(uri)
let id = params.get('id')


//本页面不换class，采用和create一样的样式
const updateContent = document.querySelector('.create-content')

//查询当前id的信息                                  
axios.get('../back/model/thread/getThread.php?for=getThreadDetail&id=' + id)
    .then(response => {
        console.log(response.data)
        let data = response.data[0][0]

        if (data.head_id != window.user_id) {
            alert('你不是这个帖子的负责人，无法编辑')
            window.history.go(-1)
        }

        let is_filed = parseInt(data.is_filed)

        if (is_filed) {
            alert('已归档帖子不可修改')
            window.history.go(-1)
        }

        updateContent.innerHTML =
            `
        <form action = "../back/addThread.php" method = "POST" >
            <input class="title-field" type="text" name="thread_title" value=${data.thread_title} placeholder="标题">
                <textarea class="body-field" name="thread_body" placeholder="内容">${data.thread_body}</textarea>
                <input class="title-field" type="text" name="update_reason" placeholder="编辑原因">
        </form>
            <div class="show-markdown-content-area">
            </div>
            <a href="#!" class="create-button">更新</a>
        `
        const titleField = document.querySelector('input[name="thread_title"]')
        const bodyField = document.querySelector('.body-field')
        const reasonField = document.querySelector('input[name="update_reason"]')
        const updateButton = updateContent.querySelector('.create-button')
        updateButton.addEventListener('click', function (e) {
            e.preventDefault()

            //获取用户登录信息
            if (window.is_login) {
                let converter = new showdown.Converter()
                let html = converter.makeHtml(bodyField.value)
                let params = {
                    thread_id: id,
                    thread_title: titleField.value,
                    thread_body: html,
                    updated_reason: reasonField.value
                }
                axios.post('../back/model/thread/updThread.php?for=edit', params)
                    .then(response => {
                        window.location.href = `detail.html?id=${id}`
                    })
                    .catch(error => {
                        console.log(error)
                    })

            } else {
                window.location.href = 'login.html'
            }
        })
    })
    .catch(error => console.log(error))

