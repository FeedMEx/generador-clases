<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="img/internet.png" />  
    <title>Internet en casa</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    
    <link rel="stylesheet" href="assets/main.css">  

</head>

<body> 

    <?php
        session_start();

        if(!isset($_SESSION["admin"])){
            header("Location: /");
        }
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-5">
        <a class="navbar-brand" href="/">Internet Test</a>
        <button class="navbar-toggler" type="button" id="nav_hidden" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="container">

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#" onclick="show_clients()">Clientes</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="#" onclick="show_history()">Historial</a>
                    </li>
                    <li class="nav-item active dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Mas
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#">Modo experto</a>
                            <a class="dropdown-item" href="#" id="change_pass">Cambiar contraseña</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="db/close_session.php" id="sign_off">Cerrar sesión</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" id="clients_page">
        <div class="row">
            <div class="col-md-12" id="message_client">     
            </div>
            
            <div class="col-md-8 mb-3">
                <select class="btn btn-primary" style="height: 38px" id="view_select">
                    <option>Ver activos</option>
                    <option>Ver todos</option>
                    <option>Ver inactivos</option>
                </select>

                <button type="button" class="btn btn-success new-client" data-toggle="modal">Nuevo cliente</button>
            </div>
            <div class="col-md-4 mb-3">
                <input class="form-control mr-sm-2" id="search" placeholder="Buscar cliente">
            </div>

            <div class="col-lg-12">
                <div class="table-responsive">        
                    <table class="table table-striped table-bordered table-condensed table-sm" >
                        <thead class="text-center thead-dark">
                            <tr>
                                <th class="column0">id</th>
                                <th>N°</th>
                                <th>Apellidos y nombres</th>
                                <th id="order_ip">Dirección IP</th>                                
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="client_table" >                           
                        </tbody>        
                    </table>               
                </div>
            </div>
        </div>  
    </div>

    <div class="container" id="history_page">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form id="payment_form">
                            <div class="form-group">
                                <label for="">Seleccione cliente</label>
                                <select class="form-control" id="client_select">
                                    <option>Elige un cliente</option>
                                </select>
                            </div>

                            <div class="form-group">
                            <label for="exampleFormControlInput1">Seleccione año y mes</label>
                            <input type="month" class="form-control" id="year_month" >
                            </div>
                            
                            <div class="form-group">
                                <label for="aa">Monto</label>
                                <input type="text" class="form-control" id="payment" >
                            </div>

                            <div class="form-group">
                                <label for="bbb">Fecha</label>
                                <input type="date" class="form-control" id="payment_date">
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Registrar</button>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div id="message_history">
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-sm" style="width:100%">
                        <thead class="text-center thead-dark">
                            <tr class="head-table">
                                <th class="column0">n</th>
                                <th>N°</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="history_table">                           
                        </tbody> 
                    </table>
                </div>
            </div>
        </div>  
    </div>

<!--Modal para CRUD-->

<div class="modal fade" id="modal_client" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                </button>
            </div>
        <form id="client_form">    
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                    <div class="form-group">
                    <label for="" class="col-form-label">Apellidos y Nombres:</label>
                    <input type="text" class="form-control" id="name">
                    </div>
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                    <label for="" class="col-form-label">Dirección IP</label>
                    <input type="text" class="form-control" id="ip_adress">
                    </div> 
                    </div>    
                </div>
                <div class="row"> 
                    <div class="col-lg-6">
                    <div class="form-group">
                    <label for="" class="col-form-label">Dirección</label>
                    <input type="text" class="form-control" id="home_adress">
                    </div>               
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                    <label for="" class="col-form-label">Telefono</label>
                    <input type="text" class="form-control" id="phone">
                    </div>
                    </div>  
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                        <label for="" class="col-form-label">Mensual</label>
                        <input type="text" class="form-control" id="month_payment">
                        </div>
                    </div>    
                    <div class="col-lg-3">    
                        <div class="form-group">
                        <label for="" class="col-form-label">Mbps</label>
                        <input type="number" class="form-control" id="mbps">
                        </div>            
                    </div>
                    <div class="col-lg-6">    
                        <div class="form-group">
                        <label for="" class="col-form-label">Fecha de instalación</label>
                        <input type="text" class="form-control" id="install_date">
                        </div>            
                    </div>     
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
            </div>
        </form>    
        </div>
    </div>
</div>  

<div class="modal fade" id="modal_history" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>

        <form id="history_form">    
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Descripción</label>
                            <input type="text" class="form-control" id="description">
                        </div>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="" class="col-form-label">Fecha</label>
                            <input type="text" class="form-control" id="date_edit">
                        </div>               
                    </div>
                    <div class="col-lg-6">
                    <div class="form-group">
                    <label for="" class="col-form-label">Monto</label>
                    <input type="text" class="form-control" id="payment_edit">
                    </div>
                    </div>  
                </div>              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" id="btn-save-history" class="btn btn-dark">Guardar</button>
            </div>
        </form>    
        </div>
    </div>
</div>

<div class="modal fade" id="modal_password" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>

        <form id="password_form">    
            <div class="modal-body text-center">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Contraseña actual</label>
                            <input type="password" class="form-control text-center" id="current_pass">
                        </div>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Nueva contraseña</label>
                            <input type="password" class="form-control text-center" id="new_pass">
                        </div>               
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="" class="col-form-label">Confirmar nueva contraseña</label>
                            <input type="password" class="form-control text-center" id="confirm_pass">
                        </div>
                    </div> 
                   <div class="col-lg-12" id="message_pass">
                   </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" id="btn-save-pass" class="btn btn-dark">Guardar</button>
            </div>
        </form>    
        </div>
    </div>
</div>

<div class="modal fade" id="modal_status" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"></h6>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>

        <form id="status_form">    
            <div class="modal-body">
                <div class="form-group">
                    <label for="" class="col-form-label">Cambiar estado</label>
                    <select class="form-control" id="status">
                        <option>Activo</option>
                        <option>Inactivo</option>
                    </select>
                </div>
                    
                <div class="form-group">
                    <label for="" class="col-form-label">Fecha</label>
                    <input type="text" class="form-control" id="status_date">
                </div>               
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="submit" id="btn-save-status" class="btn btn-dark">Guardar</button>
            </div>
        </form>    
        </div>
    </div>
</div>
      
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script> 
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script type="text/javascript" src="assets/main.js"></script>  

</body>
</html>
