
function show_clients() {
    document.getElementById('history_page').style.display="none";
    document.getElementById('clients_page').style.display="block";
    $("#nav_hidden").click();
    
};

function show_history() {
    document.getElementById('clients_page').style.display="none";
    document.getElementById('history_page').style.display="block";
    $("#nav_hidden").click();
};

function show_message(id,message,color = 'success'){

    document.getElementById(id).innerHTML = `
        <div class="alert alert-${color} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>`
};

function empty_history(){
    template = `
        <tr>
            <td class="column0">0</td>
            <td><div class='text-center'>0</div></td>
            <td>Sin registro</td>
            <td>Sin registro</td>
            <td>Sin registro</td>
            <td>Sin acciones</td>
        </tr>
    `
    $('#history_table').html(template);
};

show_clients();
empty_history();

$(document).ready(() => {
    let client_id, type, row, clients_view, count_click_ip = 1, order_ip='asc';
    let months_array = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    clients_view = 'Activo';
    //Llenar tabla de clientes
    function client_table () {
        $.ajax({
            url: '../db/crud.php', 
            method: 'POST', //usamos el metodo POST
            data:{
                type: 'consult_client',
                clients_view: clients_view,
                order_ip: order_ip},
            success: (response) => {
                let records = JSON.parse(response)
                let template = '';
                let count = 1;
                records.forEach(record => {                    
                    template += `
                        <tr>
                            <td class="column0">${record.id}</td>
                            <td>
                                <div class='text-center'>
                                    ${ count }
                                </div>
                            </td>
                            <td class="edit-status">${record.name}</td>
                            <td>${record.ip_adress}</td>
                            <td>
                                <div class='text-center'>
                                <div class='btn-group'>
                                <button class='btn btn-success btn-sm edit-client'>Editar</button>
                                <button class='btn btn-danger btn-sm delete-client'>Eliminar</button>
                                <button class='btn btn-warning btn-sm history-client'>Historial</button>
                                </div>
                                </div>
                            </td>
                        </tr>
                    `
                    count+=1;
                });
    
                $('#client_table').html(template);
            }
        });
    }
    $('#view_select').on('change',() => {
        let vs = $('#view_select').val();
        if (vs == 'Ver activos'){clients_view = 'Activo'}
        else if (vs == 'Ver todos'){clients_view = 'all'}
        else if (vs == 'Ver inactivos'){clients_view = 'Inactivo'}
        client_table();
        client_select ();
        empty_history();
    });

    $(document).on('click','#order_ip', function() {
        count_click_ip+=1;

        if (count_click_ip%2==0){
            order_ip = 'desc';
            client_table();
        }else{
            order_ip = 'asc';
            client_table();
        }
    })

    // Abrir un modal para editar el estado del cliente
    $(document).on('click','.edit-status', function() {
        row = $(this).closest("tr");
        client_id = parseInt(row.find('td:eq(0)').text());
        name = row.find('td:eq(2)').text();
        $("#status_form").trigger("reset");
        $(".modal-header").css( "background-color", "#D42929");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text(name);
        $('#modal_status').modal('show');	
        $.ajax({
            url: '../db/crud.php',
            method: 'POST',
            datatype:"json",
            data: {type: 'upload_client',
                    client_id: client_id},
            success: (response) => {
                let records = JSON.parse(response);
                records.forEach(record => {
                    $("#status").val(record.status);     
                })
            }            
        })     
    });

    // Enviar actualizacion de estado al servidor
    $('#status_form').submit((e) => {         
        e.preventDefault();
        status = $('#status').val(); 
        status_date = $('#status_date').val(); 
        
        $.ajax({
            url: '../db/crud.php',
            type: 'POST',
            datatype:"json", 
            data : {
                client_id: client_id,
                description: status,
                payment_date : status_date,
                type: 'edit_status'
            },   
            success: () => {
                $('#modal_status').modal('hide');
                show_message('message_client','Estado actualizado correctamente');
                client_table();	
            }
        });
    });

    client_table();    

    //Buscar cliente
    $('#search').keyup(() => {
        let search = $('#search').val();
        $.ajax({
            url: '../db/crud.php',
            method: 'POST',
            data: {
                type: 'search_client',
                clients_view: clients_view,
                name: search
            },
            success: (response) => {
                let records = JSON.parse(response)
                let template = '';
                let count = 1;
                records.forEach(record => {                    
                    template += `
                        <tr>
                            <td class="column0">${record.id}</td>
                            <td>
                                <div class='text-center'>
                                    ${ count }
                                </div>
                            </td>
                            <td class="edit-status">${record.name}</td>
                            <td>${record.ip_adress}</td>
                            <td>
                                <div class='text-center'>
                                <div class='btn-group'>
                                <button class='btn btn-success btn-sm edit-client'>Editar</button>
                                <button class='btn btn-danger btn-sm delete-client'>Eliminar</button>
                                <button class='btn btn-warning btn-sm history-client'>Historial</button>
                                </div>
                                </div>
                            </td>
                        </tr>
                    `
                    count+=1;
                });
                if (count==1){
                    template = `
                        <tr>
                            <td class="column0">0</td>
                            <td><div class='text-center'>0</div></td>
                            <td>Sin registro</td>
                            <td>Sin registro</td>
                            <td>Sin acciones</td>
                        </tr>
                    `
                }$('#client_table').html(template);
            }
        })
    })

    //Nuevo cliente
    $(".new-client").click(function(){
        type = 'new_client';        
        client_id=null;
        $("#client_form").trigger("reset");
        $(".modal-header").css( "background-color", "#D42929");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Nuevo cliente");
        $('#modal_client').modal('show');	    
    });

    // Editar cliente
    $(document).on("click", ".edit-client", function(){		        
        type = 'edit_client';//editar
        row = $(this).closest("tr");	        
        client_id = parseInt(row.find('td:eq(0)').text()); //capturo el ID
        $.ajax({
            url: '../db/crud.php',
            method: 'POST',
            datatype:"json",
            data: {type: 'upload_client',
                    client_id: client_id},
            success: (response) => {
                let records = JSON.parse(response);
                records.forEach(record => {
                    $("#name").val(record.name);
                    $("#ip_adress").val(record.ip_adress);
                    $("#home_adress").val(record.home_adress);
                    $("#phone").val(record.phone);
                    $("#month_payment").val(record.month_payment);
                    $("#mbps").val(record.mbps);
                    $("#install_date").val(record.install_date);           
                })
            }            
        })   		            
        $(".modal-header").css("background-color", "#D42929");
        $(".modal-header").css("color", "white" );
        $(".modal-title").text("Editar Cliente");		
        $('#modal_client').modal('show');		   
    });

    // Enviar datos del formulario de clientes al servidor
    $('#client_form').submit(function(e){                         
        e.preventDefault();
        name = $.trim($('#name').val());    
        ip_adress = $.trim($('#ip_adress').val());
        home_adress = $.trim($('#home_adress').val());    
        phone = $.trim($('#phone').val());    
        month_payment = $.trim($('#month_payment').val());
        mbps = $.trim($('#mbps').val());
        install_date = $.trim($('#install_date').val());                         
            $.ajax({
                url: "../db/crud.php",
                type: "POST",
                datatype: "json",    
                data:  {client_id:client_id,name:name,ip_adress:ip_adress,
                    home_adress:home_adress, phone:phone, month_payment:month_payment,
                    mbps:mbps, install_date: install_date,type:type},    
                success: () => {
                    client_table();
                }
            });			        
        $('#modal_client').modal('hide');
        if (type=='new_client'){
            $.ajax({
                url: '../db/crud.php',
                method: 'POST',
                datatype:"json",
                data : { type: 'get_last_client' },
                success: (response) => {
                    let records = JSON.parse(response);
                    records.forEach(record => {
                        $.ajax({  
                            url: '../db/crud.php',
                            method: 'POST',
                            data: { client_id : record.id, 
                                description: 'Instalación',
                                payment_date: install_date,
                                payment: month_payment,
                                type: 'add_history'
                                },
                            success: ()=>{
                                client_select ();
                                show_message('message_client','Cliente registrado satisfactoriamente');
                            }
                        })
                    });
                }

            });
        }else{
            show_message('message_client','Los datos del cliente han sido actualizados satisfactoriamente');
        }
    });

    // Eliminar cliente
    $(document).on("click", ".delete-client", function(){
        row = $(this);           
        client_id = parseInt($(this).closest('tr').find('td:eq(0)').text());		
        let request = confirm("¿Está seguro de borrar el registro "+client_id+"?");                
        if (request) {            
            $.ajax({
                url: "../db/crud.php",
                type: "POST",
                datatype:"json",    
                data:  {type: 'delete_client', client_id: client_id},    
                success: function() {
                    client_table();
                    client_select ();
                    show_message('message_client','Cliente eliminado satisfactoriamente');                  
                }
            });	
        }
    });

    // direccionar a la pestaña historial  
    $(document).on("click", ".history-client", function(){
        row = $(this);
        client_id = parseInt($(this).closest('tr').find('td:eq(0)').text());
        $('#client_select').val(client_id);
        history_table();
        show_history()
    });

    // Capturar cambio en el select de clientes
    $('#client_select').on('change',()=> {
        history_table();
        if ($('#client_select').val()!='Elige un cliente'){
            client_id = $('#client_select').val();
            $.ajax({
                url: '../db/crud.php',
                method: 'POST',
                datatype:"json",
                data: {type: 'upload_client',
                        client_id: client_id},
                success: (response) => {
                    let records = JSON.parse(response);
                    records.forEach(record => {
                        $('#payment').val(record.month_payment);             
                    })
                }            
            })      
        } else {
            $('#payment').val('');
        }
    });

    // Rellenar el select de clientes
    function client_select () {
        $.ajax({
            url:   '../db/crud.php',
            method:  'POST',
            datatype: 'JSON',
            data: {type: 'consult_client',
                clients_view: clients_view,
                order_ip: order_ip
        },
            success:  function (response) {
                let records = JSON.parse(response);
                $('#client_select').find('option').remove();
                $('#client_select').append('<option value="0">Elige un cliente</option>');
                records.forEach(record => {
                    $('#client_select').append('<option value="' + record.id + '">' + record.name + '</option>');  
                })
            }
        });
    };

    // Rellenar la tabla historial de acuerdo al cliente establecido por el usuario
    function history_table () {
        let client_id = $('#client_select').val();
        $.ajax({
            url: '../db/crud.php', 
            method: 'POST', //usamos el metodo POST
            data:{type: 'consult_history',
                    client_id:client_id},
            success: (response) => {
                let records = JSON.parse(response)
                let template = '';
                let count = 1;
                records.forEach(record => {
                    template += `
                        <tr>
                            <td class="column0">${record.id}</td>
                            <td>
                                <div class='text-center'>
                                    ${ count }
                                </div>
                            </td>
                            <td>${record.description}</td>
                            <td>${record.payment_date}</td>
                            <td>${record.payment}</td>
                            <td>
                                <div class='text-center'>
                                <div class='btn-group'>
                                <button class='btn btn-success btn-sm edit-history'>Editar</button>
                                <button class='btn btn-danger btn-sm delete-history'>Eliminar</button>
                                </div>
                                </div>
                            </td>
                        </tr>
                    `
                    count+=1;
                });
                $('#history_table').html(template);
            }
        });
    }

    // Editar registro del historial
    $(document).on("click", ".edit-history", function () {	        
        row = $(this).closest("tr");	        
        history_id = parseInt(row.find('td:eq(0)').text()); //capturo el ID		         
        description = row.find('td:eq(2)').text();
        date_edit = row.find('td:eq(3)').text();
        payment_edit = row.find('td:eq(4)').text();
        
        $("#description").val(description);
        $("#date_edit").val(date_edit);
        $("#payment_edit").val(payment_edit);
        $(".modal-header").css("background-color", "#D42929");
        $(".modal-header").css("color", "white" );
        $(".modal-title").text("Editar historial");		
        $('#modal_history').modal('show');		   
    });

    // Eliminar registro del historial
    $(document).on("click", ".delete-history", function()  {
        row = $(this);           
        history_id = parseInt($(this).closest('tr').find('td:eq(0)').text()) ;		    
        let request = confirm("¿Está seguro de borrar el registro "+history_id+"?");                
        if (request) {            
            $.ajax({
              url: "../db/crud.php",
              method: "POST",
              datatype:"json",    
              data:  {type:'delete_history', history_id:history_id},    
              success: () => {
                  history_table();
                  show_message('message_history','Registro eliminado satisfactoriamente');                  
               }
            });	
        }
    });

    // Enviar datos del formulario del historial al servidor
    $('#history_form').submit((e) => {         
        e.preventDefault();
        description = $.trim($('#description').val());    
        payment_date = $.trim($('#date_edit').val());
        payment = $.trim($('#payment_edit').val());    
            $.ajax({
                url: '../db/crud.php',
                type: 'POST',
                datatype:"json", 
                data : {
                    history_id: history_id,
                    description: description,
                    payment_date: payment_date,
                    payment : payment,
                    type: 'edit_history'
                },   
                success: () => {
                    history_table();
                }
            });			        
        $('#modal_history').modal('hide');
        show_message('message_history','Registro actualizado satisfactoriamente');	
    });

    // Registrar registro en el historial
    $('#payment_form').submit((e) => {                         
        e.preventDefault();
        client_id = $.trim($('#client_select').val());    
        year_month = $.trim($('#year_month').val());
        payment = $.trim($('#payment').val());    
        payment_date = $.trim($('#payment_date').val());
        
        m=year_month.slice(5, 7);
        month=months_array[m-1];
        year = year_month.slice(0, 4);

        year = payment_date.slice(0, 4);
        month = payment_date.slice(5, 7);
        day = payment_date.slice(8, 10);

        description = 'Pago '+month+' '+year;
        payment_date = day+'/'+month+'/'+year;
        
            $.ajax({
            url: '../db/crud.php',
            method: 'POST',
            datatype:"json",  
            data : {
                client_id : client_id,
				description : description,
                payment : payment,
                payment_date : payment_date,
                type: 'add_history',
			},   
            success: () => {
                history_table();
                show_message('message_history','Pago registrado satisfactoriamente');
            }
        });		        										     			
    });

    $("#change_pass").click(function(){
        $("#password_form").trigger("reset");
        $(".modal-header").css( "background-color", "#D42929");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Cambiar contraseña");
        $('#modal_password').modal('show');	    
    });

    $("#sign_off").click(function(){
        location.href = '/';
    });

    $('#password_form').submit((e) => {         
        e.preventDefault();
        current_pass = $.trim($('#current_pass').val());    
        new_pass = $.trim($('#new_pass').val());
        confirm_pass = $.trim($('#confirm_pass').val()); 
        if (new_pass == confirm_pass){ 
            $.ajax({
                url: '../db/crud.php',
                method: 'POST',
                datatype:"json",
                data: {
                    type: 'validate_password',
                    current_pass: current_pass,
                    new_pass: new_pass
                },
                success: (response) => {
                    let records = JSON.parse(response);
                    records.forEach(record => {
                        if (record.message == true){
                            $('#modal_password').modal('hide');
                            $("#nav_hidden").click();
                            show_message('message_client', 'La contraseña ah sido actualizada satisfactoriamente');
                        } else {
                            show_message('message_pass', 'La contraseña actual no coincide', 'danger');
                            $("#password_form").trigger("reset");
                        } 
                    });
                }
            });
        } else {
            show_message('message_pass', 'La contraseña de confirmación no coincide', 'danger');
        }			        
    });

    client_select ();

});    

