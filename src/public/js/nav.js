let conteudo = document.getElementById('conteudo');
let active = document.getElementById('active');


active.addEventListener('click', () => {
    if(active.checked){
        conteudo.style.display = 'none';
    }else{
        conteudo.style.display = 'flex';
    }
})

