'use strict';

{
    
    const nos = document.querySelectorAll('.no');
    const display = document.querySelector('.display');
    const oo = document.querySelector('.oo');
    const display2 = document.querySelector('.display2');

    nos.forEach(no => {
        if(no != null){
            no.addEventListener('click',() => {
                display.style.display = 'none';
            }); 
        }
    })

    if(oo != null){
        oo.addEventListener('click',() => {
            display2.style.display = 'none';
        }); 
    }

    
}

{
    const lis = document.querySelectorAll('.li');
    const lists = document.querySelectorAll('.list');
    const up = document.querySelector('.fa-angle-up');
    const down = document.querySelector('.fa-angle-down');

    lis.forEach((li,index) => {
        li.addEventListener('click',() => {
            lists[index].classList.toggle('open');
            up.classList.toggle('show');
            if(up.classList.contains('show')){
                down.style = 'display: none';
            }else{
                down.style = 'display: block';
            }
        })
    })



}