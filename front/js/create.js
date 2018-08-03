
const titleField = document.querySelector('.title-field')
const bodyField = document.querySelector('.body-field')
const headField = document.querySelector('.thread-head')
const showContent = document.querySelector('.show-markdown-content-area')
const createButton = document.querySelector('.create-button')


bodyField.addEventListener('input', function () {
    let converter = new showdown.Converter(),
        html = converter.makeHtml(this.value);
    showContent.innerHTML = html
})

createButton.addEventListener('click', function (e) {
    e.preventDefault()
    let params = {
        thread_title: titleField.value,
        thread_body: bodyField.value,
        thread_head: headField.value
    }
    axios.post('../back/addThread.php', params)
        .then(response => {
            window.location.href = `detail.html?id=${response.data}`
        })
        .catch(error => {
            console.log(error)
        })
})