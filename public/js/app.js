document.getElementById('filter_company_id').addEventListener('change', function (){

    let companyId = this.value || this.option[this.selectedIndex].value

    window.location.href = window.location.href.split('?')[0] + '?company_id=' + companyId

    //http://127.0.0.1:8000/contacts?page=4
    //http://127.0.0.1:8000/contacts?company_id=4
})

document.querySelectorAll('.btn-delete').forEach((button) =>{
    button.addEventListener('click', function (event){
        event.preventDefault()
        if (confirm("Are you sure?")){
            let action = this.getAttribute('href')
            let form = document.getElementById('form-delete')
            form.setAttribute('action', action)
            form.submit()
        }
    })
})

// alert message session expiere
setTimeout(() => {
    const box = document.getElementById('alert-time');

    //  removes element from DOM
    box.style.display = 'none';

    // hides element (still takes up space on the page)
    // box.style.visibility = 'hidden';
}, 2000);


// refresh on search
document.getElementById('btn-clear').addEventListener('click',() => {
    let input = document.getElementById('search'),
        select = document.getElementById('filter_company_id')

    input.value = ""
    select.selectedIndex = 0

    window.location.href = window.location.href.split('?')[0]
})

const toggleClearButton = () => {
   let query =  location.search,
       pattern = /[?&]search=/,  //?company_id=1&search=
       button = document.getElementById('btn-clear')

    if (pattern.test(query)){
        button.style.display = "block"
    }
    else{
        button.style.display = "none"
    }
}
toggleClearButton()





//alert message animation

// const alertTime = document.querySelector("#alert-time");

// alertTime.classList.add("show");
// setTimeout(function() {
//     alertTime.classList.add("fade-out");
//     setTimeout(function() {
//         alertTime.remove();
//     }, 3000);
// }, 4000);
