
const userAvatar = document.querySelector('.user-avatar')
const listContent = document.querySelector('.list-content')

setTimeout(getListContent, 500)
function getListContent() {
    if (!window.is_login) {
        window.location.href = 'login.html'
    } else {
        //已经登录，获取用户帖子信息
        axios.get('../back/model/thread/getThread.php?for=getUserThreads&id=' + window.user_id)
            .then(response => {
                let threads = response.data[0]
                console.log(threads)
                let html = ''
                for (let i in threads) {
                    let tagHtml = ''
                    let is_filed = threads[i].is_filed
                    let is_file_msg = ''
                    if (parseInt(is_filed)) {
                        is_file_msg = '<span>已归档</span>'
                    } else {
                        is_file_msg = `<a href='#!' class="thread-file" data-thread_id=${threads[i].thread_id}>归档</a>`
                    }
                    for (let j = 0; j < threads[i].tags.length; j++) {
                        tagHtml += `<span class="tag"><a href='home.html?tagId=${threads[i].tags[j].id}'>${threads[i].tags[j].name}</a></span>`
                    }
                    html += `
                    
                <div class="thread">
                <div class="avatar">
                    <img src="${threads[i].avatar}" alt="">
                </div>
                <div class="info">
                    <a href="detail.html?id=${threads[i].thread_id}" class="thread-title">${threads[i].thread_title}</a>
                    <a href='update.html?id=${threads[i].thread_id}'>编辑</a>
                    <span class="is-file-display">${is_file_msg}</span>
                    <span class="tag-field">${tagHtml}</span>
                    <div class="thread-footer">
                        <span class="head-name">
                        ${threads[i].thread_head}
                        </span>
                        <span class="posted-time">
                        ${threads[i].posted_time}
                        </span>
                        <span class="arrow">
                            <i class="fas fa-angle-double-left"></i>
                        </span>
                        <span class="last-replied">
                        ${threads[i].replied_user}
                        </span>
                        <span class="replied-time">
                        ${threads[i].replied_time}
                        </span>
                    </div>
                </div>
                </div>`
                }
                listContent.innerHTML = html

                const threadFile = listContent.querySelectorAll('.thread-file')
                threadFile.forEach(item => {
                    item.addEventListener('click', function (e) {
                        e.preventDefault()
                        axios.get('../back/model/thread/updThread.php?for=file&thread_id=' + item.dataset.thread_id)
                            .then(response => {
                                console.log(response.data)
                                if (response.data == 'SUCCESS') {
                                    item.parentElement.innerHTML = '<span>已归档</span>'
                                }
                            })
                            .catch(error => console.log(error))
                    })
                })
            })
            .catch(error => {
                console.log(error)
            })
    }
}

