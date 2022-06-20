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

{
    const csv = document.querySelector('.csv');
    const display3 = document.querySelector('.display3');
    const batu = document.querySelector('.fa-xmark');

    csv.addEventListener('click',() => {
        display3.classList.add('fadeIn');
    })

    batu.addEventListener('click',() => {
        display3.classList.remove('fadeIn');
    })
}

{
    const top1 = document.querySelector('.top1');
    const ioi = document.querySelector('.ioi');
    const head = document.querySelector('.head');
    const top1Height = top1.getBoundingClientRect().height;
    const headHeight = head.getBoundingClientRect().height;
    const ioiHeight = ioi.getBoundingClientRect().height + 100;
    const per = (top1Height + ioiHeight + headHeight) / window.innerHeight;
    const scroll = document.querySelector('.scroll');
    const per2 = 1 - per;

    // console.log(per2);

    const ii = scroll.style.height = `${per2 * 100}%`;
}