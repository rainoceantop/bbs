let uri = window.location.search
let params = new URLSearchParams(uri)

//获取该板块的名称
axios.get('../back/model/forum/getForum.php?for=getForumNameById&id=' + params.get('forum'))
    .then(response => {
        const location = document.querySelector('.location')
        location.innerHTML = `<a href='home.html'>首页</a> / <span>${response.data[1]}</span>`
    })


//获取该板块的帖子
axios.get('../back/model/thread/getThread.php?for=getForumThreads&id=' + params.get('forum'))
    .then(response => {
        console.log(response.data)
        const content = document.querySelector('.content')
        let thread_info = response.data[0]

        for (let i in thread_info) {
            let tagHtml = ''
            for (let j = 0; j < thread_info[i].tags.length; j++) {
                tagHtml += `<span class="tag"><a href='home.html?tagId=${thread_info[i].tags[j].id}'>${thread_info[i].tags[j].name}</a></span>`
            }
            content.innerHTML +=
                `
                <div class="thread">
                <div class="avatar">
                    <img src="${thread_info[i].avatar}" alt="">
                </div>
                <div class="info">
                    <a href="detail.html?id=${thread_info[i].thread_id}" class="thread-title">${thread_info[i].thread_title}</a>
                    <span class="tag-field">${tagHtml}</span>
                    <div class="thread-footer">
                        <span class="head-name">
                        ${thread_info[i].thread_head}
                        </span>
                        <span class="posted-time">
                        ${thread_info[i].posted_time}
                        </span>
                        <span class="arrow">
                            <i class="fas fa-angle-double-left"></i>
                        </span>
                        <span class="last-replied">
                        ${thread_info[i].replied_user}
                        </span>
                        <span class="replied-time">
                        ${thread_info[i].replied_time}
                        </span>
                    </div>
                </div>
                </div>
                `
        }
    })
    .catch(error => {
        console.log(error)
    })