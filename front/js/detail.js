
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
                ${data.thread_title}
            </div>
            <div class="post-info">
                ${data.thread_head} &sdot; ${data.posted_time} &sdot;
                <i class="fas fa-eye"></i> ${data.views}
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
    })
    .catch(error => {
        console.log(error)
    })