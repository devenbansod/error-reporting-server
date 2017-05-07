$(document).ready(function () {
	oTable = $('#reports_table').dataTable({
		"bSortCellsTop": true,
		"bProcessing": true,
		"bServerSide": true,
		"bStateSave": true,
		"sAjaxSource": $('#reports_table').data('ajax-url'),
		"aoColumnDefs": [
			{ "bSearchable": false, "aTargets": [ 1, 6, 7 ] },
			{ "sClass": "center", "aTargets": [ 0, 1, 4, 5, 6, 7] },
			{ "fnRender": function (oObj) {
					return '<a class="block" href="/reports/view/' + oObj.aData[1] +
						'">' + oObj.aData[1] + '</a>';
				},
				"aTargets": [ 1 ]
			}
		],
		"aoColumns": [
			{ "sWidth": "1%" },
			{ "sWidth": "7%" },
			{ "sWidth": "20%" },
			{ "sWidth": "48%" },
			{ "sWidth": "10%" },
			{ "sWidth": "10%" },
			{ "sWidth": "10%" },
			{ "sWidth": "5%" }
		],
		"fnServerData": function (sSource, aoData, fnCallback) {
			$.getJSON(sSource, aoData, function (json) {
				fnCallback(json);
				// setup necessary CSS for linkable rows.
				$('#reports_table tbody tr').hover(function() {
					$(this).css('cursor', 'pointer');
				}, function() {
					$(this).css('cursor', 'auto');
				});
				// Stop Redirecting upon checkbox click event
				$('#reports_table td input').click(function (e) {
					e.stopPropagation();
				});
			});
		},
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
			// click on the row anywhere to go to the report.
			$(nRow).click(function (event) {
				if (event.ctrlKey || event.which == 2) {
					event.stopPropagation();
				} else {
					// extract the href from the anchor string
					var url = $($.parseHTML(aData[1])).attr('href');
					document.location.href = url;
				}
			});
		},
		"fnInitComplete": function(oSettings) {
			$(this.find("select")).each( function(index) {
				if (index == 0 && oSettings.aoPreSearchCols[index+2].sSearch.length>0) {
					// Exception Name selector
					$(this).val(oSettings.aoPreSearchCols[index+2].sSearch);
				} else if (oSettings.aoPreSearchCols[index+3].sSearch.length>0) {
					//Other selectors
					$(this).val(oSettings.aoPreSearchCols[index+3].sSearch);
				}
			});

			$('input').addClass('form-control');
			$('input[type=checkbox]').removeClass('form-control');
			$('select').addClass('form-control');
		}
	});

	$('#notifications_table').dataTable({
		"bSortCellsTop": true,
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": $('#notifications_table').data('ajax-url'),
		"aoColumnDefs": [
			{ "bSearchable": false, "aTargets": [ 1 ] },
			{ "sClass": "center", "aTargets": [ 0, 1, 2, 3, 4, 5 ] }
		],
		"aoColumns": [
			{ "sWidth": "5%" },
			{ "sWidth": "10%" },
			{ "sWidth": "15%" },
			{ "sWidth": "30%" },
			{ "sWidth": "10%" },
			{ "sWidth": "10%" },
			{ "sWidth": "10%" }
		],
		"fnServerData": function (sSource, aoData, fnCallback) {
			$.getJSON(sSource, aoData, function (json) {
				fnCallback(json);
				// setup necessary CSS for linkable rows.
				$('#notifications_table tbody tr').hover(function() {
					$(this).css('cursor', 'pointer');
				}, function() {
					$(this).css('cursor', 'auto');
				});
				// Stop Redirecting upon checkbox click event
				$('#notifications_table td input').click(function (e) {
					e.stopPropagation();
				});
			});
		},
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
			// click on the row anywhere to go to the report.
			$(nRow).click(function () {
				// extract the href from the anchor string
				document.location.href = $($.parseHTML(aData[1])).attr('href');

			});
		},
		"fnInitComplete": function (oSettings) {
			$('input').addClass('form-control');
			$('select').addClass('form-control');
		}
	});

	oTable.find("input").on('keyup', function (e) {
		// only search when enter is pressed
		if (e.keyCode == 13) {
			oTable.fnFilter($(this).val(), oTable.find("tr:last-child th").index($(this).parent()));
		}
	});

	oTable.find("select").on('change', function (e) {
		oTable.fnFilter($(this).val(), oTable.find("tr:last-child th").index($(this).parent()));
	});

	$('#toggle-stacktrace').click(function (e) {
		if ($('#stacktrace').hasClass('shown')) {
			$('#toggle-stacktrace').html('Show stacktrace');

			$('#stacktrace').slideUp(function () {
				$(this).removeClass('shown');
			});
		} else {
			$('#toggle-stacktrace').html('Hide stacktrace');

			$('#stacktrace').slideDown(function () {
				$(this).addClass('shown');
			});
		}
		return false;
	});

		$('#resultsForm_checkall').click(function () {
			if($(this).attr('checked') == 'checked') {
				$('#reports_table td input').attr('checked', 'checked');
			} else {
				$('#reports_table td input').removeAttr('checked');
			}
		});

	// display notifications count
	if (notifications_count > 0)
	{
		$('#nav_notifications a').html($('#nav_notifications a').html() + '(' + notifications_count + ')');
	}

	setTimeout(
		function () {
			$(".alert.alert-success").slideUp();
		},
		2000
	);

	SyntaxHighlighter.defaults.toolbar = false;
	SyntaxHighlighter.all();
});

function showStateForm() {
	$('#state-form').slideToggle();
}
