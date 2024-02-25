const form_ajax = document.querySelectorAll(".FormularioAjax");

function enviar_formulario_ajax(e){
    e.preventDefault();
    let enviar = confirm("Quieres enviar formulario");
    if (enviar == true) {
        let data = new FormData(this);
        let metodo=this.getAttribute("method");
        let action = this.getAttribute("action");
        
        let encabezado = new Headers();
        let conf={
        method: metodo,
        headers:encabezado,
        mode:"cors",
        cahe:"no-cache",
        body:data };
        fetch(action,conf)
        .then(res=>res.text())
        .then(res=>{
            let contenedor = document.querySelector(".form-rest");
            contenedor.innerHTML = res;
        });
    }
    
}
form_ajax.forEach(formularios=>{
    formularios.addEventListener("submit",enviar_formulario_ajax);
});

