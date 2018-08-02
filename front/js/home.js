axios.get('../back/getThread.php')
    .then(response => {
        const content = document.querySelector('.content')
        for (let i in response.data) {
            content.innerHTML +=
                `
            <div class="thread">
            <div class="avatar">
                <img src="${response.data[i].avatar}" alt="">
            </div>
            <div class="info">
                <a href="detail.html?id=${response.data[i].thread_id}&sc=sda" class="thread-title">${response.data[i].thread_title}</a>
                <div class="thread-footer">
                    <span class="head-name">
                    ${response.data[i].thread_head}
                    </span>
                    <span class="posted-time">
                    ${response.data[i].posted_time}
                    </span>
                    <span class="arrow">
                        <i class="fas fa-angle-double-left"></i>
                    </span>
                    <span class="last-replied">
                    ${response.data[i].replied_user}
                    </span>
                    <span class="replied-time">
                    ${response.data[i].replied_time}
                    </span>
                </div>
            </div>
            </div>
            `
        }
    })
    .catch(error => console.log(error))