
axios.get('../back/getThread.php')
    .then(response => {
        const content = document.querySelector('.content')
        for (let i in response.data[0]) {
            content.innerHTML +=
                `
                <div class="thread">
                <div class="avatar">
                    <img src="${response.data[0][i].avatar}" alt="">
                </div>
                <div class="info">
                    <a href="detail.html?id=${response.data[0][i].thread_id}" class="thread-title">${response.data[0][i].thread_title}</a>
                    <div class="thread-footer">
                        <span class="head-name">
                        ${response.data[0][i].thread_head}
                        </span>
                        <span class="posted-time">
                        ${response.data[0][i].posted_time}
                        </span>
                        <span class="arrow">
                            <i class="fas fa-angle-double-left"></i>
                        </span>
                        <span class="last-replied">
                        ${response.data[0][i].replied_user}
                        </span>
                        <span class="replied-time">
                        ${response.data[0][i].replied_time}
                        </span>
                    </div>
                </div>
                </div>
                `
        }
    })
    .catch(error => console.log(error))
