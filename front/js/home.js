
axios.get('../back/model/thread/getThread.php?for=getHomeThreads')
    .then(response => {
        console.log(response.data)
        const content = document.querySelector('.content')
        let thread_info = response.data[0]
        for (let i in thread_info) {
            let tagHtml = ''
            for (let j = 0; j < thread_info[i].tags.length; j++) {
                tagHtml += `<span class="tag">${thread_info[i].tags[j]}</span>`
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
    .catch(error => console.log(error))
