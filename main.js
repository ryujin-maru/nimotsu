'use strict';
{
    window.addEventListener('pageshow',()=>{
        if(window.performance.navigation.type==2) location.reload();
    });

    const qs = document.querySelectorAll('.q');
    const radios = document.querySelectorAll('input[type="radio"]');

    radios.forEach((radio,index) => {
        radio.addEventListener('click',() => {
            qs.forEach(q => {
                q.classList.add('fade');
            })
            qs[index].classList.remove('fade');
        });
    });
}

{
    const form = document.getElementById('userInfo');
    const id = document.getElementById('update_id');
    const update1 = document.querySelector('input[name="update1"]');
    const update2 = document.querySelector('input[name="update2"]');
    const delete1 = document.querySelector('input[name="delete_id"]');
    const csv = document.querySelector('input[name="csv"]');
    const ps = document.querySelectorAll('.err p');
    const form1 = document.querySelector('.form1');
    const form3 = document.querySelector('.form3');
    const yy = document.querySelector('.yy');
    const ii = document.querySelector('.ii');
    const form4 = document.querySelector('.form4');
    const img = document.querySelector('.img_id');
    const kk = document.querySelector('.kk');

    window.addEventListener('DOMContentLoaded', function(){
        form.addEventListener('submit',(e) => {
            if(id.value === "") {
                e.preventDefault();
                ps[0].classList.add('block');
            }else{
                ps[0].classList.remove('block');
            }

            if(update1.value === '' || update1.value.length > 11) {
                e.preventDefault();
                ps[1].classList.add('block');
            }else{
                ps[1].classList.remove('block');
            }

            if(update2.value === '') {
                e.preventDefault();
                ps[2].classList.add('block');
            }else{
                ps[2].classList.remove('block');
            }
            });

            form1.addEventListener('submit',(e) => {
                if(delete1.value === "") {
                    e.preventDefault();
                    yy.classList.add('block');
                }else{
                    yy.classList.remove('block');
                }
            })

            form3.addEventListener('submit',(e) => {
                if(csv.value === "") {
                    e.preventDefault();
                    ii.classList.add('block');
                }else{
                    ii.classList.remove('block');
                }
            })

            form4.addEventListener('submit',(e) => {
                if(img.value == "") {
                    e.preventDefault();
                    kk.classList.add('block');
                }else{
                    kk.classList.remove('block');
                }
            })

        });
}  



    

    