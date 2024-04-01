<?php 
include('../includes/header_s.php');
include('../includes/header.php');
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>System Settings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">System Settiings</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-user">Add New User</button>
                            <div class="modal fade" id="modal-user">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">User Information Form</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="userForm" action="" method="POST">
                                                <div class="form-group">
                                                    <label for="firstName">First Name:</label>
                                                    <input type="text" class="form-control" id="firstName" name="firstName" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="lastName">Last Name:</label>
                                                    <input type="text" class="form-control" id="lastName" name="lastName" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="username">Username:</label>
                                                    <input type="text" class="form-control" id="username" name="username">
                                                </div>
                                                <div class="form-group">
                                                    <label for="contactNumber">Contact Number:</label>
                                                    <input type="text" class="form-control" id="contactNumber" name="contactNumber" minlength="11" maxlength="11" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="emailAddress">Email Address:</label>
                                                    <input type="text" class="form-control" id="emailAddress" name="emailAddress" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Password:</label>
                                                    <div class="input-group">
                                                        <input type="password" class="form-control" id="password" name="password" required>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">Show</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="designation">Designation:</label>
                                                    <select class="form-control" name="designation" id="designation">
                                                        <option value=""></option>
                                                        <option value="1">Administrator</option>
                                                        <option value="2">Store Manager</option>
                                                        <option value="3">Cashier</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Designation</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM tbl_user";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) { 
                                            $type = $row['usertype'];
                                            $status = $row['status'];?>
                                            <tr>
                                                <td><?php echo $row['fname'].' '.$row['lname'];?></td>
                                                <td><?php echo $row['contact'];?></td>
                                                <td><?php echo $row['email'];?></td>
                                                <td><?php echo $row['username'];?></td>

                                                <td>
                                                    <?php 
                                                    if ($type == 1) {
                                                        echo '<span class="badge badge-success">Administrator</span>';
                                                    } elseif ($type == 2) {
                                                        echo '<span class="badge badge-primary">Manager</span>';
                                                    } elseif ($type == 3) {
                                                        echo '<span class="badge badge-warning">Cashier</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    if ($status == 1) {
                                                        echo '<span class="badge badge-success">Active</span>';
                                                    } elseif ($status == 2) {
                                                        echo '<span class="badge badge-danger">Deactivated</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                                                    <?php if ($status == 1) { ?>
                                                        <a href="#" class="btn btn-danger btn-sm deactUser" data-id="<?php echo $row['user_id']; ?>"><i class="fas fa-lock"></i></a>
                                                    <?php } elseif ($status == 2) { ?>
                                                        <a href="#" class="btn btn-warning btn-sm activateUser" data-id="<?php echo $row['user_id']; ?>"><i class="fas fa-unlock"></i></a>
                                                    <?php } ?>
                                                    <a href="#" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include('../includes/footer_s.php');?>
<script>
    $(document).ready(function() {
        // Function to toggle password visibility
        $('#togglePassword').on('click', function() {
            var passwordField = $('#password');
            var passwordFieldType = passwordField.attr('type');
            if (passwordFieldType === 'password') {
                passwordField.attr('type', 'text');
                $(this).text('Hide');
            } else {
                passwordField.attr('type', 'password');
                $(this).text('Show');
            }
        });

        // Function to validate password
        function validatePassword(password) {
            // Regex to check for at least one uppercase letter, one number, and one special character
            var uppercaseRegex = /[A-Z]/;
            var numberRegex = /[0-9]/;
            var specialCharRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
            return uppercaseRegex.test(password) && numberRegex.test(password) && specialCharRegex.test(password);
        }

        $('#userForm').submit(function(e) {
            e.preventDefault();
            var password = $('#password').val();
            if (!validatePassword(password)) {
                alert('Password must contain at least one uppercase letter, one number, and one special character.');
                return false;
            }
            var formData = $(this).serialize();
            $.ajax({
                url: 'process_user.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log(response);
                    $('#modal-user').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });

    $(document).ready(function() {
        $('.deactUser').on('click', function(e) {
            e.preventDefault();

            var userId = $(this).data('id');

            if (confirm('Are you sure you want to deactivate this user?')) {
                $.ajax({
                    type: 'POST',
                    url: '../ajax/ajax.php',
                    data: { action: 'deactUser', userId: userId },
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    });

    $(document).ready(function() {
        $('.activateUser').on('click', function(e) {
            e.preventDefault();

            var userId = $(this).data('id');

            if (confirm('Are you sure you want to activate this user?')) {
                $.ajax({
                    type: 'POST',
                    url: '../ajax/ajax.php',
                    data: { action: 'activateUser', userId: userId },
                    success: function(response) {
                        console.log(response);
                        location.reload();
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    });
</script>
