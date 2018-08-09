
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
                let html = ''
                for (let i in threads) {
                    let tagHtml = ''
                    for (let j = 0; j < threads[i].tags.length; j++) {
                        tagHtml += `<span class="tag">${threads[i].tags[j]}</span>`
                    }
                    html += `
                    
                <div class="thread">
                <div class="avatar">
                    <img src="${threads[i].avatar}" alt="">
                </div>
                <div class="info">
                    <a href="detail.html?id=${threads[i].thread_id}" class="thread-title">${threads[i].thread_title}</a>
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
            })
            .catch(error => {
                console.log(error)
            })
    }
}

