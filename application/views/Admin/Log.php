<div class="main-content">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title">Master Log</h4>
					<p class="card-title-desc">Logs data can be filtered by category and date.</p>

					<!-- Filter Section -->
					<div class="row mb-4">
						<div class="col-md-4">
							<label for="filter_category" class="form-label">Filter by Category</label>
							<select id="filter_category" class="form-select">
								<option value="">All Categories</option>
							</select>
						</div>
						<div class="row mb-4 mt-2">
						

							<div class="col-md-4">
								<select id="yearpicker" class="form-control">
								</select>
							</div>
							<div class="col-md-4">
								<input type="text" id="datepicker" placeholder="Rentang Tanggal" class="form-control">
							</div>
						</div>

					</div>

					<!-- DataTable -->
					<table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
						<thead>
							<tr>
								<th>No</th>
								<th>Category</th>
								<th>Jenis User <br> User</th>
								<th>Log Message</th>
								<th>Date</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		var datatable = $('#datatable').DataTable({
			'processing': true,
			'serverSide': true,
			'ajax': {
				url: '<?= base_url("MasterLog/getLogs"); ?>',
				type: 'POST',
				data: function(d) {
					d.category = $('#filter_category').val();
					d.datepicker = $('#datepicker').val();
					d.year = $('#yearpicker').val();

				}
			},
			'columns': [{
					data: 'no'
				},
				{
					data: 'kategori_log'
				},
				{
					data: 'admin_name'
				},
				{
					data: 'log_message'
				},
				{
					data: 'created_at'
				}
			]
		});
		$.ajax({
            url: '<?= base_url("MasterLog/getCategories"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    let options = '<option value="">All Categories</option>';
                    response.data.forEach(function(category) {
                        options += `<option value="${category.id_kategori_log}">${category.nama_kategori_log}</option>`;
                    });
                    $('#filter_category').html(options);
                }
            },
            error: function() {
                alert('Failed to load categories.');
            }
        });
		$('#start_date, #end_date').datepicker({
			dateFormat: 'yy-mm-dd', // Format tanggal
			changeMonth: true,
			changeYear: true
		});
		$('#filterLogs').on('click', function() {
			const startDate = $('#start_date').val();
			const endDate = $('#end_date').val();

			// Validasi range tanggal
			if (startDate === '' || endDate === '') {
				Swal.fire('Error', 'Pilih tanggal awal dan akhir!', 'error');
				return;
			}

			if (startDate > endDate) {
				Swal.fire('Error', 'Tanggal awal tidak boleh lebih dari tanggal akhir!', 'error');
				return;
			}

			// Reload DataTable dengan parameter range tanggal
			$('#datatable').DataTable().ajax.reload();
		});
		$('#apply_filter').on('click', function() {
			datatable.ajax.reload();
		});
		$('#filter_category').on('change', function() {
			datatable.ajax.reload();
		});
		let currentYear = new Date().getFullYear();
		for (let i = currentYear; i >= currentYear - 10; i--) {
			$('#yearpicker').append($('<option>', {
				value: i,
				text: i
			}));
		}

		$('#datepicker').datepicker({
			numberOfMonths: 1,
			maxDate: '+0D',
			dateFormat: 'mm-dd',
			onSelect: function(selectedDate) {
				let year = $('#yearpicker').val();
				if (!$(this).data().datepicker.first) {
					$(this).data().datepicker.inline = true;
					$(this).data().datepicker.first = selectedDate;
				} else {
					if (selectedDate > $(this).data().datepicker.first) {
						$(this).val(year + '-' + $(this).data().datepicker.first + ' / ' + year + '-' + selectedDate);
					} else {
						$(this).val(year + '-' + selectedDate + ' / ' + year + '-' + $(this).data().datepicker.first);
					}
					$(this).data().datepicker.inline = false;
				}
				// alert($(this).val());
				datatable.ajax.reload();
			},
			onClose: function() {
				delete $(this).data().datepicker.first;
				$(this).data().datepicker.inline = false;
			}
		});
	});
</script>
