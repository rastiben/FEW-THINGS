<a class="green button action-button popup-dialog" href="#contrat/add">
                    <i class="icon-plus-sign"></i>
                    Ajouter un contrat                </a>

<script>

$(document).on('click', 'a.popup-dialog', function(e) {
    e.preventDefault();
    $.contratLookup('ajax.php/' + $(this).attr('href').substr(1), function (contrat) {
        var url = window.location.href;
        if (contrat && contrat.id)
            url = 'contrat.php?id='+contrat.id;
        $.pjax({url: url, container: '#pjax-container'})
        return false;
     });

    return false;
});

</script>
