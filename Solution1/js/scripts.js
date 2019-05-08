$(document).ready(function() {
    console.log('Document is ready');
    $("#searchParam").keyup(function(){
        console.log('Keyup is fired');
        searchParam = $('#searchParam').val();
        $.post("src/scripts.php",
            {
                searchParam: searchParam,
            },
            function(data, status){
                $('#response').empty();
                data = JSON.parse(data);
                if (data.length > 0) {
                    $('#response').append('<table>');
                    data.forEach(function(line) {
                        $('#response').append('<tr><td>' + line[0] + '</td><td>' + line[1] + '</td></tr>')
                    })
                    $('#response').append('</table>');
                } else {
                    $('#response').append('No restaurants found...');
                }
            });
    });
});

