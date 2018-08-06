const footer = document.querySelector('footer')
axios.get('_part/footer.html')
    .then(response => {
        footer.innerHTML = response.data
    })