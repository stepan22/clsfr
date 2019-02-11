<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Classifier</title>

		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css">
	</head>
	<body>
		<div class="wrap">
			<div class="navbar navbar-default navbar-static-top" role="navigation">
				<div class="container">
					<div class="navbar-header">
						<a class="navbar-brand" href="#">Classifier</a>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="row" id="design_panel">
					<div class="col-md-12">
						<div class="panel panel-primary">
							<div class="panel-heading panel-toggle">
								<h3 class="panel-title"><i class="far fa-list"></i> Classifier
									<span class="panel-toggle-indicator pull-right far fa-caret-square-down"></span>
								</h3>
							</div>
							<div class="panel-body">
								<div id="upload_form" class="form-group form-horizontal claimsTable">
									<input type="file" id="fileUpload" />
									<input type="button" id="upload" value="Upload" />
								</div>
								<div id="table_form" class="form-horizontal claimsTable">
									<table id="table_classifier" class="table table-sortable margin-bottom-0">
										<colgroup>
											<col width="60%">
											<col width="40%">
										</colgroup>
										<thead>
											<tr>
												<th>ID</th>
												<th>Text</th>
												<th>Classifier</th>
												<th>Action</th>
											</tr>
										</thead>
									</table>
								</div>
								<button id="btn_classify" type="button" class="btn btn-success">Classify</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script
			  src="http://code.jquery.com/jquery-3.3.1.min.js"
			  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
			  crossorigin="anonymous">
		</script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
		<script type="text/javascript">
			$(function () {
				//$("#upload").bind("click", function () {
				$('#upload').click(function() {
				    var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.csv|.txt)$/;
				    if (regex.test($("#fileUpload").val().toLowerCase())) {
				        if (typeof (FileReader) != "undefined") {
				            var reader = new FileReader();
				            reader.onload = function (e) {
				                var items = {};
				                var data = [];
				                var rows = e.target.result.split("\r\n");
				                for (var i = 0; i < rows.length; i++) {
				                    var cells = rows[i].split(",");
				                    if ((cells.length > 1) && (cells[0] != 'id')) {
				                        var classy = {};
				                        classy.id = cells[0];  
				                        classy.text = cells[1];
				                        classy.topic = cells[2];
				                        data.push(classy);
				                    }
				                }
								items.method = 'save';
				                items.data = data;

				                $.post("requests.php", JSON.stringify(items));

				                showTable();
				            }
				            reader.readAsText($("#fileUpload")[0].files[0]);
				        } else {
				            alert("This browser does not support HTML5.");
				        }
				    } else {
				        alert("Please upload a valid CSV file.");
				    }
				});

				$('#btn_classify').click(function() {
					 var items = {};
					items.method = 'classifier';
					//items.data = data;
					$.post("requests.php", JSON.stringify(items));
				});
			});

			function showTable(items = null) {
				if (items) {
					$('#table_classifier').DataTable(
					{
						destroy: true,
						data: items,
						columns: [
							{ "data": "id" },
							{ "data": "text" },
							{ "data": "topic" }
						],
						columnDefs: [
							{
								"targets": [0],
								"visible": false,
								"searchable": false
							},
						],
						buttons: [
							'copy', 'excel', 'pdf'
						],
					});
				} else {
					$('#table_classifier').DataTable(
					{
						destroy: true,
						ajax: "requests.php?method=get",
						columns: [
							{ "data": "id" },
							{ "data": "text" },
							{ "data": "topic" }
						],
						columnDefs: [
							{
								"targets": [0],
								"visible": false,
								"searchable": false
							},
						],
						buttons: [
							'copy', 'excel', 'pdf'
						],
					});
				}
			}

			showTable();
			
		</script>
	</body>
</html>

