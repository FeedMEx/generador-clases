
function show_message(message){

    document.getElementById('message_login').innerHTML = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>`
}
$(document).ready(() => {
    $('#login_form').submit((e) => {         
        e.preventDefault();
        let user = $('#user').val();    
        let password = $('#password').val();
        $.ajax({
            url: '/db/login.php',
            method: 'POST',
            datatype:"json",
            data: {
                user: user,
                password: password
            },
            success: (response) => {
                let records = JSON.parse(response);
                records.forEach(record => {
                    if (record.message == true){
                        location.href = 'clients';
                    } else {
                        show_message('Login incorrecto');
                        $("#login_form").trigger("reset");
                    } 
                });
            }
        });
                    
    });
});
