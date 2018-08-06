
const titleField = document.querySelector('.title-field')
const forumField = document.querySelector('#forum-options')
const bodyField = document.querySelector('.body-field')
const headField = document.querySelector('.thread-head')
const showContent = document.querySelector('.show-markdown-content-area')
const createButton = document.querySelector('.create-button')

//获取板块名称
axios.get('../back/model/forum/getForum.php?for=getForumName')
    .then(response => {
        console.log(response.data)
        for (let forum_id in response.data) {
            forumField.innerHTML += `<option value="${forum_id}">${response.data[forum_id]}</option>`
        }
    })
    .catch(error => {
        console.log(error)
    })


//监听文本输入域
let converter = new showdown.Converter()
bodyField.addEventListener('input', function () {
    let html = converter.makeHtml(this.value)
    showContent.innerHTML = html
})

//监听thread发表按钮
createButton.addEventListener('click', function (e) {
    e.preventDefault()
    let html = converter.makeHtml(bodyField.value)
    let params = {
        thread_title: titleField.value,
        forum_id: forumField.value,
        thread_body: html,
        thread_head: headField.value
    }
    axios.post('../back/model/thread/addThread.php', params)
        .then(response => {
            window.location.href = `detail.html?id=${response.data}`
        })
        .catch(error => {
            console.log(error)
        })
})