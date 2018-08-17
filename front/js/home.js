
//获取url的id参数
let uri = window.location.search
let params = new URLSearchParams(uri)
let tagId = params.get('tagId')
let search = params.get('search')
let api_url
const content = document.querySelector('.content')
const pageLink = document.querySelector('.page-link')


if (isNaN(tagId) || tagId == null)
    api_url = '../back/model/thread/getThread.php?for=getHomeThreads'
else
    api_url = '../back/model/thread/getThread.php?for=getHomeThreadsByTagId&&id=' + tagId

if (search != null)
    api_url = '../back/model/thread/getThread.php?for=getSearchThreads&&s=' + search


axios.get(api_url)
    .then(response => {
        console.log(response.data)

        let thread_info = response.data[0]

        let total_rows = thread_info.length
        let rows_per_page = 10
        let total_pages = Math.ceil(total_rows / rows_per_page)
        if (total_rows > rows_per_page) {
            for (let i = 1; i <= total_pages; i++) {
                pageLink.innerHTML += `<a href="#!" style="color:white;margin:0 .5rem;text-decoration: none;outline:none;" class="to-page" data-start=${(i - 1) * rows_per_page}> ${i} </a>`
            }
        }
        //监听搜索框
        const searchInput = document.querySelector('.search-input')
        searchInput.value = search
        searchInput.addEventListener('keydown', function (e) {
            if (e.keyCode == 13) {
                this.parentElement.href = 'home.html?search=' + this.value
            }
        })

        //初始化页面
        showPage(0, rows_per_page, thread_info, content)

        const toPage = pageLink.querySelectorAll('.to-page')
        toPage.forEach(page => {
            page.addEventListener('click', function () {
                let start_index = parseInt(page.dataset.start)
                let end_index = start_index + rows_per_page
                showPage(start_index, end_index, thread_info, content)
            })
        })

        function showPage(start_index, end_index, thread_info, content) {
            let html = ''
            for (let i = start_index; i < end_index; i++) {
                if (i == total_rows)
                    break
                let tagHtml = ''
                for (let j = 0; j < thread_info[i].tags.length; j++) {
                    tagHtml += `<span class="tag"><a href='home.html?tagId=${thread_info[i].tags[j].id}'>${thread_info[i].tags[j].name}</a></span>`
                }
                html +=
                    `
                <div class="thread">
                <div class="avatar">
                    <img src="${thread_info[i].avatar}" alt="">
                </div>
                <div class="info">
                    <div class="title-tags">
                        <a href="detail.html?id=${thread_info[i].thread_id}" class="thread-title">${thread_info[i].thread_title}</a>
                        <span class="tag-field">${tagHtml}</span>
                    </div>
                    <div class="thread-footer">
                        <div class="footer-left">                        
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
                        <div class="footer-right">
                            <span class="replies">
                            <i class="far fa-comment"></i>
                            ${thread_info[i].replies}
                            </span>
                        </div>
                    </div>
                </div>
                </div>
                `
            }
            content.innerHTML = html
        }

    })

    .catch(error => console.log(error))
