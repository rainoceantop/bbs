
paramsStr = window.location.search.substr(1).split('&')
let id
for (let i in paramsStr) {
    if (paramsStr[i].substr(0, 3) === 'id=') {
        id = paramsStr[i].substr(3)
        break
    }
}
axios.get('../back/getThread.php?id=' + id)
    .then(response => {
        let content = document.querySelector('.post-content')
        let data = response.data[0][0]
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