<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Php, MySQL, AJAX Demo</title>
	<!-- Inayos: Bootstrap 5.3.3 CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
	<div class="container my-5">
		<h2 class="text-center">User Management (Basic CRUD with PHP, MYSQL and AJAX)</h2>
		<!-- button for opening modal -->
		<button type="button" class="btn btn-primary btn-sm" id="btnOpenAddModal">Add New User</button>
		<?php include 'add_user_modal.php'; ?>
		<div id="alertBox" class="mt-3"></div>

		<div class="card shadow-sm">
			<div class="card-body">
				<table class="table table-striped table-hover mb-0">
					<thead>
						<tr>
							<th>ID</th>
							<th>NAME</th>
							<th>EMAIL</th>
							<th>PHONE</th>
							<th>ACTION</th>
						</tr>
					</thead>
					<tbody id="userData"></tbody>
				</table>
			</div>
		</div>
	</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> 
<!-- Inayos: Bootstrap 5.3.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	$(document).ready(function(){
		
		function showAlert(type, message){
			$('#alertBox').html(
				`<div class="alert alert-${type} alert-dismissible fade show" role="alert">
					${message}
				 <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				 </div>`
			);
		}

		// function to display data
		function fetchUsers(){
			$.ajax({
				url: `action.php?action=read_all`,
				type: 'GET',
				dataType: 'json',
				success: function(res){
					let rows = '';
					if (res.status === 'success' && res.data.length > 0) {
						res.data.forEach(user=> {
							rows += `
								<tr>
									<td>${user.user_id}</td>
									<td>${user.name}</td>
									<td>${user.email}</td>
									<td>${user.phone}</td>
									<td>
										<button class="btn btn-primary btn-sm btn-edit" data-id="${user.user_id}">Edit</button>
										<button class="btn btn-danger btn-sm btn-delete" data-id="${user.user_id}">Delete</button>
									</td>
								</tr>
							`;
						});
					}else{
						rows = `<tr><td colspan="5" class="text-center">No Users Found...</td></tr>`;
					}

					$("#userData").html(rows);
				}
			});
		}

		// displaying of modal
		const btnOpenAddModal = $("#btnOpenAddModal");
		const userModal = new bootstrap.Modal(document.getElementById('userModal'));

		btnOpenAddModal.click(function(){
			$("#userForm")[0].reset();
			$("#user_id").val('');
			$("#modalTitle").text('Add New User');
			userModal.show();
		});

		// edit data
		$(document).on('click', '.btn-edit', function (){
			const id = $(this).data('id');$.ajax({
				url: `action.php?action=read_one&id=${id}`,
				type: 'GET',
				dataType: 'json',
				success: function(res){
					if (res.status === 'success') {
						$("#user_id").val(res.data.user_id);
						$("#name").val(res.data.name);
						$("#email").val(res.data.email);
						$("#phone").val(res.data.phone);
						$('#modalTitle').text('Edit User Details');
						userModal.show();
					}
				}
			});
		});

		// submit data
		$('#userForm').submit(function(e){
			e.preventDefault();
			const userId = $("#user_id").val();
			const action = userId ? 'update' : 'create';
			
			$.ajax({
				url: `action.php?action=${action}`,
				type: 'POST',
				data: $(this).serialize(),
				dataType: 'json',
				success: function(res){
					if (res.status === 1) {
						userModal.hide();
						showAlert('success',res.msg);
						fetchUsers();
					}else if(res.status === 0){
						userModal.hide();
						showAlert('danger',res.msg);
						fetchUsers();
					}
				}
			});
		});

		// Delete User
    $(document).on('click', '.btn-delete', function () {
        if (confirm('Are you sure you want to delete this user?')) {
            const id = $(this).data('id');$.ajax({
                url: 'action.php?action=delete',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        fetchUsers();
                        showAlert('success',res.msg);
                    } else {
                        showAlert('danger',res.msg);
                    }
                }
            });
        }
    });

		fetchUsers();
	});
</script>
</body>
</html>