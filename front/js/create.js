
const titleField = document.querySelector('.title-field')
const forumField = document.querySelector('#forum-options')
const tagGroupField = document.querySelector('.tag-group-options')
const tagField = document.querySelector('.tag-options')
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

//监听板块改变，获取该板块下的标签组
forumField.addEventListener('change', getTagGroups)
//监听标签组变化，获取该标签组下的标签
tagGroupField.addEventListener('change', getTags(this.value))


//监听文本输入域
let converter = new showdown.Converter()
bodyField.addEventListener('input', showMarkdownStyle)

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

//显示markdown转换后的样式
function showMarkdownStyle() {
    let html = converter.makeHtml(this.value)
    showContent.innerHTML = html
}

//获取标签组
function getTagGroups() {
    axios.get('../back/model/tag/getTag.php?for=getTagGroups&id=' + this.value)
        .then(response => {
            let data = response.data
            console.log(data)
            let html = ''
            for (let i in data) {
                html += `<option value='${data[i].tag_group_id}'>${data[i].tag_group_name}</option>`
            }
            tagGroupField.innerHTML = html
        })
        .catch(error => {
            console.log(error)
        })
}

//获取标签
function getTags(tag_group_id) {
    axios.get('../back/model/tag/getTag.php?for=getTags&tag_group_id=' + tag_group_id)
        .then(response => {
            let data = response.data
            console.log(data)
            let html = ''
            for (let i in data) {
                html += `<option value='${data[i].tag_id}'>${data[i].tag_name}</option>`
            }
            tagField.innerHTML = html

        })
        .catch(error => {
            console.log(error)
        })
}