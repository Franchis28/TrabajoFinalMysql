// IniciaLización de datepicker
console.log('Hola mamahuevo');
    $(document).ready(function(){
        $('#datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    }); 

    function lanzar_msg(msg){
        //Creamos un alert para mostrar el mensaje
        alert(msg);
    }


   