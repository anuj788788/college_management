</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.0.5
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>

<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<script>
    $(document).ready(function() {

        $('#datatableid').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search Your Data",
            }
        });

    });
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.deletebtn', function() {
            $('#deletemodal').modal('show'); // Show the delete modal
            let $tr = $(this).closest('tr'); // Get the closest table row
            let data = $tr.children("td").map(function() {
                return $(this).text().trim(); // Get text from each cell
            }).get();

            console.log(data); // Debugging: log data to verify correctness
            $('#delete_id').val(data[0]); // Assign the ID to the hidden input in the modal
        });
    });
</script>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to deletecode.php with the student ID to delete
                window.location.href = 'deletecode.php?delete_id=' + id;
            }
        });
    }
</script>
<script>
    function putEditValues(id, name, email, username, password, age, phone, role_as) {
        $(document).ready(function() {

            // Set the modal inputs with the current values
            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_username').val(username);
            $('#edit_password').val(password);
            $('#edit_age').val(age);
            $('#edit_phone').val(phone);
            $('#edit_role_as').val(role_as);

            $('#editModal').modal('show');

        })
    }
</script>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('edit_password');
        const icon = document.getElementById('password-icon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.all.min.js"></script>

<!-- jQuery and AJAX for Real-Time Validation -->
<script>
    $(document).ready(function() {
        // Validate Email
        $("#email").on("keyup", function() {
            const email = $(this).val();
            if (email.length > 0) {
                $.ajax({
                    url: "validate.php",
                    type: "POST",
                    data: {
                        email: email,
                        action: "check_email"
                    },
                    success: function(response) {
                        $("#emailCheck").html(response);
                    },
                });
            } else {
                $("#emailCheck").html("");
            }
        });

        // Validate Username
        $("#username").on("keyup", function() {
            const username = $(this).val();
            if (username.length > 0) {
                $.ajax({
                    url: "validate.php",
                    type: "POST",
                    data: {
                        username: username,
                        action: "check_username"
                    },
                    success: function(response) {
                        $("#usernameCheck").html(response);
                    },
                });
            } else {
                $("#usernameCheck").html("");
            }
        });

        // Validate Phone
        $("#phone").on("keyup", function() {
            const phone = $(this).val();
            if (phone.length > 0) {
                $.ajax({
                    url: "validate.php",
                    type: "POST",
                    data: {
                        phone: phone,
                        action: "check_phone"
                    },
                    success: function(response) {
                        $("#phoneCheck").html(response);
                    },
                });
            } else {
                $("#phoneCheck").html("");
            }
        });
    });
</script>

</body>

</html>