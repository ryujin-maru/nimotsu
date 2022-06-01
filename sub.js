'use strict';

{
    
    const no = document.querySelector('.no');
    const display = document.querySelector('.display');
    const oo = document.querySelector('.oo');
    const display2 = document.querySelector('.display2');

    if(no != null){
        no.addEventListener('click',() => {
            display.style.display = 'none';
        }); 
    }

    if(oo != null){
        oo.addEventListener('click',() => {
            display2.style.display = 'none';
        }); 
    }

    
}
