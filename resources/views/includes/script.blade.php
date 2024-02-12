<script src="{{ asset('assets') }}/js/app.js"></script>

<script src="{{ asset('assets') }}/js/pages/dashboard.js"></script>
{{-- <script src="{{ asset('assets') }}/js/extensions/sweetalert2.js"></script>> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="{{ asset('assets') }}/js/extensions/form-element-select.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#filter").on("click", function(e) {
        e.preventDefault();
        var params = {};
        $(".datatable-input").each(function() {
            var i = $(this).data("col-index");
            if (params[i]) {
                params[i] += "|" + $(this).val();
            } else {
                params[i] = $(this).val();
            }
        });
        $.each(params, function(i, val) {
            parent
                .$("#table-data")
                .DataTable()
                .column(i)
                .search(val ? val : "", false, false);
        });
        parent.$("#table-data").DataTable().table().draw();
    });

    //reset button
    $("#reset").on("click", function(e) {
        e.preventDefault();
        $(".datatable-input").each(function() {
            $(this).val("");
            parent
                .$("#table-data")
                .DataTable()
                .column($(this).data("col-index"))
                .search("", false, false);
        });
        parent.$("#table-data").DataTable().table().draw();
    });
</script>
