$(document).ready(function() {

    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).text('Signing in...');

        $.ajax({
            url: '/auth/auth.php',
            method: 'POST',
            data: {
                action: 'login',
                user_name: $('#loginUsername').val(),
                user_password: $('#loginPassword').val()
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    if (res.role === 'admin') {
                        window.location.href = '/admin/dashboard.php';
                    } else {
                        Swal.fire({ icon: 'warning', title: 'Access Denied', text: 'Only admin can access the dashboard.' });
                        $btn.prop('disabled', false).text('Sign In');
                    }
                } else {
                    Swal.fire({ icon: 'error', title: 'Login Failed', text: res.message });
                    $btn.prop('disabled', false).text('Sign In');
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong. Please try again.' });
                $btn.prop('disabled', false).text('Sign In');
            }
        });
    });

    $('#registerForm').on('submit', function(e) {
        e.preventDefault();
        var password = $('#registerPassword').val();
        var confirm  = $('#registerConfirmPassword').val();

        if (password !== confirm) {
            Swal.fire({ icon: 'warning', title: 'Mismatch', text: 'Passwords do not match' });
            return;
        }

        var $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).text('Creating account...');

        $.ajax({
            url: '/auth/auth.php',
            method: 'POST',
            data: {
                action: 'register',
                user_name: $('#registerName').val(),
                user_password: password
            },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    Swal.fire({ icon: 'success', title: 'Account Created', text: res.message }).then(function() {
                        window.location.href = 'login.php';
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Registration Failed', text: res.message });
                    $btn.prop('disabled', false).text('Create Account');
                }
            },
            error: function() {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Something went wrong. Please try again.' });
                $btn.prop('disabled', false).text('Create Account');
            }
        });
    });

});
