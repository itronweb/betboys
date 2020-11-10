    $(function() {
        function expand() {
            var obj = this;
            var lines = $(obj).val().split('\n').length;
            $(obj).attr('rows', Math.max($(obj).attr('data-rows-orig'), lines + 1) );
        }

        $('textarea').on('input', expand).each(function() {
            var rows = ($(this).attr('rows') || '').toString();
            if (rows.length) $(this).attr('data-rows-orig', rows);
        });
    });
